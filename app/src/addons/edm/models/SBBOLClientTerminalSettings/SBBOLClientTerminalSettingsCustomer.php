<?php

namespace addons\edm\models\SBBOLClientTerminalSettings;

use yii\base\Model;

class SBBOLClientTerminalSettingsCustomer extends Model
{
    public $name;
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
    public $propertyType;
    public $internationalName;
    public $ogrn;
    public $dateOgrn;
    public $senderName;
}
