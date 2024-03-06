<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use yii\helpers\Html;
use common\modules\monitor\models\MonitorLogAR;

class SecureOfficerUserApproveEvent extends BaseEvent
{
    public $approveUserId;

    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function rules()
    {
        return [
            ['approveUserId', 'integer']
        ];
    }

    public function getCodeLabel()
    {
        return 'Подтверждение настроек пользователя';
    }

    public function getLabel()
    {

        return Yii::t('monitor/events',
            '{initiator} {user} approve user settings {id}',
            [
                'user' => $this->getUserData()['userLink'],
                'id' => $this->getUserData($this->approveUserId)['userLink'],
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}