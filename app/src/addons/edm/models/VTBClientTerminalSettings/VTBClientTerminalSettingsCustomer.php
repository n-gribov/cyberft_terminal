<?php

namespace addons\edm\models\VTBClientTerminalSettings;

use yii\base\Model;

class VTBClientTerminalSettingsCustomer extends Model
{
    public $id;
    public $name;
    public $type;
    public $propertyType;
    public $kpp;
    public $inn;
    public $countryCode;
    public $addressState;
    public $addressDistrict;
    public $addressSettlement;
    public $addressStreet;
    public $addressBuilding;
    public $addressBuildingBlock;
    public $addressApartment;
    public $internationalName;
    public $internationalAddressState;
    public $internationalAddressSettlement;
    public $internationalStreetAddress;
}
