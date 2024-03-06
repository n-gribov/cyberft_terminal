<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;
use Yii;

class PasswordResetRequestEvent extends BaseEvent
{
    public $email;
    public $subjectUserId;

    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_USER;
    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function rules()
    {
        return [
            ['email', 'string'],
            ['subjectUserId', 'integer'],
            ['ip', 'default', 'value' => Yii::$app->getRequest()->getUserIP()],
        ];
    }

    public function getCodeLabel()
    {
        return 'Запрос на восстановлене пароля';
    }

    public function getLabel()
    {
        return Yii::t('monitor/events',
            'Password reset for user {user} has been requested, email: {email}',
            [
                'email' => $this->email,
                'user'  => $this->getUserData($this->subjectUserId)['userLink'],
            ]
        );
    }
}
