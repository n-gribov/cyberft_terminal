<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use yii\helpers\Html;
use common\modules\monitor\models\MonitorLogAR;

class ISOReceiveStatusEvent extends BaseEvent
{
    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public $msgId;
    public $status;
    public $reason;

    public function getCodeLabel()
    {
        return Yii::t('monitor/events', 'ISOReceiveStatus');
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        // Ссылка на документ, для которого пришел статус в pain.002
        $documentLink = Html::a($this->msgId, ['/document/view', 'id' => $this->_entityId]);

        // Статус нового сообщения
        $status = $this->status;

        // Если есть описание ошибки статуса, добавляем его к статусу
        if ($this->reason) {
            $status .= '. Причина: ' . $this->reason;
        }

        return Yii::t('monitor/events',
            'Receive new pain.002 document: Change status {document} to {status}',
            [
                'document' => $documentLink,
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : '',
                'status' => $status
            ]
        );
    }
}