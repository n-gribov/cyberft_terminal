<?php

use yii\db\Migration;

class m190528_134014_create_sbbol2_customer extends Migration
{
    private $tableName = '{{%sbbol2_customer}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'                    => $this->primaryKey(),
            'shortName'             => $this->string(1000)->notNull(),
            'fullName'              => $this->string(1000)->notNull(),
            'inn'                   => $this->string(32)->notNull()->unique(),
            'kpp'                   => $this->string(32),
            'ogrn'                  => $this->string(32),
            'okato'                 => $this->string(32),
            'okpo'                  => $this->string(32),
            'orgForm'               => $this->string(32),
            'addressArea'           => $this->string(),
            'addressBuilding'       => $this->string(10),
            'addressCity'           => $this->string(),
            'addressCountryCode'    => $this->string(10),
            'addressFlat'           => $this->string(10),
            'addressHouse'          => $this->string(10),
            'addressRegion'         => $this->string(),
            'addressSettlement'     => $this->string(),
            'addressSettlementType' => $this->string(),
            'addressStreet'         => $this->string(),
            'addressZip'            => $this->string(10),
            'terminalAddress'       => $this->string(32)->unique(),
            'createDate'            => $this->timestamp()->defaultValue(null),
            'updateDate'            => $this->timestamp()->defaultValue(null),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
