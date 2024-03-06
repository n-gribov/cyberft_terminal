<?php
namespace addons\ISO20022\events;

use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;
use Yii;

class DuplicateDocumentEvent extends BaseEvent
{
    protected $_logLevel = \Psr\Log\LogLevel::ERROR;
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;

    public $docPath = '';
    public $msgId = '';

    public function rules()
    {
        return [
            [['docPath', 'msgId'], 'string'],
        ];
    }

    public function getLabel()
    {
        return Yii::t('app/iso20022',
                'Error importing document {docPath} with message id {msgId}: message id already exists',
                [
                    'docPath' => $this->docPath,
                    'msgId' => $this->msgId,
                ]
            );
    }

    public function getCodeLabel()
    {
         return Yii::t('app/iso20022', 'Duplicated document');
    }

}