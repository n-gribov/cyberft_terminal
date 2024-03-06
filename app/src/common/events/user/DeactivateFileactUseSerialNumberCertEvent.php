<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use common\modules\monitor\models\MonitorLogAR;

class DeactivateFileactUseSerialNumberCertEvent extends BaseEvent
{
    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function getCodeLabel()
    {
        return 'Fileact - деактивация использования серийного номера сертификата вместо отпечатка';
    }

    public function getLabel()
    {
        return Yii::t('monitor/events',
            '{initiator} {user} edit Fileact settings - deactivate use certificate serial number instead fingerprint',
            [
                'user' => $this->getUserData()['userLink'],
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}