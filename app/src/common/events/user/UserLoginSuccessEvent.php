<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use common\modules\monitor\models\MonitorLogAR;

class UserLoginSuccessEvent extends BaseEvent
{
    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function getCodeLabel()
    {
        return 'Авторизация пользователя в системе';
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return Yii::t('monitor/events',
            '{initiator} {user} success login using password, ip {ip}',
            [
                'user' => $this->getUserData()['userLink'],
                'initiator' => $this->_initiatorType
                        ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                        : '',
                'ip' => $this->getUserData()['userIp']
            ]
        );
    }
}