<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use yii\helpers\Html;
use common\modules\monitor\models\MonitorLogAR;

class RejectSigningDocumentEvent extends BaseEvent
{
    public $documentId;

    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function rules()
    {
        return [
            ['documentId','integer']
        ];
    }

    public function getCodeLabel()
    {
        return 'Отказ подписания документа';
    }

    public function getLabel()
    {
        $documentLink = Html::a($this->documentId, ['/document/view', 'id' => $this->documentId]);

        return Yii::t('monitor/events',
            '{initiator} {user} rejected signing document {id}',
            [
                'user' => $this->getUserData()['userLink'],
                'id' => $documentLink,
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}