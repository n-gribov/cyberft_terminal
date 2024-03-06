<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;
use Yii;

class NonExistingUserPasswordResetRequestEvent extends BaseEvent
{
    public $email;

    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_USER;
    protected $_logLevel = \Psr\Log\LogLevel::WARNING;

    public function rules()
    {
        return [
            ['email', 'string'],
            ['ip', 'default', 'value' => Yii::$app->getRequest()->getUserIP()],
        ];
    }

    public function getCodeLabel()
    {
        return 'Запрос на восстановлене пароля для несуществующего пользователя';
    }

    public function getLabel()
    {
        return Yii::t('monitor/events',
            'Password reset for {email} has been requested',
            ['email' => $this->email]
        );
    }
}
