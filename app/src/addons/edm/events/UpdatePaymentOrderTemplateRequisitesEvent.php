<?php
namespace addons\edm\events;

use common\modules\monitor\models\MonitorLogAR;
use common\modules\monitor\events\BaseEvent;
use Yii;
use yii\helpers\Html;

class UpdatePaymentOrderTemplateRequisitesEvent extends BaseEvent
{
    protected $_logLevel = \Psr\Log\LogLevel::INFO;
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_USER;
    
    public $templateId;
    public $templateName;

    public function rules()
    {
        return [
            [['templateId'], 'integer'],
            [['templateName'], 'string'],
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

        return Yii::t(
            'edm',
            'User {user} has updated payer organization requisites for payment order template "{templateName}" (ID: {templateId})',
            [
                'user'         => Html::a($userName, ['/user/view', 'id' => $this->userId]),
                'templateName' => $this->templateName,
                'templateId'   => $this->templateId,
            ]
        );
    }

    public function getCodeLabel()
    {
        return Yii::t('edm', 'Payment order template organization requisites update');
    }
}