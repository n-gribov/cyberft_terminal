<?php

namespace common\events\user;

use Yii;
use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;

class DeleteControllerKeyEvent extends BaseEvent
{
    public $id;

    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function rules()
    {
        return [
            ['id','integer']
        ];
    }

    public function getCodeLabel()
    {
        return 'Удаление ключа Контролера';
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return Yii::t('monitor/events',
            '{initiator} {user} delete controller key ({id})',
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