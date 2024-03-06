<?php

namespace common\models\vtbxml\documents\fields;

use DateTime;

class DateTimeField extends Field
{
    const DATE_TIME_FORMAT = 'd.m.Y:H:i:s';

    public $type = 'DATETIME';

    /**
     * @param \DateTime $value
     * @return string
     */
    public function encodeForXml($value)
    {
        if (empty($value)) {
            return null;
        }
        return $value->format(static::DATE_TIME_FORMAT);
    }

    public function decodeXmlValue($value)
    {
        if (empty($value)) {
            return null;
        }
        return DateTime::createFromFormat(static::DATE_TIME_FORMAT, $value);
    }

    /**
     * @param \DateTime $value
     * @return string
     */
    public function encodeForSignedData($value)
    {
        return $value->format(static::DATE_TIME_FORMAT) . '.0000';
    }
}
