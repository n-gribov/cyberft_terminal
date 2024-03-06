<?php
namespace addons\swiftfin\events;

use common\models\User;
use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;
use Yii;
use yii\helpers\Html;

/**
 * Событие загрузки справочника SWIFT-кодов
 *
 * @author a.nikolaenko
 */
class DictionaryUploadedEvent extends BaseEvent
{
    protected $_logLevel = \Psr\Log\LogLevel::INFO;
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;
    
    const STATUS_PROCESSING = 'processing';
    const STATUS_PROCESSED = 'processed';
    const STATUS_ERROR = 'error';

    const TYPE_CBR = 'CBR';
    const TYPE_SWIFT = 'SWIFT';

    protected $_user;

    public $type = 0;
    public $fileName;
    public $fileSize = 0;
    public $recordCount = 0;
    public $changedCount = 0;
    public $status;

    public function rules()
    {
        return [
            [['type', 'fileName', 'status'], 'string'],
            [['fileSize', 'recordCount'], 'integer']
        ];
    }

    public static function getStatusLabels()
    {
        return [
            self::STATUS_PROCESSING => Yii::t('doc/swiftfin', 'Processing'),
            self::STATUS_PROCESSED => Yii::t('doc/swiftfin', 'Processed'),
            self::STATUS_ERROR => Yii::t('doc/swiftfin', 'Error')
        ];
    }

    public function getStatusLabel()
    {
        $labels = self::getStatusLabels();

        return array_key_exists($this->status, $labels)
            ? $labels[$this->status]
            : $this->status;
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        $user = $this->getUser();
        if (!empty($user)) {
            $userName = !empty($user->name) ? $user->name : $user->email;
        } else {
            $userName = 'id ' . $this->entityId;
        }

        $userLink = Html::a($userName, ['/user/view', 'id' => $this->entityId]);

        return Yii::t('doc/swiftfin',
                    'SWIFT Dictionary was uploaded by {user} with status {status}',
                    [
                        'user' => $userLink,
                        'status' => $this->getStatusLabel(),
                    ]
            );
    }

    public function getCodeLabel()
    {
        return Yii::t('doc/swiftfin', 'SWIFT Dictionary uploaded');
    }

    public function getUser()
    {
        if (is_null($this->_user)) {
            $this->_user = User::findOne($this->entityId);
        }

        return $this->_user;
    }
}