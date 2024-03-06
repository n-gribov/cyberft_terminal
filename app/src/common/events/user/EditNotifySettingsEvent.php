<?php

namespace common\events\user;

use Yii;
use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;

class EditNotifySettingsEvent extends BaseEvent
{
    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function getCodeLabel()
    {
        return 'Изменение параметров событий оповещения';
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return Yii::t('monitor/events',
            '{initiator} {user} edit notify settings',
            [
                'user' => $this->getUserData()['userLink'],
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}