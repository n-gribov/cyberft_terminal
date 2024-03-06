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
class DocumentForSigningEvent extends BaseEvent
{
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        if (!$document = Document::findOne(['id' => $this->entityId])){
            return '';
        }

        $link = Html::a($document->id, ['/document/view', 'id' => $document->id]);

        return Yii::t('monitor/events', '{type}: Document #{link} signing required',
            [
                'type' => strtoupper($document->typeGroup),
                'link' => $link,
            ]);
    }

    public function getCodeLabel()
    {
        return Yii::t('monitor/events', 'DocumentForSigning');
    }
}
