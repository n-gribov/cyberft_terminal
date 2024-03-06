<?php

namespace common\events\user;

use Yii;
use common\document\Document;
use common\helpers\Html;
use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;

class CreateDocumentEvent extends BaseEvent
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
        return 'Создание документа';
    }

    public function getLabel()
    {
        $href = '/document/view';

        if (in_array($this->docType, ['PaymentOrder', 'edmPaymentOrder'])) {
            $href = '/edm/payment-register/payment-order-view';
            $documentType = "edm - payment order";
        } elseif (in_array($this->docType, ['PaymentRegister', 'edmPaymentRegister'])) {
            $href = '/edm/payment-register/view';
            $documentType = "edm - payment register";
        } else {
            $document = Document::findOne($this->_entityId);

            if (!empty($document)) {
                $documentType = $document->typeGroup;
            } else {
                $documentType = "";
            }
        }

        $documentLink = Html::a($this->_entityId, [$href, 'id' => $this->_entityId]);

        return Yii::t('monitor/events',
            '{initiator} {user} create {documentType} document {document}',
            [
                'user' => $this->getUserData($this->_userId)['userLink'],
                'documentType' => $documentType,
                'document' => $documentLink,
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}