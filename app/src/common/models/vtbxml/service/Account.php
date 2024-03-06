<?php

namespace common\models\vtbxml\service;

use yii\base\BaseObject;

/**
 * @property string  $number
 * @property integer $customerId
 * @property string  $customerName
 * @property integer $branchId
 * @property string  $branchBic
 * @property string  $branchName
 * @property float   $rest
 */
class Account extends BaseObject
{
    const TAGS = [
        'Acc'          => 'number',
        'CustID'       => 'customerId',
        'CustomerName' => 'customerName',
        'BranchID'     => 'branchId',
        'BranchBIC'    => 'branchBic',
        'BranchName'   => 'branchName',
        'Rest'         => 'rest',
    ];

    public $number;
    public $customerId;
    public $customerName;
    public $branchId;
    public $branchBic;
    public $branchName;
    public $rest;

    public function appendToDom(\SimpleXMLElement $parentElement)
    {
        $accountElement = $parentElement->addChild('Account');
        foreach (static::TAGS as $tag => $property) {
            $accountElement->$tag = $this->$property;
        }
    }

    public static function extractFromDom(\SimpleXMLElement $element)
    {
        $account = new Account();

        foreach ($element->children() as $childElement) {
            $tag = $childElement->getName();

            if (array_key_exists($tag, static::TAGS)) {
                $property = static::TAGS[$tag];
                $account->$property = static::formatPropertyValue($property, (string)$childElement);
            }
        }

        return $account;
    }

    private static function formatPropertyValue($propertyName, $value)
    {
        if (static::isIntegerProperty($propertyName)) {
            if ($value === null || $value === '') {
                return null;
            }
            return intval($value);
        } else if (static::isFloatProperty($propertyName)) {
            if ($value === null || $value === '') {
                return null;
            }
            return floatval($value);
        }
        return $value;
    }

    private static function isIntegerProperty($name)
    {
        return in_array($name, ['customerId', 'branchId']);
    }

    private static function isFloatProperty($name)
    {
        return in_array($name, ['rest']);
    }
}
