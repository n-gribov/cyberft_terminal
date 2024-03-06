<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use common\modules\monitor\models\MonitorLogAR;

class ActivateIsoUseSerialNumberCertEvent extends BaseEvent
{
    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function getCodeLabel()
    {
        return 'ISO20022 - активация использования серийного номера сертификата вместо отпечатка';
    }

    public function getLabel()
    {
        return Yii::t('monitor/events',
            '{initiator} {user} edit ISO20022 settings - activate use certificate serial number instead fingerprint',
            [
                'user' => $this->getUserData()['userLink'],
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}