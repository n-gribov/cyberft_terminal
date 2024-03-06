<?php
namespace addons\edm\events;

use common\modules\monitor\models\MonitorLogAR;
use common\modules\monitor\events\BaseEvent;
use Yii;

class InvalidDocumentEvent extends BaseEvent
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

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return empty($this->docPath)
            ? Yii::t('edm', 'Invalid document')
            : Yii::t(
                'edm',
                'Invalid document found in {path}',
                ['path' => $this->docPath]
            );
    }

    public function getCodeLabel()
    {
        return Yii::t('edm', 'Invalid document');
    }
}