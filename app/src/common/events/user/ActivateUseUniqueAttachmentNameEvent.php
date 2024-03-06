<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use common\modules\monitor\models\MonitorLogAR;

class ActivateUseUniqueAttachmentNameEvent extends BaseEvent
{
    protected $_logLevel = \Psr\Log\LogLevel::INFO;
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_ADMIN;

    public function getCodeLabel()
    {
        return Yii::t('monitor/events', 'ISOChangeSettings');
    }

    public function getLabel()
    {
        return Yii::t('monitor/events',
            '{initiator} {user} edit ISO20022 settings - activate generate unique attachment filename',
            [
                'user' => $this->getUserData()['userLink'],
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}