<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use addons\edm\models\DictContractor;
use common\helpers\Html;
use common\modules\monitor\models\MonitorLogAR;

class AddNewEdmContractorEvent extends BaseEvent
{
    public $id;
    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function rules() {
        return [
            ['id', 'integer']
        ];
    }

    public function getCodeLabel()
    {
        return 'Изменение настроек модуля EDM';
    }

    public function getLabel()
    {
        $contractor = DictContractor::findOne($this->id);

        if (!empty($contractor)) {
            $contractorLink = Html::a($contractor->id, ['/edm/dict-contractor/view', 'id' => $contractor->id]);
        } else {
            $contractorLink = $this->id;
        }

        return Yii::t('monitor/events',
            '{initiator} {user} edit edm settings - add new contractor ({id})',
            [
                'user' => $this->getUserData()['userLink'],
                'id' => $contractorLink,
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}


