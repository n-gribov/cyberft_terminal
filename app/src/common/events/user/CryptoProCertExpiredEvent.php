<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use common\modules\monitor\models\MonitorLogAR;

class CryptoProCertExpiredEvent extends BaseEvent
{
    protected $_logLevel = \Psr\Log\LogLevel::WARNING;
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;

    public function getCodeLabel()
    {
        return Yii::t('monitor/events', 'CryptoPro license status');
    }

    public function getLabel()
    {
        return Yii::t('monitor/events', 'CryptoPro license expired');
    }
}