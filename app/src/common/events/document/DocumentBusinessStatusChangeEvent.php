<?php

namespace common\events\document;

use addons\edm\models\PaymentRegister\PaymentRegisterType;
use common\helpers\Html;
use common\modules\monitor\models\MonitorLogAR;
use common\modules\monitor\events\BaseEvent;
use addons\edm\models\PaymentOrder\PaymentOrderType;
use Yii;

class DocumentBusinessStatusChangeEvent extends BaseEvent
{
    public $documentType;
    public $businessStatus;
    public $reportType;

    protected $_logLevel = \Psr\Log\LogLevel::INFO;
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;

    public function rules()
    {
        return [
            [
               ['businessStatus', 'documentType', 'reportType'], 'safe'
            ]
        ];
    }

    public function getCodeLabel()
    {
        return 'Изменение бизнес-статуса';
    }

    public function getLabel()
    {
        // Ссылка на документ
        if ($this->documentType == PaymentRegisterType::TYPE) {
            $documentLink = Html::a($this->_entityId, ['/edm/payment-register/view', 'id' => $this->_entityId]);
        } else if ($this->documentType == PaymentOrderType::TYPE) {
            $documentLink = Html::a($this->_entityId, ['/edm/payment-register/payment-order-view', 'id' => $this->_entityId]);
        } else { //if ($this->documentType == FinZipType::TYPE) {
            $documentLink = Html::a($this->_entityId, ['/document/view', 'id' => $this->_entityId]);
        }

        return Yii::t('monitor/events',
            'Document ({document}) - change business status to {businessStatus} from incoming {report}',
            [
                'document' => $documentLink,
                'businessStatus' => $this->businessStatus,
                'report' => $this->reportType
            ]
        );
    }

}