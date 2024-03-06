<?php
namespace common\modules\participant\events;

use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;
use Yii;

class ParticipantUpdatedEvent extends BaseEvent
{
    protected $_logLevel = \Psr\Log\LogLevel::INFO;

    public $requestType = 0;
    public $count = 0;
    public $startDate;
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;

    public function rules()
    {
        return [
            ['count', 'integer'],
            ['requestType', 'string'],
            ['startDate', 'integer'],
        ];
    }

    public function getLabel()
    {
        return Yii::t('app/participant',
                    'CyberFT members list was updated since {startDate}, updated count: {count}',
                    [
                        'requestType' => $this->requestType,
                        'count' => $this->count,
                        'startDate' => date('Y-m-d H:i', $this->startDate),
                    ]
            );
    }

    public function getCodeLabel()
    {
        return Yii::t('app/participant', Yii::t('app/participant', 'Member directory updated'));
    }

}