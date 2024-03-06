<?php

namespace common\events\user;

use Yii;
use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;

class InputIncorrectActivationCodeEvent extends BaseEvent
{
    public $newUserId;

    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function getCodeLabel()
    {
        return 'Ошибка ввода кода активации учетной записи';
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return Yii::t('monitor/events',
            '{initiator} {user} input incorrect activation code',
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