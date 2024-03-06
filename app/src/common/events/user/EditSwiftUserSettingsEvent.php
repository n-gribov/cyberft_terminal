<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use common\modules\monitor\models\MonitorLogAR;

class EditSwiftUserSettingsEvent extends BaseEvent
{
    public $id;
    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function rules() {
        return [
            ['id','integer']
        ];
    }

    public function getCodeLabel()
    {
        return 'Изменение настроек модуля SwiftFIN';
    }

    public function getLabel()
    {
        return Yii::t('monitor/events',
            '{initiator} {user} edit swiftfin settings - edit SWIFTFIN user roles {id}',
            [
                'user' => $this->getUserData()['userLink'],
                'id' => $this->getUserData($this->id)['userLink'],
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}