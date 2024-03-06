<?php

namespace common\modules\monitor\models;

use common\models\Terminal;
use common\models\User;
use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\MonitorModule;
use Psr\Log\LogLevel;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * MonitorLog entity logs entity events and provides viewable string
 *
 * @author a.nikolaenko
 *
 * @package modules
 * @subpackage monitor
 *
 * @property integer $id
 * @property string $dateCreated
 * @property integer $userId
 * @property integer $initiatorType
 * @property integer $componentId
 * @property integer $logLevel
 * @property string  $eventCode
 * @property string  $entity
 * @property integer $entityId
 * @property string  $params
 * @property string  $ip
 * @property integer $terminalId
 * @property-read User|null $user
 * @property-read Terminal|null $terminal
 */
class MonitorLogAR extends ActiveRecord
{
    const COMPONENT_SWIFTFIN  = 1;
    const COMPONENT_EDM       = 2;
    const COMPONENT_FILEACT   = 3;
    const COMPONENT_FINZIP    = 4;
    const COMPONENT_ISO20022  = 5;
    const COMPONENT_USER      = 6;
    const COMPONENT_TRANSPORT = 7;
    const COMPONENT_DOCUMENT  = 8;
    const COMPONENT_PARTICIPANT = 9;
    const COMPONENT_CERT      = 10;

    const LOG_LEVEL_EMERGENCY = 10;
    const LOG_LEVEL_ALERT     = 20;
    const LOG_LEVEL_CRITICAL  = 30;
    const LOG_LEVEL_ERROR     = 40;
    const LOG_LEVEL_WARNING   = 50;
    const LOG_LEVEL_NOTICE    = 60;
    const LOG_LEVEL_INFO      = 70;
    const LOG_LEVEL_DEBUG     = 80;

    const INITIATOR_TYPE_ADMIN = 1;
    const INITIATOR_TYPE_USER = 2;
    const INITIATOR_TYPE_LSO = 3;
    const INITIATOR_TYPE_RSO = 4;
    const INITIATOR_TYPE_SYSTEM = 5;
    const INITIATOR_TYPE_ADDITIONAL_ADMIN = 6;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'dateCreated',
                'updatedAtAttribute' => FALSE,
                'value' => function() {
                    return mktime();
                }
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['eventCode', 'required'],
            [['entityId', 'dateCreated', 'logLevel', 'userId', 'componentId'], 'integer'],
            [['entity', 'params', 'initiatorType', 'ip', 'terminalId'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'monitor_log';
    }

    public function beforeSave($insert)
    {
        $parts = static::parseEventCode($this->eventCode);
        $this->eventCode = $parts['eventCode'];
        $this->componentId = $parts['componentId'];

        return parent::beforeSave($insert);
    }

    public static function parseEventCode($eventCode)
    {
        $componentId = null;
        $parts = explode(':', $eventCode);
        if (count($parts) > 1) {
            $componentName = array_shift($parts);
            $eventCode     = implode(':', $parts);
            $codes         = array_flip(static::componentMap());
            if (array_key_exists($componentName, $codes)) {
                $componentId = $codes[$componentName];
            }
        }

        return ['componentId' => $componentId, 'eventCode' => $eventCode];
    }

    protected function mapLogLevel($level)
    {
        $map = array_flip(static::logLevelMap());
        if (array_key_exists($level, $map)) {
            return $map[$level];
        }

        return null;
    }

    public static function getLogLevelLabels()
    {
        return [
            static::LOG_LEVEL_EMERGENCY	 => Yii::t('monitor/events', LogLevel::EMERGENCY),
            static::LOG_LEVEL_ALERT      => Yii::t('monitor/events', LogLevel::ALERT),
            static::LOG_LEVEL_CRITICAL   => Yii::t('monitor/events', LogLevel::CRITICAL),
            static::LOG_LEVEL_ERROR      => Yii::t('monitor/events', LogLevel::ERROR),
            static::LOG_LEVEL_WARNING    => Yii::t('monitor/events', LogLevel::WARNING),
            static::LOG_LEVEL_INFO       => Yii::t('monitor/events', LogLevel::INFO),
            static::LOG_LEVEL_NOTICE     => Yii::t('monitor/events', LogLevel::NOTICE),
            static::LOG_LEVEL_DEBUG      => Yii::t('monitor/events', LogLevel::DEBUG),
        ];
    }

    public static function getEventCodeLabels(): array
    {
        /** @var MonitorModule $monitorModule */
        $monitorModule = Yii::$app->getModule('monitor');
        $labels = [];
        foreach ($monitorModule->findEventClasses() as $className) {
            /** @var BaseEvent $event */
            $event = new $className;
            $labels[$event->getCode()] = $event->getCodeLabel();
        }
        asort($labels);
        return $labels;
    }
    
    public static function searchEventCodeLabels()
    {
        $codes = static::find()->select(['componentId', 'eventCode'])->distinct()->all();

        $labels = [];
        
        foreach ($codes as $eventModel) {
            $event = BaseEvent::getEventObject($eventModel->getComponentName().':'.$eventModel->eventCode);
            $labels[$eventModel->eventCode] = $event->getCodeLabel();
        }
        
        return $labels;
    }

    public static function getInitiatorTypelLabels()
    {
        return [
            static::INITIATOR_TYPE_ADMIN	 => Yii::t('monitor/events', 'Administrator'),
            static::INITIATOR_TYPE_ADDITIONAL_ADMIN	 => Yii::t('monitor/events', 'Additional administrator'),
            static::INITIATOR_TYPE_USER      => Yii::t('monitor/events', 'User'),
            static::INITIATOR_TYPE_LSO   => Yii::t('monitor/events', 'Security officer (left)'),
            static::INITIATOR_TYPE_RSO   => Yii::t('monitor/events', 'Security officer (right)'),
            static::INITIATOR_TYPE_SYSTEM   => Yii::t('monitor/events', 'System'),
        ];
    }

    public static function getComponentLabels()
    {
        return static::componentMap();
    }

    public static function componentMap()
    {
        return [
            static::COMPONENT_SWIFTFIN => 'swiftfin',
            static::COMPONENT_EDM => 'edm',
            static::COMPONENT_FILEACT => 'fileact',
            static::COMPONENT_FINZIP => 'finzip',
            static::COMPONENT_ISO20022 => 'ISO20022',
            static::COMPONENT_USER => 'user',
            static::COMPONENT_TRANSPORT => 'transport',
            static::COMPONENT_DOCUMENT => 'document',
            static::COMPONENT_PARTICIPANT => 'participant',
            static::COMPONENT_CERT => 'cert',
        ];
    }

    public static function logLevelMap()
    {
        return [
            static::LOG_LEVEL_EMERGENCY => LogLevel::EMERGENCY,
            static::LOG_LEVEL_ALERT => LogLevel::ALERT,
            static::LOG_LEVEL_CRITICAL => LogLevel::CRITICAL,
            static::LOG_LEVEL_ERROR => LogLevel::ERROR,
            static::LOG_LEVEL_WARNING => LogLevel::WARNING,
            static::LOG_LEVEL_NOTICE => LogLevel::NOTICE,
            static::LOG_LEVEL_INFO => LogLevel::INFO,
            static::LOG_LEVEL_DEBUG => LogLevel::DEBUG,
        ];
    }

    public function getComponentName()
    {
        return static::componentMap()[$this->componentId];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dateCreated' => \Yii::t('monitor', 'Date'),
            'eventCode' => \Yii::t('monitor', 'Event code'),
            'eventCodeLabel' => \Yii::t('monitor', 'Event code'),
            'componentName' => \Yii::t('monitor', 'Component'),
            'componentId' => \Yii::t('monitor', 'Component'),
            'initiatorType' => \Yii::t('monitor', 'Initiator'),
            'logLevel' => \Yii::t('monitor', 'Log level'),
            'ip' => \Yii::t('monitor', 'IP'),
            'terminalId' => \Yii::t('monitor', 'Terminal')
        ];
    }

    public static function getErrorStatus()
    {
        return [
            static::LOG_LEVEL_ERROR,
            static::LOG_LEVEL_CRITICAL,
            static::LOG_LEVEL_EMERGENCY,
        ];
    }

    public static function getWarningStatus()
    {
        return [
            static::LOG_LEVEL_WARNING,
            static::LOG_LEVEL_ALERT,
        ];
    }

    /**
     * Load event
     *
     * @param BaseEvent $event
     */
    public function loadEvent(BaseEvent $event)
    {
        $this->setAttributes([
            'eventCode' => $event->code,
            'entity' => $event->entity,
            'entityId' => $event->entityId,
            'userId' => $event->userId,
            'initiatorType' => $event->initiatorType,
            'logLevel' => $this->mapLogLevel($event->logLevel),
            'ip' => $event->ip,
            'terminalId' => $event->terminalId,
            'params' => serialize($event->attributes)
        ]);
    }

    /**
     * Get event
     *
     * @return type
     */
    public function getEvent()
    {
        $map  = static::componentMap();
        $code = $this->eventCode;

        if (array_key_exists($this->componentId, $map)) {
            $code = $map[$this->componentId] . ':' . $code;
        }

        $params = unserialize($this->params);
        if (!is_array($params)) {
            $params = [];
        }

        $event = BaseEvent::getEventObject($code, $params);

        $event->userId = $this->userId;
        $event->logLevel = static::logLevelMap()[$this->logLevel];
        $event->entity = $this->entity;
        $event->entityId = $this->entityId;
        $event->componentId = $this->componentId;
        $event->initiatorType = $this->initiatorType;
        $event->ip = $this->id;
        $event->terminalId = $this->terminalId;
        
        return $event;
    }

    /**
     * Get event code label
     *
     * @return string
     */
    public function getEventCodeLabel()
    {
        return Yii::t('monitor/events', $this->eventCode);
    }

    public function getTerminal()
    {
        return $this->hasOne(Terminal::className(), ['id' => 'terminalId']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'userId']);
    }
}