<?php
namespace common\events\document;

use common\modules\monitor\models\MonitorLogAR;
use common\modules\monitor\events\BaseEvent;
use common\document\Document;
use yii\helpers\Html;
use Yii;

/**
 * Document for signing event class
 *
 * @package modules
 * @subpackage monitor
 */
class DocumentContSignErrorEvent extends BaseEvent
{
    public $documentTypeGroup;
    protected $_logLevel = \Psr\Log\LogLevel::ERROR;
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;

    public function rules()
    {
        return [
            ['documentTypeGroup', 'safe']
        ];
    }

    public function getCodeLabel()
    {
        return Yii::t('monitor/events', 'DocumentContSignError');
    }

    public function getLabel()
    {
        $link = Html::a($this->entityId, ['/document/view', 'id' => $this->entityId]);

        return Yii::t('monitor/events', '{type}: Document #{link}: can\'t find active controller certificate',
            [
                'type' => strtoupper($this->documentTypeGroup),
                'link' => $link,
            ]);
    }
}