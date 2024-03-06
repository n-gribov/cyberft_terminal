<?php
namespace addons\ISO20022\events;

use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;
use Yii;

class FailedXsdValidationEvent extends BaseEvent
{
    protected $_logLevel = \Psr\Log\LogLevel::ERROR;
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;

    public $docPath = '';

    public function rules()
    {
        return [
            [['docPath'], 'string'],
        ];
    }

    public function getCodeLabel()
    {
        return Yii::t('app/iso20022', 'Invalid document');
    }

    public function getLabel()
    {
        return Yii::t('app/iso20022',
            'Invalid document: {docPath}. Validation against XSD failed. Document moved to error directory.',
            [
                'docPath' => $this->docPath,
            ]
        );
    }
}