<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use common\modules\monitor\models\MonitorLogAR;
use common\helpers\Html;

class AddIsoCertificateEvent extends BaseEvent
{
    public $fingerprint;
    public $senderTerminal;
    public $id;
    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function rules()
    {
        return [
            [['fingerprint', 'senderTerminal'], 'string'],
            ['id', 'integer']
        ];
    }

    public function getCodeLabel()
    {
        return 'ISO20022 - добавлен сертификат для верификации входящих сообщений';
    }

    public function getLabel()
    {
        $certLink = Html::a($this->fingerprint, ['/ISO20022/cryptopro-cert/view', 'id' => $this->id]);

        return Yii::t('monitor/events',
            '{initiator} {user} edit ISO20022 settings - add certificate ({fingerprint}) for verification incoming message from ({terminal})',
            [
                'user' => $this->getUserData()['userLink'],
                'fingerprint' => $certLink,
                'terminal' => $this->senderTerminal,
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}