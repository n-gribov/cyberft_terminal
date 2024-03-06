<?php

use yii\db\Migration;

class m191010_100251_create_raiffeisen_customerAccount extends Migration
{
    private $tableName = '{{%raiffeisen_customerAccount}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'           => $this->primaryKey(),
            'number'       => $this->string(100)->notNull()->unique(),
            'bankBik'      => $this->string(20)->notNull(),
            'currencyCode' => $this->string(10)->notNull(),
            'customerId'   => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_raiffeisen_customerAccount_customerId',
            $this->tableName,
            'customerId',
            '{{%raiffeisen_customer}}',
            'id',
            'cascade',
            'cascade'
        );
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
