<?php

use yii\db\Migration;

class m180330_055238_add_columns_to_vtb_customer extends Migration
{
    private $tableName = '{{%vtb_customer}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'name',                           $this->string());
        $this->addColumn($this->tableName, 'type',                           $this->smallInteger());
        $this->addColumn($this->tableName, 'kpp',                            $this->string(9));
        $this->addColumn($this->tableName, 'countryCode',                    $this->smallInteger());
        $this->addColumn($this->tableName, 'addressState',                   $this->string());
        $this->addColumn($this->tableName, 'addressDistrict',                $this->string());
        $this->addColumn($this->tableName, 'addressSettlement',              $this->string());
        $this->addColumn($this->tableName, 'addressStreet',                  $this->string());
        $this->addColumn($this->tableName, 'addressBuilding',                $this->string(10));
        $this->addColumn($this->tableName, 'addressBuildingBlock',           $this->string(10));
        $this->addColumn($this->tableName, 'addressApartment',               $this->string(10));
        $this->addColumn($this->tableName, 'internationalName',              $this->string());
        $this->addColumn($this->tableName, 'internationalAddressState',      $this->string());
        $this->addColumn($this->tableName, 'internationalAddressSettlement', $this->string());
        $this->addColumn($this->tableName, 'internationalStreetAddress',     $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'name');
        $this->dropColumn($this->tableName, 'type');
        $this->dropColumn($this->tableName, 'kpp');
        $this->dropColumn($this->tableName, 'countryCode');
        $this->dropColumn($this->tableName, 'addressState');
        $this->dropColumn($this->tableName, 'addressDistrict');
        $this->dropColumn($this->tableName, 'addressSettlement');
        $this->dropColumn($this->tableName, 'addressStreet');
        $this->dropColumn($this->tableName, 'addressBuilding');
        $this->dropColumn($this->tableName, 'addressBuildingBlock');
        $this->dropColumn($this->tableName, 'addressApartment');
        $this->dropColumn($this->tableName, 'internationalName');
        $this->dropColumn($this->tableName, 'internationalAddressState');
        $this->dropColumn($this->tableName, 'internationalAddressSettlement');
        $this->dropColumn($this->tableName, 'internationalStreetAddress');
    }
}
