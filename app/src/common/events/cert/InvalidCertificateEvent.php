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
class InvalidCertificateEvent extends BaseEvent
{
    public $certId;
    public $fingerprint;

    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;

    public function rules()
    {
        return [
            [['fingerprint' ], 'string'],
            ['certId', 'integer']
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
        $certLink = Html::a($this->fingerprint, ['/certManager/cert/view', 'id' => $this->certId]);

        return Yii::t(
            'monitor/events',
            'Terminal {terminalId}: {type} Document #{link} has inactive certificate "{certLink}"',
            [
                'terminalId'     => $terminalId,
                'type'           => strtoupper($document->typeGroup),
                'link'           => $link,
                'certLink'       => $certLink,
            ]
        );
    }

}