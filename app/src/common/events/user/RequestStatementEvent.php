<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use yii\helpers\Html;
use common\modules\monitor\models\MonitorLogAR;

class RequestStatementEvent extends BaseEvent
{
    public $accountNumber;

    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function rules()
    {
        return [
            ['accountNumber','integer']
        ];
    }

    public function getCodeLabel()
    {
        return 'Запрос выписки';
    }

    public function getLabel()
    {
        return Yii::t('monitor/events',
            '{initiator} {user} create statement request on account {account}',
            [
                'user' => $this->getUserData()['userLink'],
                'account' => $this->accountNumber,
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}