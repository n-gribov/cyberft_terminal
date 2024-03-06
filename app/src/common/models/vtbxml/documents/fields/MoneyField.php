<?php

namespace common\models\vtbxml\documents\fields;

class MoneyField extends DoubleField
{
    public $type = 'MONEY';

    public function encodeForXml($value)
    {
        return $value === null ? null : sprintf('%0.2f', $value);
    }
}
