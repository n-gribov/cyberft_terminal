<?php
namespace common\modules\monitor\checkers;

use common\modules\monitor\models\MonitorLogAR;
use Yii;

class CryptoProCertExpiredChecker extends BaseChecker
{
    protected function checkByTerminalId($terminalId, $data = []) {
        $activeSince = $data['activeSince'];
        $opData = $data['opData'];

        $lastId = !empty($opData['lastId']) ? $opData['lastId'] : 0;

        $events = MonitorLogAR::find()
            ->where(['eventCode' => 'CryptoProCertExpired'])
            ->andWhere(['>', 'id' , $lastId])
            ->andWhere(['>=', 'dateCreated' , $activeSince])
            ->orderBy('id ASC')
            ->all();

        if (count($events) > 0) {
            $this->notify($this->getParams(), $terminalId);
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
            'subject' => Yii::t('monitor/mailer', 'CryptoPro license error'),
            'view'    => '@common/modules/monitor/views/mailer/cryptoProCertExpired'
        ];
    }

    /**
     * Get code label
     *
     * @return string
     */
    public function getCodeLabel() {
        return Yii::t('monitor/events', 'Expire CryptoPro certificate');
    }
}