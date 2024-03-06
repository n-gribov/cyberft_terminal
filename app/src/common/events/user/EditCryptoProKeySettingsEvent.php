<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;
use Yii;

class EditCryptoProKeySettingsEvent extends BaseEvent
{
    public $fingerprint;
    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function rules() {
        return [
            ['fingerprint', 'string']
        ];
    }

    public function getCodeLabel()
    {
        return 'Изменение настроек ключа КриптоПро';
    }

    public function getLabel()
    {
        return Yii::t('monitor/events',
            '{initiator} {user} edit CryptoPro key settings ({fingerprint})',
            [
                'user' => $this->getUserData()['userLink'],
                'fingerprint' => $this->fingerprint,
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}