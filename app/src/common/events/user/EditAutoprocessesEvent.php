<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use common\modules\monitor\models\MonitorLogAR;

class EditAutoprocessesEvent extends BaseEvent
{

    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function getCodeLabel()
    {
        return 'Изменение настроек обмена с сетью CyberFT';
    }

    public function getLabel()
    {
        return Yii::t('monitor/events',
            '{initiator} {user} edit CyberFT autoprocesses settings',
            [
                'user' => $this->getUserData()['userLink'],
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}