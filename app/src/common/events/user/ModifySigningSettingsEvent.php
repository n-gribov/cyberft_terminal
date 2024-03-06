<?php

namespace common\events\user;

use Yii;
use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;

class ModifySigningSettingsEvent extends BaseEvent
{
    public $id;
    public $terminalId;

    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function rules()
    {
        return [
            [['id', 'terminalId'], 'string']
        ];
    }

    public function getCodeLabel()
    {
        return 'Изменение настроек подписания';
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return Yii::t('monitor/events',
            '{initiator} {user} edit signing settings for module {id} on terminal {terminal}',
            [
                'user' => $this->getUserData()['userLink'],
                'id' => $this->id,
                'terminal' => $this->terminalId,
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}