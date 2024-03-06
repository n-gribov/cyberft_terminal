<?php
namespace common\modules\monitor\checkers;

use common\modules\monitor\models\MonitorLogAR;
use Yii;

class ChangeCertStatusChecker extends BaseChecker
{
    protected function checkByTerminalId($terminalId, $data = [])
    {
        $activeSince = $data['activeSince'];
        $opData = $data['opData'];

        $lastId = !empty($opData['lastId']) ? $opData['lastId'] : 0;

        $query = MonitorLogAR::find()->where(['eventCode' => 'ChangeCertStatus']);
        $query->andWhere(['>', 'id' , $lastId]);
        $query->andWhere(['>=', 'dateCreated' , $activeSince]);
        $query->orderBy('id ASC');

        // Запрос событий по конкретному
        // терминалу, если он указан
        if ($terminalId) {
            $query->andWhere(['terminalId' => $terminalId]);
        }

        $events = $query->all();

        if (count($events) > 0) {
            $certs = $this->getCertificatesList($events);

            // Если данные по сертификатам не сформировались, прекращаем действие чекера
            if (empty($certs)) {
                return false;
            }

            $this->notify($this->getParams(['certs' => $certs]), $terminalId);
            $lastEvent = array_pop($events);
            $this->setOpData('lastId', $lastEvent->id, $terminalId);

            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function getParams($data = null)
    {
        return [
            'subject' => Yii::t('monitor/mailer', 'Change certificate status'),
            'view'    => '@common/modules/monitor/views/mailer/changeCertStatus',
            'certs' => $data['certs']
        ];
    }

    /**
     * Get code label
     *
     * @return string
     */
    public function getCodeLabel()
    {
        return Yii::t('monitor/events', 'Change certificate status');
    }

    /**
     * Метод формирует массив с информаций по изменениям сертификатов
     */
    private function getCertificatesList($events)
    {
        try {

            $certs = [];

            foreach ($events as $value) {
                $params = unserialize($value['params']);

                $certs[] = [
                    'certName' => $params['certName'],
                    'status' => $params['status'],
                    'reason' => $params['reason']
                ];
            }

            return $certs;

        } catch (\Exception $ex) {
            \Yii::warning($ex->getMessage());

            return null;
        }
    }

}