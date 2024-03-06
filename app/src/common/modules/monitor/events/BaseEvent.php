<?php
namespace common\modules\monitor\events;

use common\base\Model;
use Psr\Log\LogLevel;
use ReflectionClass;
use Yii;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use common\models\User;
use yii\helpers\Html;

/**
 * Base event class
 *
 * @package modules
 * @subpackage monitor
 */
abstract class BaseEvent extends Model
{
    /**
     * @var string $_logLevel Log Level
     */
    protected $_logLevel = LogLevel::INFO;

    /**
     * @var integer $_userId User Id
     */
    protected $_userId;

    /**
     * @var integer $_componentId Component Id
     */
    protected $_componentId;
    
    /**
     * @var string $_code Code
     */
    protected $_code;

    /**
     * @var string $_entity Entity
     */
    protected $_entity;

    /**
     * @var integer $_entityId Entity ID
     */
    protected $_entityId;
    
    protected $_user;

    protected $_initiatorType;

    protected $_ip;

    protected $_terminalId;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->_code = Inflector::camelize(preg_replace('/Event$/', '',
                    StringHelper::basename($this->className())));

    }

//    public function attributes()
//    {
//        return \yii\helpers\ArrayHelper::merge(
//                    parent::attributes(),
//                    [
//                        'logLevel',
//                        'userId',
//                        'componentId',
//                        'entity',
//                        'entityId',
//                    ]
//                );
//    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [$this->attributes(), 'safe']
        ];
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->_code;
    }

    public function getEntity()
    {
        return $this->_entity;
    }

    public function getEntityId()
    {
        return $this->_entityId;
    }

    public function getUserId()
    {
        return $this->_userId;
    }

    public function getUser()
    {
        if (is_null($this->_user)) {
            $this->_user = \common\models\User::findOne($this->userId);
        }

        return $this->_user;
    }

    public function getComponentId()
    {
        return $this->_componentId;
    }

    public function getInitiatorType()
    {
        return $this->_initiatorType;
    }

    public function getIp()
    {
        return $this->_ip;
    }

    public function getTerminalId()
    {
        return $this->_terminalId;
    }

    public function setEntity($value)
    {
        $this->_entity = $value;
    }

    public function setEntityId($value)
    {
        $this->_entityId = $value;
    }

    public function setComponentId($value)
    {
        $this->_componentId = $value;
    }

    public function setUserId($value)
    {
        $this->_userId = $value;
    }

    public function setInitiatorType($value)
    {
        $this->_initiatorType = $value;
    }

    public function setIp($value)
    {
        $this->_ip = $value;
    }

    public function setTerminalId($value)
    {
        $this->_terminalId = $value;
    }

    /**
     * Get log level
     *
     * @param string $code Code
     */
    public function getLogLevel()
    {
        return $this->_logLevel;
    }

    public function getLogLevelLabel()
    {
        return Yii::t('monitor/events', $this->_logLevel);
    }

    /**
     * Set log level
     *
     * @param string $level log level (numeric/string)
     */
    public function setLogLevel($level)
    {
        if (is_numeric($level)) {
            $map = \common\modules\monitor\models\MonitorLogAR::logLevelMap();
            if (!isset($map[$level])) {
                throw new \UnexpectedValueException('Unknown log level');
            }

            $level = $map[$level];
        }

        $this->_logLevel = $level;
    }

    /**
     * Set code
     *
     * @param string $code Code
     */
    protected function setCode($code)
    {
        $this->_code = $code;
    }

    /**
     * Get labels
     *
     * @return string
     */
    public function getLabel()
    {
        return "{$this->code} {$this->entity}:{$this->entityId}";
    }

    public function getCodeLabel()
    {
        return $this->_code;
    }

    /**
     * Magic method __toString()
     *
     * @return string
     */
    public function __toString() {
        return $this->label;
    }

    /**
     * Get event object
     *
     * @param string $code Event code
     * @return \common\modules\monitor\events\eventClassName
     */
    public static function getEventObject($code, $params = null)
    {
        $code = explode(':', $code);
        $prefix = '';
        $componentNameSpace = null;

        if (2 == count($code)) {
            $prefix = $code[0];
            $module = Yii::$app->getModule($prefix);
            if ($module) {
                $reflection = new ReflectionClass($module);
                $componentNameSpace = $reflection->getNamespaceName() . '\events\\';
            }
            $className = $code[1];
        } else {
            $className = $code[0];
        }

        if (!$componentNameSpace) {
            $componentNameSpace = 'common\events\\';
            if ($prefix) {
                 $componentNameSpace .= $prefix . '\\';
            }
        }
        
        $className = Inflector::camelize($className);

        $componentNameSpace .= $className . 'Event';

        $event = new $componentNameSpace();
        $event->setAttributes($params, false);

        if ($prefix) {
            $event->setCode($prefix . ':' . $className);
        }

        return $event;
    }

    protected function getUserData($id = null)
    {
        $userName = $id;
        $userLink = $id;
        $userIp = null;

        if ($id) {
            $user = User::findOne($id);

            if ($user) {
                $userName = $user->name;
                $userLink = Html::a($userName, ['/user/view', 'id' => $id]);
                $userIp = $user->lastIp;
            }
        } else if ($this->entityId) {
            $user = User::findOne($this->entityId);
            $userName = !empty($user)? $user->name : $this->entityId;
            $userLink = Html::a($userName, ['/user/view', 'id' => $this->entityId]);
            $userIp = $user->lastIp;
        }

        return ['userName' => $userName, 'userLink' => $userLink, 'userIp' => $userIp];
    }
}