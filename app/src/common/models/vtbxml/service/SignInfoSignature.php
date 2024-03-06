<?php

namespace common\models\vtbxml\service;

use yii\base\BaseObject;

/**
 * @property integer $number
 * @property string  $value
 * @property integer $cryptLibId
 * @property string  $uid
 */
class SignInfoSignature extends BaseObject
{
    const ATTRIBUTES = [
        'SignNum'    => 'number',
        'CryptLibID' => 'cryptLibId',
        'UID'        => 'uid',
    ];

    public $number;
    public $value;
    public $cryptLibId;
    public $uid;

    public function appendToDom(\SimpleXMLElement $parentElement)
    {
        $signatureElement = $parentElement->addChild('Sign', $this->value);
        foreach (static::ATTRIBUTES as $attribute => $property) {
            if ($this->$property !== null) {
                $signatureElement->addAttribute($attribute, $this->$property);
            }
        }
    }

    public static function extractFromDom(\SimpleXMLElement $element)
    {
        $signature = new SignInfoSignature();

        $signature->value = (string)$element;
        $attributes = $element->attributes();
        foreach (static::ATTRIBUTES as $attribute => $property) {
            if (isset($attributes->$attribute)) {
                $signature->$property = static::formatPropertyValue($property, (string)$attributes->$attribute);
            }
        }

        return $signature;
    }

    private static function formatPropertyValue($propertyName, $value)
    {
        if (static::isIntegerProperty($propertyName)) {
            if ($value === null || $value === '') {
                return null;
            }
            return intval($value);
        }
        return $value;
    }

    private static function isIntegerProperty($name)
    {
        return in_array($name, ['number', 'cryptLibId']);
    }
}
