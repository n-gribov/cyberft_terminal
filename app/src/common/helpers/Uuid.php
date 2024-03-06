<?php
namespace common\helpers;

use Ramsey\Uuid\Uuid as RamseyUuid;

/**
 * Реализация API внешнего компонента генерации UUID
 *
 * @author fuzz
 */
class Uuid {

	const UUID_SYSTEM_COMMAND = 'uuid';

	public static function generate($uppercase = true, $version = 1)
	{
        $method = 'uuid' . $version;
        $uuid = RamseyUuid::$method();

		return $uppercase ? strtoupper($uuid) : $uuid;
	}

    /**
     * generate uuid1 converted to sequential binary
     * (use for binary(16) primary key in mysql tables)
     *
     * @param type $uppercase
     * @return string binary in hex format
     */
    public static function generateSequentialBinary($uppercase = true)
    {
        $uuid = static::convertToSequentialBinary(RamseyUuid::uuid1());

		return $uppercase ? strtoupper($uuid) : $uuid;
    }

    /**
     * convert uuid1 to sequential binary
     * @param string $uuid1
     * @return string binary in hex format
     */
    public static function convertToSequentialBinary($uuid1)
    {
        return substr($uuid1, 14, 4) . substr($uuid1, 9, 4)
                . substr($uuid1, 0, 8) . substr($uuid1, 19, 4) . substr($uuid1, 24);
    }

}
