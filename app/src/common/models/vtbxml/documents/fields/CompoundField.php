<?php

namespace common\models\vtbxml\documents\fields;

class CompoundField extends BinaryField
{
    public $type = 'COMPOUND';

    public function __construct($properties)
    {
        parent::__construct($properties);
    }

//    public function encodeForXml($value)
//    {
//        return preg_replace('/\r\n|\r|\n/', "\r\n", $value);
//    }

//    public function encodeForSignedData($value)
//    {
//        $valueWithReplacedNewLines = preg_replace('/\r\n|\r|\n/', "\r\n", $value);
//        $valueIn1251 = iconv('UTF-8', 'windows-1251', $valueWithReplacedNewLines);
//
//        return parent::encodeForSignedData($valueIn1251);
//    }

//    public function isNullSignedValue($value)
//    {
//        return false;
//    }

}
