<?php

namespace common\events\user;

use common\modules\monitor\events\BaseEvent;
use Yii;
use yii\helpers\Html;
use common\modules\monitor\models\MonitorLogAR;

class VerifyDocumentSuccessEvent extends BaseEvent
{
    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public function getCodeLabel()
    {
        return 'Верификация документа';
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        $documentLink = Html::a($this->_entityId, ['/document/view', 'id' => $this->_entityId]);

        return Yii::t('monitor/events',
            '{initiator} {user} verify document ({document})',
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