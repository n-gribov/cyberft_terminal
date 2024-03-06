<?php
namespace common\components\terminal;

use yii\base\Model;
use common\db\RedisConnection;
use Yii;

/**
 * @author fuzz
 */
class RedisModel extends Model
{

	/** @var RedisConnection */
	protected $redis;
	/**
	 * @var Terminal
	 */
	protected $_terminal;

	public $updatedAt;

	public function __construct($config = array())
	{
		$this->redis = Yii::$app->redis;

		parent::__construct($config);
	}

	public function setTerminal($value)
	{
		$this->_terminal = $value;
	}

	public function init()
	{
		$this->redis = Yii::$app->redis;
		$vars = $this->redis->hmget(static::KEY_VARS);

		$this->setAttributes($vars, false);
	}

	public function save($runValidation = true, $attributeNames = null)
	{
		if ($runValidation && !$this->validate($attributeNames)) {
			return false;
		}

		$this->updatedAt = gmdate('Y-m-d H:i:s');
		return $this->redis->hmset(static::KEY_VARS, $this->attributes);
	}
}
