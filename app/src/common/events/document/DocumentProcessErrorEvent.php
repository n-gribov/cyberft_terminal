<?php

namespace common\events\document;

use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;
use common\document\Document;
use yii\helpers\Html;
use Yii;

/**
 * Document process error event class
 *
 * @property string $previousStatus Previous status
 * @property string $status         New status
 */
class DocumentProcessErrorEvent extends BaseEvent
{
    public $previousStatus;
    public $status;
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;

    public function rules()
    {
        return [
            [['previousStatus', 'status'], 'string'],
        ];
    }

    public function getLabel()
    {
        $document = Document::findOne(['id' => $this->entityId]);

        $terminal = \common\models\Terminal::findOne($document->terminalId);

        if (!$terminal) {
            $terminalId = 'UNKNOWN';
        } else {
            $terminalId = $terminal->terminalId;
        }

        $link = Html::a($document->id, ['/document/view', 'id' => $document->id]);

        return Yii::t(
            'monitor/events',
            'Terminal {terminalId}: {type} Document #{link} got status "{status}" after "{previousStatus}"',
            [
                'terminalId'     => $terminalId,
                'type'           => strtoupper($document->typeGroup),
                'link'           => $link,
                'status'         => $this->status,
                'previousStatus' => $this->previousStatus,
            ]
        );
    }

    public function getCodeLabel()
    {
        return Yii::t('monitor/events', 'DocumentProcessError');
    }
}