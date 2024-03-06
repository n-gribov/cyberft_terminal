<?php

namespace common\modules\participant\events;

use common\models\User;
use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;
use Psr\Log\LogLevel;
use Yii;
use yii\helpers\Html;

class UpdateRequestSentEvent extends BaseEvent
{
    protected $_logLevel = LogLevel::INFO;
    public $requestType = 0;
    public $startDate;
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;

    public function rules()
    {
        return [
            ['requestType', 'string'],
            ['startDate', 'integer'],
        ];
    }

    public function getLabel()
    {
        $user = $this->getUser();
        if (!empty($user)) {
            $userName = $user->screenName;
        } else {
            $userName = 'id '.$this->entityId;
        }

        $userLink = Html::a($userName, ['/user/view', 'id' => $this->entityId]);

        return Yii::t('app/participant',
                        'CyberFT participant directory update request was sent by user {user}, start date: {startDate}',
                        [
                            'user' => $userLink,
                            'startDate' => date('Y-m-d', $this->startDate)
                        ]
        );
    }

    public function getCodeLabel()
    {
        return Yii::t('app/participant', Yii::t('app/participant', 'Member directory request sent'));
    }
}