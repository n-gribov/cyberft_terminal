<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use common\modules\monitor\models\MonitorLogAR;

class ActivateEdm1cExportEvent extends BaseEvent
{
    protected $_logLevel = \Psr\Log\LogLevel::INFO;
    
    public function getCodeLabel()
    {
        return 'Изменение настроек модуля EDM';
    }

    public function getLabel()
    {
        return Yii::t('monitor/events',
            '{initiator} {user} edit edm settings - activate incoming statement 1c export',
            [
                'user' => $this->getUserData()['userLink'],
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}