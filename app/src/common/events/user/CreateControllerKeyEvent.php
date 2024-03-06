<?php

namespace common\events\user;

use Yii;
use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;
use yii\helpers\Html;

class CreateControllerKeyEvent extends BaseEvent
{
    public $id;

    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function rules()
    {
        return [
            ['id','integer']
        ];
    }

    public function getCodeLabel()
    {
        return 'Создание ключа Контролера';
    }

    public function getLabel()
    {
        $controllerKeyLink = Html::a($this->id, ['/autobot/view', 'id' => $this->id]);

        return Yii::t('monitor/events',
            '{initiator} {user} create controller key ({id})',
            [
                'user' => $this->getUserData()['userLink'],
                'id' => $controllerKeyLink,
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}