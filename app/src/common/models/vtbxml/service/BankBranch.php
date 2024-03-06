<?php

namespace common\models\vtbxml\service;

use yii\base\BaseObject;

/**
 * @property integer $id
 * @property string  $bic
 * @property string  $name
 * @property string  $fullName
 * @property string  $internationalName
 */
class BankBranch extends BaseObject
{
    const TAGS = [
        'BRANCHID'  => 'id',
        'BIC'       => 'bic',
        'NAMESHORT' => 'name',
        'NAMEFULL'  => 'fullName',
        'NAMEINT'   => 'internationalName'
    ];

    public $id;
    public $bic;
    public $name;
    public $fullName;
    public $internationalName;

    public function appendToDom(\SimpleXMLElement $parentElement)
    {
        $customerElement = $parentElement->addChild('Branch');
        foreach (static::TAGS as $tag => $property) {
            $customerElement->$tag = $this->$property;
        }
    }

    public static function extractFromDom(\SimpleXMLElement $element)
    {
        $branch = new BankBranch();

        foreach ($element->children() as $childElement) {
            $tag = $childElement->getName();

            if (array_key_exists($tag, static::TAGS)) {
                $property = static::TAGS[$tag];
                $branch->$property = static::formatPropertyValue($property, (string)$childElement);
            }
        }

        return $branch;
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
        return in_array($name, ['id']);
    }
}
