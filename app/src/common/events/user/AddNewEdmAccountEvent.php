<?php

namespace common\events\user;

use addons\edm\models\EdmPayerAccount;
use common\modules\monitor\events\BaseEvent;
use Yii;
use common\helpers\Html;
use common\modules\monitor\models\MonitorLogAR;

class AddNewEdmAccountEvent extends BaseEvent
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
        $account = EdmPayerAccount::findOne($this->id);

        if (!empty($account)) {
            $accountLink = Html::a($account->number, ['/edm/edm-payer-account/view', 'id' => $account->id]);
        } else {
            $accountLink = $this->id;
        }

        return Yii::t('monitor/events',
            '{initiator} {user} edit edm settings - add new account ({id})',
            [
                'user' => $this->getUserData()['userLink'],
                'id' => $accountLink,
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}