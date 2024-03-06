<?php

namespace common\models\vtbxml\documents\fields;

use DateTime;

class DateField extends Field
{
    public $type = 'DATE';

    /**
     * @param \DateTime $value
     * @return string
     */
    public function encodeForXml($value)
    {
        if (empty($value)) {
            return null;
        }

        return $value->format('d.m.Y');
    }

    public function decodeXmlValue($value)
    {
        if (empty($value)) {
            return null;
        }

        return DateTime::createFromFormat('d.m.Y H:i:s', "$value 00:00:00");
    }

    public function encodeForSignedData($value)
    {
        return $this->encodeForXml($value);
    }

}
