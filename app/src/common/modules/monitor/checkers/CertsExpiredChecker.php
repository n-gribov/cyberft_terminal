<?php
namespace common\modules\monitor\checkers;

use common\models\Terminal;
use common\modules\certManager\models\Cert;
use common\modules\monitor\models\ExpiringCert;
use Yii;
use common\helpers\CertsHelper;

class CertsExpiredChecker extends BaseChecker
{
    /**
     * Количество дней за которое необходимо
     * напомнить об истечении сертификатов
     * @var int
     */
    private $_notifyDays = 7;

    const ITERATION_INTERVAL = 86400;

    protected function checkByTerminalId($terminalId, $data = []) {
        $certs = $this->searchExpired($terminalId);

        $expiredCerts = CertsHelper::filterExpiredCerts($certs);

        // Если есть истекшие сертификаты,
        // отправляем сообщение
        if ($expiredCerts) {
            $this->notify($this->getParams(['certs' => $expiredCerts]), $terminalId);
            return true;
        }

        return false;
    }

    public function getParams($data = null)
    {
        return [
            'subject' => Yii::t('monitor/mailer', 'Expired certificates notification'),
            'view'    => '@common/modules/monitor/views/mailer/certsExpired',
            'certs'   => $data['certs']
        ];
    }

    /**
     * Представление для вывода настроек чекера
     * @return string
     */
    public function getFormRowView()
    {
        return '@common/modules/monitor/views/checkers/certsExpired';
    }

    public function attributeLabels()
    {
        return [
            'notifyDays'   => Yii::t('monitor/events', 'Notify days'),
        ];
    }

    /**
     * Get code label
     *
     * @return string
     */
    public function getCodeLabel() {
        return Yii::t('monitor/events', 'Expire certificates');
    }

    private function searchExpired($terminalId)
    {
        $terminalCode = null;

        if ($terminalId) {
            $terminal = Terminal::findOne($terminalId);
            $terminalCode = $terminal->terminalId;
        }

        // Получаем список сертификатов (активные, и с заполненными датами истечения)
        $allCerts = [];

        // Ключи контроллера
        $allCerts = array_merge($allCerts, CertsHelper::getAutobotCerts($terminalCode));

        // Общие сертификаты
        $allCerts = array_merge($allCerts, CertsHelper::getCerts($terminalCode, Cert::ROLE_SIGNER));

        // КриптоПро ISO20022 (верификация входящих)
        $allCerts = array_merge($allCerts, CertsHelper::getCryptoProCerts('ISO20022', $terminalId));

        // КриптоПро Fileact (верификация входящих)
        $allCerts = array_merge($allCerts, CertsHelper::getCryptoProCerts('fileact', $terminalId));

        // КриптоПро
        $allCerts = array_merge($allCerts, CertsHelper::getCryptoProKeys($terminalId));

        // Массив истекших сертификатов
        $expiredCerts = [];

        foreach($allCerts as $cert) {
            $datetime1 = new \DateTime(date('Y-m-d H:i:s'));
            $datetime2 = new \DateTime($cert->date);
            $interval = $datetime1->diff($datetime2);
            $intervalDays = $interval->days;

            if ($datetime1 > $datetime2) {
                $cert->isExpired = true;
                $intervalDays = -$intervalDays;
            } else {
                $cert->isExpired = false;
            }

            if ($intervalDays > $this->getNotifyDays($terminalId)) {
                continue;
            }

            if (empty($cert->terminalName)) {
                $cert->terminalName = ExpiringCert::getTerminalName($cert->terminal);
            }

            $expiredCerts[] = $cert;
        }

        return $expiredCerts;
    }

    public function getNotifyDays($terminalId)
    {
        $settings = $this->getSettingsData($terminalId);

        if (isset($settings['notifyDays'])) {
            return $settings['notifyDays'];
        } else {
            return $this->_notifyDays;
        }
    }
}