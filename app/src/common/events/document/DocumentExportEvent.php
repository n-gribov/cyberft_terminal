<?php

namespace common\events\document;

use common\modules\monitor\events\BaseEvent;
use Yii;
use common\helpers\Html;
use common\modules\certManager\models\Cert;
use common\modules\monitor\models\MonitorLogAR;

class DocumentExportEvent extends BaseEvent
{
    public $path;

    protected $_logLevel = \Psr\Log\LogLevel::INFO;
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;

    public function rules()
    {
        return [
            ['path','string']
        ];
    }

    public function getCodeLabel()
    {
        return Yii::t('monitor/events', 'Document export');
    }

    public function getLabel()
    {

        $documentLink = Html::a($this->_entityId, ['/document/view', 'id' => $this->_entityId]);

        return Yii::t('monitor/events',
            '{initiator} document ({id}) exported to {path}',
            [
                'id' => $documentLink,
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : '',
                'path' => $this->path
            ]
        );
    }
}