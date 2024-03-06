<?php

use yii\db\Schema;
use yii\db\Migration;

class m160202_175246_create_table_edm_foreignCurrencyOperation extends Migration
{
    private $tableName = "documentExtEdmForeignCurrencyOperation";

    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => Schema::TYPE_BIGPK . ' NOT NULL',
            'documentId' => Schema::TYPE_BIGINT . ' NOT NULL',
            'typeRequest' => Schema::TYPE_STRING . ' NOT NULL DEFAULT "purchase"',
            'body' => Schema::TYPE_TEXT . ' NOT NULL',
            'numberDocument' => Schema::TYPE_BIGINT . ' NOT NULL',
            'date' => Schema::TYPE_TIMESTAMP . ' NOT NULL',
            'payer' => Schema::TYPE_STRING . ' NOT NULL',
            'paymentAccount' => Schema::TYPE_STRING . ' NOT NULL',
            'currency' => Schema::TYPE_STRING . ' NOT NULL',
            'sum' => Schema::TYPE_DECIMAL . ' DEFAULT 0',
            'signaturesRequired' => Schema::TYPE_INTEGER . ' DEFAULT 0',
            'signaturesCount' => Schema::TYPE_INTEGER . ' DEFAULT 0',
            'status' => Schema::TYPE_STRING . ' NOT NULL',
            'dateCreate' => Schema::TYPE_TIMESTAMP . ' NOT NULL',
            'dateUpdate' => Schema::TYPE_TIMESTAMP . ' NOT NULL',
        ], 'ENGINE=InnoDB CHARSET=utf8');
        return true;
    }

    public function down()
    {
        $this->dropTable($this->tableName);
        return true;
    }
    
}
