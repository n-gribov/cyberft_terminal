<?php

namespace common\models\vtbxml\documents\fields;

class BooleanField extends Field
{
    public $type = 'BOOLEAN';

    public function encodeForXml($value)
    {
        if ($value === null || $value === '') {
            return null;
        }
        return $value == true ? 'true' : 'false';
    }

    public function decodeXmlValue($value)
    {
        switch ($value) {
            case 'true':
                return true;
            case 'false':
                return false;
            default:
                return null;
        }
    }

    public function encodeForSignedData($value)
    {
        return $this->encodeForXml($value);
    }
}
