<?php

namespace addons\edm\events;

use common\modules\monitor\models\MonitorLogAR;
use common\helpers\Html;
use common\modules\monitor\events\BaseEvent;
use Psr\Log\LogLevel;
use Yii;
use yii\helpers\Url;

class PaymentStatusErrorEvent extends BaseEvent
{
    protected $_logLevel = LogLevel::ERROR;
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;

    public $status = null;
    public $statusDescription = null;
    public $documentId = null;
    public $registerId = null;

    public function rules()
    {
        return [
            [['status', 'statusDescription'], 'string'],
            [['documentId', 'registerId'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return Yii::t('edm',
            'Error status returned for payment register #{id}: {status} ({statusDescription})',
            [
                'status' => $this->status,
                'statusDescription' => $this->statusDescription,
                'id' => Html::a($this->registerId, Url::toRoute(['/edm/payment-register/view', 'id' => $this->registerId]))
            ]
        );
    }

    public function getCodeLabel()
    {
        return Yii::t('edm', 'Payment status error');
    }
}