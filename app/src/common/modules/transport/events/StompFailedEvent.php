<?php

namespace common\modules\transport\events;

use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;
use Yii;
use yii\helpers\ArrayHelper;


/**
 * Stomp failed event class
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 *
 * @package modules
 * @subpackage monitor
 *
 * @property string $code Error code
 */
class StompFailedEvent extends BaseEvent
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
        return Yii::t('monitor/events', 'STOMP connection failed');
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        $message = Yii::t('monitor/events', 'Unknown error');
        switch ($this->errorCode){
            case 'badConfig':
                $message = Yii::t('monitor/events', 'Wrong STOMP config params');
                break;
            case 'badConnection':
                $message = Yii::t('monitor/events', 'Connection error');
                break;
            default:
                $message = $this->errorCode;
                break;
        }

        return Yii::t('monitor/events', 'No access to processing ({message})', ['message' => $message]);
    }
}