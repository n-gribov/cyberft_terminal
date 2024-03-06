<?php

use yii\db\Migration;

class m180731_092223_create_sbbol_customerAccount extends Migration
{
    private $tableName = '{{%sbbol_customerAccount}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'           => $this->string(100)->notNull()->unique(),
            'number'       => $this->string(100)->notNull()->unique(),
            'bankBik'      => $this->string(20)->notNull(),
            'currencyCode' => $this->string(10)->notNull(),
            'customerId'   => $this->string(100)->notNull(),
        ]);

        $this->addPrimaryKey('pk_customerAccount', $this->tableName, 'id');

        $this->addForeignKey(
            'fk_sbbol_customerAccount_customerId',
            $this->tableName,
            'customerId',
            '{{%sbbol_customer}}',
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
