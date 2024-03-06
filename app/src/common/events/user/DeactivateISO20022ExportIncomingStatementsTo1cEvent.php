<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use common\modules\monitor\models\MonitorLogAR;

class DeactivateISO20022ExportIncomingStatementsTo1cEvent extends BaseEvent
{
    protected $_logLevel = \Psr\Log\LogLevel::INFO;
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;

    public function getCodeLabel()
    {
        return 'ISO20022 - деактивация экспорта входящих выписок в формат 1С';
    }

    public function getLabel()
    {
        return Yii::t('monitor/events',
            '{initiator} {user} edit ISO20022 settings - deactivate export incoming statements to 1C format',
            [
                'user' => $this->getUserData()['userLink'],
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}