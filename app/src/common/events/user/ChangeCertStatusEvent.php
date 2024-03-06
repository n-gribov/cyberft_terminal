<?php

namespace common\events\user;

use Yii;
use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;
use common\helpers\Html;

class ChangeCertStatusEvent extends BaseEvent
{
    public $id;
    public $certName;
    public $status;
    public $reason;

    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function rules()
    {
        return [
            ['id','integer'],
            [['reason', 'status', 'certName'], 'string'],
        ];
    }

    public function getCodeLabel()
    {
        return Yii::t('monitor/events', 'Change certificate status');
    }

    public function getLabel()
    {
        // Формируем ссылку на сертификат
        $certLink = Html::a($this->certName, ['/certManager/cert/view', 'id' => $this->id]);

        return Yii::t('monitor/events',
            '{initiator} {user} change certificate ({title}) status to {status}. Reason - {reason}',
            [
                'user' => $this->getUserData()['userLink'],
                'title' => $certLink,
                'status' => $this->status,
                'reason' => $this->reason,
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}