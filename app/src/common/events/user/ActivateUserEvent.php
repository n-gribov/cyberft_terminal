<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use common\modules\monitor\models\MonitorLogAR;

class ActivateUserEvent extends BaseEvent
{
    public $newUserId;

    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function rules()
    {
        return [
            ['newUserId','integer']
        ];
    }

    public function getCodeLabel()
    {
        return 'Активация пользователя';
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return Yii::t('monitor/events',
            '{initiator} {user} activate user {newUser}',
            [
                'user' => $this->getUserData()['userLink'],
                'newUser' => $this->getUserData($this->newUserId)['userLink'],
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}