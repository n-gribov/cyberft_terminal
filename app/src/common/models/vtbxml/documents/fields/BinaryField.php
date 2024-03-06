<?php

namespace common\models\vtbxml\documents\fields;

abstract class BinaryField extends Field
{
    public function encodeForSignedData($value)
    {
        $bytes = unpack('C*', $value);
        $encodedValue = '';
        foreach ($bytes as $charCode) {
            $char = chr($charCode);
            if ($charCode <= 31 || $char === '#' || $char === ']') {
                $encodedValue .= sprintf('#%02X', $charCode);
            } else {
                $encodedValue .= $char;
            }
        }
        return "<![CDATA[$encodedValue]]>";
    }
}
