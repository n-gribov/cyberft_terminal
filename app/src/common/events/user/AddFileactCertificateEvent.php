<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use common\modules\monitor\models\MonitorLogAR;
use common\helpers\Html;

class AddFileactCertificateEvent extends BaseEvent
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
        return 'Изменение настроек Fileact';
    }

    public function getLabel()
    {
        $certLink = Html::a($this->fingerprint, ['/fileact/cryptopro-cert/view', 'id' => $this->id]);

        return Yii::t('monitor/events',
            '{initiator} {user} edit Fileact settings - add certificate ({fingerprint}) for verification incoming message from ({terminal})',
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