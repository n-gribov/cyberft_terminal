<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use common\modules\monitor\models\MonitorLogAR;

class EditGeneralSettingsEvent extends BaseEvent
{
    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public $terminalId;

    public function rules()
    {
        return [
            ['terminalId', 'string']
        ];
    }

    public function getCodeLabel()
    {
        return 'Изменение общих настроек';
    }

    public function getLabel()
    {
        return Yii::t('monitor/events',
            '{initiator} {user} edit general settings on terminal {terminal}',
            [
                'user' => $this->getUserData()['userLink'],
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : '',
                'terminal' => $this->terminalId
            ]
        );
    }
}