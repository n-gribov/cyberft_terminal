<?php
/**
 * Created by PhpStorm.
 * User: vk
 * Date: 02.02.15
 * Time: 19:32
 */

namespace common\helpers;


use common\modules\certManager\components\ssl\Exception;

abstract class Directory {

	protected static $log = [];

	public static function getLog(){
		return self::$log;
	}

	public static function lastLog() {
		return self::$log[(count(self::$log)-1)];
	}

	protected static function log($message){
		self::$log[] = $message;
	}

	protected function purgeLog() {
		self::$log = [];
	}

	/**
	 * Очистка директории, без рекурсивного режима, директории очищены не будут
	 * @param string $path
	 * @param bool $recursive
	 * @return bool
	 * @throws \Exception
	 */
	public static function clean($path,$recursive=false) {
		self::purgeLog();

		if(!is_dir($path)) {
			throw new \Exception($path.' not found.');
		}
		if(!is_writable($path)) {
			throw new \Exception($path.' not writable.');
		}

		$list = array_reverse(self::read($path,$recursive));
		$c = count($list);
		for($i=0;$i<$c;$i++) {
			if(
				is_dir($list[$i]) && rmdir($list[$i])
				|| unlink($list[$i])
			) {
				self::log('Success: '.$list[$i].' was removed');
			} elseif(!is_writable($list[$i])) {
				self::log('Error: '.$list[$i].' is not writable');
				return false;
			} else {
				self::log('Error: '.$list[$i].' cat\'t delete');
				return false;
			}
		}

		return true;
	}


	/**
	 * @param string $path
	 * @param bool $recursive
	 * @return array
	 * @throws \Exception
	 */
	public static function read($path, $recursive = false) {
		if (!is_dir($path)) {
			throw new \Exception($path . ' not found.');
		}
		if (!is_readable($path)) {
			throw new \Exception($path . ' not readable.');
		}

		$result = [];
		$list   = scandir($path);
		$c      = count($list);

		for ($i = 0; $i < $c; $i++) {
			// такой фильтр нужен, т.к. данные директории не всегда стоят в начале списка, возможны варианты
			if ($list[$i] == '.' || $list[$i] == '..') {
				continue;
			}
			$t        = $path . '/' . $list[$i];
			$result[] = $t;
			if (true === $recursive && is_dir($t)) {
				$result = array_merge($result, self::read($t, true));
			}
		}
		return $result;
	}
}