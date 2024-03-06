<?php

use yii\db\Migration;

class m180731_090630_create_sbbol_customer extends Migration
{
    private $tableName = '{{%sbbol_customer}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'                   => $this->string(100)->notNull()->unique(),
            'shortName'            => $this->string(1000)->notNull(),
            'fullName'             => $this->string(1000)->notNull(),
            'internationalName'    => $this->string(1000),
            'propertyType'         => $this->string(300),
            'inn'                  => $this->string(32),
            'kpp'                  => $this->string(32),
            'ogrn'                 => $this->string(32),
            'dateOgrn'             => $this->string(10),
            'countryCode'          => $this->string(5),
            'addressState'         => $this->string(),
            'addressDistrict'      => $this->string(),
            'addressSettlement'    => $this->string(),
            'addressStreet'        => $this->string(),
            'addressBuilding'      => $this->string(10),
            'addressBuildingBlock' => $this->string(10),
            'addressApartment'     => $this->string(10),
            'terminalAddress'      => $this->string(32)->unique(),
            'isHoldingHead'        => $this->boolean(),
            'holdingHeadId'        => $this->string(100),
            'login'                => $this->string(200),
            'password'             => $this->string(1000),
            'senderName'           => $this->string(500),
            'certAuthId'           => $this->string(100),
            'lastCertNumber'       => $this->integer(),
            'bankBranchSystemName' => $this->string(100),
        ]);

        $this->createIndex('sbbol_customer_holdingHeadId', $this->tableName, 'holdingHeadId');
        $this->createIndex('sbbol_customer_terminalAddress', $this->tableName, 'terminalAddress', true);

        $this->addPrimaryKey('pk_sbbol_customer', $this->tableName, 'id');

        $this->addForeignKey(
            'fk_sbbol_customer_holdingHeadId',
            $this->tableName,
            'holdingHeadId',
            $this->tableName,
            'id'
        );
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
