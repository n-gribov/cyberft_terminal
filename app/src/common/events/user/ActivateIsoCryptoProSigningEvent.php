<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use common\modules\monitor\models\MonitorLogAR;

class ActivateIsoCryptoProSigningEvent extends BaseEvent
{
    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function getCodeLabel()
    {
        return 'ISO20022 - активация подписания КриптоПро';
    }

    public function getLabel()
    {
        return Yii::t('monitor/events',
            '{initiator} {user} edit ISO20022 settings - activate CryptoPro signing',
            [
                'user' => $this->getUserData()['userLink'],
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}