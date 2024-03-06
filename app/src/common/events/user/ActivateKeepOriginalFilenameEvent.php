<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use common\modules\monitor\models\MonitorLogAR;

class ActivateKeepOriginalFilenameEvent extends BaseEvent
{
    protected $_logLevel = \Psr\Log\LogLevel::INFO;
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;

    public function getCodeLabel()
    {
        return 'ISO20022 - активация сохранения имени исходного документа при экспорте';
    }

    public function getLabel()
    {
        return Yii::t('monitor/events',
            '{initiator} {user} edit ISO20022 settings - activate keep original filename on export',
            [
                'user' => $this->getUserData()['userLink'],
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}