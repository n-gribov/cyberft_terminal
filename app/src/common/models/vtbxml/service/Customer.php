<?php

namespace common\models\vtbxml\service;

use yii\base\BaseObject;

/**
 * @property integer $id
 * @property integer $client
 * @property integer $type
 * @property string  $fullName
 * @property string  $internationalName
 * @property string  $shortName
 * @property integer $propertyType
 * @property string  $inn
 * @property string  $kpp
 * @property string  $okato
 * @property string  $okpo
 * @property string  $internationalAddress
 * @property string  $internationalPlace
 * @property string  $internationalState
 * @property string  $internationalZip
 * @property string  $lawAddress
 * @property string  $lawCountry
 * @property string  $lawPlace
 * @property integer $lawPlaceType
 * @property string  $lawState
 * @property string  $lawZip
 * @property string  $lawDistrict
 * @property string  $lawCity
 * @property string  $lawStreet
 * @property string  $lawBuilding
 * @property string  $lawBlock
 * @property string  $lawOffice
 */
class Customer extends BaseObject
{
    const TAGS = [
        'CUSTID'           => 'id',
        'CLIENT'           => 'client',
        'CUSTOMERTYPE'     => 'type',
        'FISCALREASONCODE' => 'kpp',
        'INN'              => 'inn',
        'INTADDRESS'       => 'internationalAddress',
        'INTPLACE'         => 'internationalPlace',
        'INTSTATE'         => 'internationalState',
        'INTZIP'           => 'internationalZip',
        'LAWADDRESS'       => 'lawAddress',
        'LAWCOUNTRY'       => 'lawCountry',
        'LAWPLACE'         => 'lawPlace',
        'LAWPLACETYPE'     => 'lawPlaceType',
        'LAWSTATE'         => 'lawState',
        'LAWZIP'           => 'lawZip',
        'NAMEFULL'         => 'fullName',
        'NAMEINT'          => 'internationalName',
        'NAMESHORT'        => 'shortName',
        'OKATOCODE'        => 'okato',
        'OKPOCODE'         => 'okpo',
        'PROPERTYTYPE'     => 'propertyType',
        'LAWDISTRICT'      => 'lawDistrict',
        'LAWCITY'          => 'lawCity',
        'LAWSTREET'        => 'lawStreet',
        'LAWBUILDING'      => 'lawBuilding',
        'LAWBLOCK'         => 'lawBlock',
        'LAWOFFICE'        => 'lawOffice',
    ];

    public $id;
    public $client;
    public $type;
    public $fullName;
    public $internationalName;
    public $shortName;
    public $propertyType;
    public $inn;
    public $kpp;
    public $okato;
    public $okpo;
    public $internationalAddress;
    public $internationalPlace;
    public $internationalState;
    public $internationalZip;
    public $lawAddress;
    public $lawCountry;
    public $lawPlace;
    public $lawPlaceType;
    public $lawState;
    public $lawZip;
    public $lawDistrict;
    public $lawCity;
    public $lawStreet;
    public $lawBuilding;
    public $lawBlock;
    public $lawOffice;

    /** @var BankBranch[] */
    public $branches = [];

    public function getInternationalStreetAddress()
    {
        $streetAddress = $this->internationalAddress;
        $partsToCut = [$this->internationalPlace, $this->internationalZip, $this->lawState];
        foreach ($partsToCut as $partToCut) {
            if (empty($partToCut)) {
                continue;
            }
            $streetAddress = str_replace($partToCut, '', $streetAddress);
        }
        return preg_replace('/^\W+/', '', $streetAddress);
    }

    public function appendToDom(\SimpleXMLElement $parentElement)
    {
        $customerElement = $parentElement->addChild('Customer');
        foreach (static::TAGS as $tag => $property) {
            $customerElement->$tag = $this->$property;
        }

        $branchesElement = $customerElement->addChild('Branches');
        foreach ($this->branches as $branch) {
            $branch->appendToDom($branchesElement);
        }
    }

    public static function extractFromDom(\SimpleXMLElement $element)
    {
        $customer = new Customer();

        foreach ($element->children() as $childElement) {
            $tag = $childElement->getName();

            if (array_key_exists($tag, static::TAGS)) {
                $property = static::TAGS[$tag];
                $customer->$property = static::formatPropertyValue($property, (string)$childElement);
            } else if ($tag === 'Branches') {
                foreach ($childElement->Branch as $branchElement) {
                    $customer->branches[] = BankBranch::extractFromDom($branchElement);
                }
            }
        }

        return $customer;
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
        return in_array($name, ['id', 'client', 'type', 'propertyType', 'lawPlaceType']);
    }
}
