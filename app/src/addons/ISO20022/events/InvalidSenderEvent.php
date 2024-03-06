<?php
namespace addons\ISO20022\events;

use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;
use Yii;

class InvalidSenderEvent extends BaseEvent
{
    protected $_logLevel = \Psr\Log\LogLevel::ERROR;
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;
    
    public $sender = '';
    public $docPath = '';

    public function rules()
    {
        return [
            [['sender', 'docPath'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return Yii::t('app/iso20022',
                    'Document {docPath} contains invalid sender: {sender}',
                    [
                        'docPath' => $this->docPath,
                        'sender' => $this->sender,
                    ]
            );
    }

    public function getCodeLabel()
    {
        return Yii::t('app/iso20022', 'Invalid sender');
    }
}