<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use yii\helpers\Html;
use common\modules\monitor\models\MonitorLogAR;

class EditDocumentEvent extends BaseEvent
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
        return 'Изменение документа';
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        $documentLink = Html::a($this->documentId, ['/document/view', 'id' => $this->documentId]);

        return Yii::t('monitor/events',
            '{initiator} {user} edit document ({document})',
            [
                'user' => $this->getUserData()['userLink'],
                'document' => $documentLink,
                'initiator' => $this->_initiatorType
                    ? MonitorLogAR::getInitiatorTypelLabels()[$this->_initiatorType]
                    : ''
            ]
        );
    }
}