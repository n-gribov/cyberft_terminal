<?php

namespace common\modules\transport\events;

use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Cftcp failed event class
 *
 * @author a.nikolaenko
 *
 * @package modules
 * @subpackage monitor
 *
 * @property string $code Error code
 */
class CftcpFailedEvent extends BaseEvent
{
    /**
     * @var string $code Error code
     */
    public $errorCode;
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['code'], 'string'],
        ]);
    }

    public function getCodeLabel()
    {
        return Yii::t('monitor/events', 'CFTCP connection failed');
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        $message = Yii::t('monitor/events', 'Connection error');
        return Yii::t('monitor/events', 'No access to processing ({message})', ['message' => $message]);
    }
}