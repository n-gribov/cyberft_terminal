<?php
namespace common\helpers;

use Yii;

/**
 * Project issues helper
 *
 */
class Project
{
	const GITINFO = '@projectRoot/gitinfo.json';

	public static function gitInfo()
	{
		static $result = null;

		if (is_null($result)) {
			$result = [];
			if (file_exists(Yii::getAlias(static::GITINFO))) {
				$data = json_decode(file_get_contents(Yii::getAlias(static::GITINFO)), true);

				if (!empty($data['commit'])) {
					$result[] = "{$data['commit']}";
				}
                if (!empty($data['branch'])) {
                    $result[] = "&lt;{$data['branch']}&gt;";
                }

				if (!empty($data['dateUpdate'])) {
					$result[] = Yii::t('app', 'updated at {timestamp}', ['timestamp' => $data['dateUpdate']]);
				}

                $result = implode(' ', $result);
			}
		}
		
		return $result;
	}

	public static function applyConfig($params, $config)
	{
		/*if (isset($params['configurationСompleted']) && !$params['configurationСompleted']) {
			die(PHP_EOL . 'Запуск невозможен т.к. не проведена настройка.' . PHP_EOL . PHP_EOL);
		}*/

		$configs = json_encode($config, JSON_FORCE_OBJECT);

		foreach ($params['params'] as $param => $value) {
			$configs = str_replace('{$' . $param . '}', $value, $configs);
		}

		return json_decode($configs, JSON_FORCE_OBJECT);
	}

}
