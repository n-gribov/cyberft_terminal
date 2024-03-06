<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;
use Yii;

class PasswordResetEvent extends BaseEvent
{
    public $subjectUserId;

    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_USER;
    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function rules()
    {
        return [
            ['subjectUserId', 'integer'],
            ['ip', 'default', 'value' => Yii::$app->getRequest()->getUserIP()],
        ];
    }

    public function getCodeLabel()
    {
        return 'Установка нового пароля';
    }

    public function getLabel()
    {
        return Yii::t('monitor/events',
            'User {user} has reset password',
            ['user'  => $this->getUserData($this->subjectUserId)['userLink']]
        );
    }
}
