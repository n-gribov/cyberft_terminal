<?php

namespace common\models\vtbxml\documents\fields;

class IntegerField extends Field
{
    public $type = 'INTEGER';

    public function decodeXmlValue($value)
    {
        if ($value === null || $value === '') {
            return null;
        }
        return intval($value);
    }
}
