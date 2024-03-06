<?php
namespace addons\edm\events;

use common\modules\monitor\models\MonitorLogAR;
use common\modules\monitor\events\BaseEvent;
use Yii;
use yii\helpers\Html;

class RegisterSigningRejectedEvent extends BaseEvent
{
    protected $_logLevel = \Psr\Log\LogLevel::WARNING;
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;
    
    public $reason = '';

    public function rules()
    {
        return [
            [['reason'], 'string'],
        ];
    }

    public function getLabel()
    {
        $user = $this->getUser();
        if (!empty($user)) {
            $userName = $user->screenName;
        } else {
            $userName = 'id ' . $this->userId;
        }

        return Yii::t('edm',
                    'User {user} rejected signing of registry ({registry})',
                    [
                        'user' => Html::a($userName, ['/user/view', 'id' => $this->userId]),
                        'registry' => Html::a($this->entityId, ['/edm/payment-register/view', 'id' => $this->entityId])
                    ]
            );
    }

    public function getCodeLabel()
    {
        return Yii::t('edm', 'Registry signing rejected');
    }
}