<?php

namespace common\events\user;

use addons\edm\models\PaymentOrder\PaymentOrderType;
use common\modules\monitor\events\BaseEvent;
use Yii;
use yii\helpers\Html;
use common\modules\monitor\models\MonitorLogAR;

class PrintDocumentEvent extends BaseEvent
{
    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public $documentType;

    public function rules() {
        return [
            ['documentType', 'string']
        ];
    }

    public function getCodeLabel()
    {
        return 'Печать документа';
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        $documentLink = Html::a($this->_entityId, ['/document/view', 'id' => $this->_entityId]);

        // Создание ссылки в зависимости от типа документа
        if ($documentLink && $this->documentType == PaymentOrderType::TYPE) {
            $documentLink = Html::a($this->_entityId, ['/edm/payment-register/payment-order-view', 'id' => $this->_entityId]);
        }

        return Yii::t('monitor/events',
            '{initiator} {user} print document {document}',
            [
                'user' => $this->getUserData($this->_userId)['userLink'],
                'document' => $documentLink,
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}