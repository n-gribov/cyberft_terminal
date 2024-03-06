<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use common\modules\monitor\models\MonitorLogAR;

class ActivateSwiftDocumentPrintEvent extends BaseEvent
{
    public $types;
    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function getCodeLabel()
    {
        return 'Изменение настроек модуля SwiftFIN';
    }

    public function getLabel()
    {
        $typeStr = implode(', ', $this->types);

        return Yii::t('monitor/events',
            '{initiator} {user} edit swiftfin settings - activate SWIFTFIN document printing {type}',
            [
                'user' => $this->getUserData()['userLink'],
                'type' => $typeStr,
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}