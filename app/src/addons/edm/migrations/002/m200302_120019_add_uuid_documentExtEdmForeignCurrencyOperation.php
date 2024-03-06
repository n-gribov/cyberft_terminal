<?php

use yii\db\Migration;

class m200302_120019_add_uuid_documentExtEdmForeignCurrencyOperation extends Migration
{
    private $tableName = '{{%documentExtEdmForeignCurrencyOperation}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'uuid', $this->string());
        $this->createIndex('documentExtEdmForeignCurrencyOperation_uuid', $this->tableName, 'uuid');
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'uuid');
    }
}
