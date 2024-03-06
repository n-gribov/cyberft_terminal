<?php
namespace common\events\document;

use common\helpers\Html;
use common\modules\monitor\models\MonitorLogAR;
use common\modules\monitor\events\BaseEvent;
use Yii;

/**
 * Document status change event class
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 *
 * @package modules
 * @subpackage monitor
 *
 * @property string $previousStatus Previous status
 * @preperty string $status         New status
 */
class DocumentStatusChangeEvent extends BaseEvent
{
    /**
     * @var string $previousStatus Previous status
     */
    public $previousStatus;

    /**
     * @var string $status New status
     */
    public $status;

    /**
     * @var string $info Info
     */
    protected $_info;
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;

    public function attributes()
    {
        $attributes = parent::attributes();
        $attributes[] = 'info';

        return $attributes;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['previousStatus', 'status', 'info'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        $documentLink = Html::a($this->_entityId, ['/document/view', 'id' => $this->_entityId]);

        return Yii::t('monitor/events',
            'Change status from [{previousStatus}] to [{status}] for document ({documentId}){info}',
            [
                'previousStatus' => $this->previousStatus,
                'status'         => $this->status,
                'documentId'     => $documentLink,
                'info'           => $this->info
                    ? ', ' . Yii::t('monitor/events', 'detailed info: {info}', ['info' => $this->info])
                    : ""
            ]
        );
    }

    public function getCodeLabel()
    {
        return Yii::t('document', 'Document status updated');
    }

    public function setInfo($value) {
        if (is_array($value)) {
            $this->_info = json_encode($value);
        } else {
            $this->_info = $value;
        }
    }

    public function getInfo() {
        return $this->_info;
    }
}