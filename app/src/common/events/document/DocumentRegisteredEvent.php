<?php
namespace common\events\document;

use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;
use common\document\Document;
use Yii;
use yii\helpers\Html;

/**
 * Document registered event class
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 *
 * @package modules
 * @subpackage monitor
 *
 * @property string $documentType Document type
 */
class DocumentRegisteredEvent extends BaseEvent
{
    /**
     * @var string $documentType Document type
     */
    public $documentType;
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['documentType'], 'string'],
        ];
    }

    public function getCodeLabel()
    {
        return Yii::t('document', 'Document registered');
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {

        $link = Html::a($this->entityId, ['/document/view', 'id' => $this->entityId]);
        

        return Yii::t('monitor/events', 'Registered new {type} document #{link}',
                [
                'type' => strtoupper($this->documentType),
                'link' => $link,
                ]
            );
    }
}