<?php

namespace common\events\cert;

use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;
use common\document\Document;
use yii\helpers\Html;
use Yii;

/**
 * Invalid certificate event class
 *
 * @property string $fingerprint
 */
class CertificateNotFoundEvent extends BaseEvent
{
    public $fingerprint;

    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;

    public function rules()
    {
        return [
            [['fingerprint'], 'string'],
        ];
    }

    public function getLabel()
    {
        $document = Document::findOne($this->entityId);

        $terminal = \common\models\Terminal::findOne($document->terminalId);

        if (!$terminal) {
            $terminalId = 'UNKNOWN';
        } else {
            $terminalId = $terminal->terminalId;
        }

        $link = Html::a($document->id, ['/document/view', 'id' => $document->id]);

        return Yii::t(
            'monitor/events',
            'Terminal {terminalId}: {type} Document #{link} has unknown certificate "{fingerprint}"',
            [
                'terminalId'     => $terminalId,
                'type'           => strtoupper($document->typeGroup),
                'link'           => $link,
                'fingerprint'    => $this->fingerprint,
            ]
        );
    }

}