<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use yii\helpers\Html;
use common\modules\monitor\models\MonitorLogAR;

class CreateUserEvent extends BaseEvent
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
        return 'Создание пользователя';
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return Yii::t('monitor/events',
            '{initiator} {user} create user {newUser}',
            [
                'user' => $this->getUserData()['userLink'],
                'newUser' => $this->getUserData($this->newUserId)['userLink'],
                'initiator' => $this->_initiatorType 
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : '',
            ]
        );
    }
}