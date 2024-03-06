<?php
namespace common\components\xmlsec\xmlseclibs;

class XMLSeclibsHelper
{
	static function generateGUID($prefix = 'pfx')
	{
		$uuid = md5(uniqid(rand(), true));
		$guid = $prefix . substr($uuid, 0, 8) . '-' .
				substr($uuid, 8, 4) . '-' .
				substr($uuid, 12, 4) . '-' .
				substr($uuid, 16, 4) . '-' .
				substr($uuid, 20, 12);

		return $guid;
	}

	/**
	 * Функция формирует квалифицированное имя узла и возвращает его
	 * @param string $nsPrefix - префикс неймспейса имени узла
	 * @param string $nodeName - имя узла
	 * @return string - Полное квалифицированное имя узла
	 */
	public static function nsNode($nsPrefix, $nodeName)
	{
		return (empty($nsPrefix) ? '' : $nsPrefix . ':')
				. $nodeName;
	}

}
