<?php

namespace common\models\vtbxml;

use yii\base\BaseObject;

abstract class VTBXmlDocument extends BaseObject
{
    public abstract function toXml($version);

    public static function fromXml($xml)
    {
        throw new \Exception('VTBXmlDocument::fromXml must be overridden in subclass');
    }

    protected static function replaceEncoding($xml, $encoding)
    {
        return preg_replace('/(<\?xml\s+version="1.0"\s+encoding=")[A-Za-z0-9\-]+("\s*\?>)/', "$1$encoding$2", $xml);
    }
}
