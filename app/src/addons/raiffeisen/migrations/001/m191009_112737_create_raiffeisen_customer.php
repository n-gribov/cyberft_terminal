<?php

use yii\db\Migration;

class m191009_112737_create_raiffeisen_customer extends Migration
{
    private $tableName = '{{%raiffeisen_customer}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'                     => $this->primaryKey(),
            'shortName'              => $this->string(1000)->notNull(),
            'fullName'               => $this->string(1000)->notNull(),
            'internationalName'      => $this->string(1000),
            'propertyType'           => $this->string(300),
            'inn'                    => $this->string(32),
            'kpp'                    => $this->string(32),
            'ogrn'                   => $this->string(32),
            'dateOgrn'               => $this->string(10),
            'countryCode'            => $this->string(5),
            'addressState'           => $this->string(),
            'addressDistrict'        => $this->string(),
            'addressSettlement'      => $this->string(),
            'addressStreet'          => $this->string(),
            'addressBuilding'        => $this->string(10),
            'addressBuildingBlock'   => $this->string(10),
            'addressApartment'       => $this->string(10),
            'terminalAddress'        => $this->string(32)->unique(),
            'isHoldingHead'          => $this->boolean(),
            'holdingHeadId'          => $this->integer(),
            'login'                  => $this->string(200),
            'password'               => $this->string(5000),
            'certificate'            => $this->string(5000),
            'privateKeyPassword'     => $this->string(5000)
        ]);

        $this->createIndex('raiffeisen_customer_holdingHeadId', $this->tableName, 'holdingHeadId');
        $this->createIndex('raiffeisen_customer_terminalAddress', $this->tableName, 'terminalAddress', true);

        $this->addForeignKey(
            'fk_raiffeisen_customer_holdingHeadId',
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
