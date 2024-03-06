<?php

namespace common\modules\transport\events;

use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;
use Yii;

/**
 * sFTP open failed event class
 *
 * @package modules
 * @subpackage monitor
 *
 * @propert string $serviceId Service ID
 * @propert string $path Path
 */
class SftpOpenFailedEvent extends BaseEvent
{
    /**
     * @var string $serviceId Service ID
     */
    public $serviceId;
    protected $_initiatorType = MonitorLogAR::INITIATOR_TYPE_SYSTEM;

    /**
     * @var string $path Path
     */
    public $path;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['serviceId', 'path'], 'string'],
        ];
    }

    public function getCodeLabel()
    {
        return Yii::t('monitor/events', 'SFTP connection failed');
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return Yii::t('monitor/events',
                '{service}: Failed to open resource "{path}"',
                [
                'service' => strtoupper($this->serviceId),
                'path'    => $this->path,
        ]);
    }
}