<?php
namespace common\helpers;

use Yii;

/**
 * Класс-заглушка для формирования ключей хранения в Редисе с разделением по
 * приложениям, модулям и т.д.
 */
class RedisHelper
{
	/**
	 * Формирует ключ хранения для заданных идентификаторов
	 * @param string $index индекс типа как в Elastic
	 * @param type $id юник ид внутри индекса
	 * @return string
	 */
	public static function getKeyName($index, $id = false)
	{
		$key = Yii::$app->params['redis.key'] . ':' . $index;

		if ($id) {
			return $key . ':' . $id;
		} else {
			return $key;
		}
	}

}
