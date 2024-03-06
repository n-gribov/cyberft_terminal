<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use common\modules\monitor\models\MonitorLogAR;
use common\helpers\Html;

class AddVtbCertificateEvent extends BaseEvent
{
    public $fingerprint;
    public $terminal;
    public $id;
    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function rules()
    {
        return [
            [['fingerprint', 'terminal'], 'string'],
            ['id', 'integer']
        ];
    }

    public function getCodeLabel()
    {
        return 'Изменение настроек ВТБ';
    }

    public function getLabel()
    {
        $certLink = Html::a($this->fingerprint, ['/VTB/cryptopro-cert/view', 'id' => $this->id]);

        return Yii::t('monitor/events',
            '{initiator} {user} edit VTB settings - add certificate ({fingerprint}) for verification incoming message to ({terminal})',
            [
                'user' => $this->getUserData()['userLink'],
                'fingerprint' => $certLink,
                'terminal' => $this->terminal,
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}