<?php

namespace common\events\document;

use common\modules\monitor\events\BaseEvent;
use Yii;
use yii\helpers\Html;
use common\modules\monitor\models\MonitorLogAR;

class DublicateIncomingDocumentEvent extends BaseEvent
{
    protected $_logLevel = \Psr\Log\LogLevel::ERROR;
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;
    public $type;
    public $uuid;

    public function rules()
    {
        return [
            [['type', 'uuid'], 'safe']
        ];
    }

    public function getCodeLabel()
    {
        return Yii::t('monitor/events', 'DocumentDublicateError');
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {

        $documentLink = Html::a($this->_entityId, ['/document/view', 'id' => $this->_entityId]);

        return Yii::t('monitor/events',
            'Document processing failed ({type}, {uuid}). Dublicate document ({document})',
            [
                'document' => $documentLink,
                'type' => $this->type,
                'uuid' => $this->uuid
            ]
        );
    }
}

