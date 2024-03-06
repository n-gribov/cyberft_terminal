<?php

namespace common\events\user;

use Yii;
use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;

class EditAutosignTerminalEvent extends BaseEvent
{
    public $id;

    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function rules()
    {
        return [
            ['id','string']
        ];
    }

    public function getCodeLabel()
    {
        return 'Изменение настроек автоматического подписания';
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return Yii::t('monitor/events',
            '{initiator} {user} edit autosigning terminal ({id}) settings',
            [
                'user' => $this->getUserData()['userLink'],
                'id' => $this->id,
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}