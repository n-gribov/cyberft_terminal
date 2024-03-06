<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use yii\helpers\Html;
use common\modules\monitor\models\MonitorLogAR;

class SignDocumentEvent extends BaseEvent
{

    public $docType;

    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function rules()
    {
        return [
            ['docType', 'string']
        ];
    }

    public function getCodeLabel()
    {
        return 'Подписание документа';
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {

        $href = '/document/view';

        if ($this->docType) {

            if ($this->docType == 'edmPaymentOrder') {
                $href = '/edm/payment-register/payment-order-view';
            } elseif ($this->docType == 'edmPaymentRegister') {
                $href = '/edm/payment-register/view';
            }
        }

        $documentLink = Html::a($this->_entityId, [$href, 'id' => $this->_entityId]);

        return Yii::t('monitor/events',
            '{initiator} {user} sign document {id}',
            [
                'user' => $this->getUserData($this->_userId)['userLink'],
                'id' => $documentLink,
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}