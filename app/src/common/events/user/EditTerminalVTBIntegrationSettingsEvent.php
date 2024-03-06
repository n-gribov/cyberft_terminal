<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use common\models\Terminal;
use common\helpers\Html;
use common\modules\monitor\models\MonitorLogAR;

class EditTerminalVTBIntegrationSettingsEvent extends BaseEvent
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
        return 'Изменение настроек интеграции с ВТБ для терминала';
    }

    public function getLabel()
    {
        $terminal = Terminal::findOne($this->id);
        $terminalLink = Html::a($terminal->terminalId, ['/terminal/view', 'id' => $terminal->id]);

        return Yii::t('monitor/events',
            '{initiator} {user} edit terminal {terminal} VTB integration settings',
            [
                'user' => $this->getUserData()['userLink'],
                'terminal' => $terminalLink,
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}