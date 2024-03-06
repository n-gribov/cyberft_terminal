<?php

namespace common\models\vtbxml\documents\fields;

class DoubleField extends Field
{
    public $type = 'DOUBLE';

    public function decodeXmlValue($value)
    {
        if ($value === null || $value === '') {
            return null;
        }
        return floatval($value);
    }

    public function encodeForSignedData($value)
    {
        return sprintf('%0.4f', $value);
    }
}
