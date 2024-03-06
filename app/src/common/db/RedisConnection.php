<?php
namespace common\db;

use yii\redis\Connection;

/**
 * Extended RedisConnection class
 *
 * @author fuzz
 */
class RedisConnection extends Connection
{
	/**
	 * HGET implementation
	 *
	 * @param type $hash
	 * @param type $key
	 * @return type
	 */
	public function hget($hash, $key)
	{
		return $this->executeCommand('HGET', [$hash, $key]);
	}

	/**
	 * Returns values by hash keys if given or all hash values
	 *
	 * @param type $hash
	 * @param type $keys
	 * @return array
	 */
	public function hmget($hash, $keys = [])
	{
		$result = [];
		$hashKeys = $this->executeCommand('HKEYS', [$hash]);

		if ($keys) {
			$hashKeys = array_intersect($hashKeys, $keys);
		}

		if ($hashKeys) {
			$values = $this->executeCommand('HMGET', array_merge([$hash], $hashKeys));
			$result = array_combine($hashKeys, $values);
		}

		return $result;
	}

	/**
	 * HSET implementation
	 *
	 * @param type $hash
	 * @param type $key
	 * @param type $value
	 */
	public function hset($hash, $key, $value)
	{

		$this->executeCommand('HSET', [$hash, $key, $value]);
	}

	/**
	 * HMSET implementation
	 * @param type $hash
	 * @param mixed $args key-value array with arguments
	 */
	public function hmset($hash, $args = [])
	{
		if (empty($args)) {
			return false;
		}


		$methodArgs = [$hash];
		foreach ($args as $key => $value) {
			array_push($methodArgs, $key);
			array_push($methodArgs, $value);
		}

		return $this->executeCommand('HMSET', $methodArgs);
	}
}
