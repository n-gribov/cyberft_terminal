<?php

use yii\db\Migration;

class m190529_110821_create_sbbol2_customerAccount extends Migration
{
    private $tableName = '{{%sbbol2_customerAccount}}';
    
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => $this->primaryKey(),
                'number' => $this->string(100)->notNull(),
                'bic' => $this->string(20)->notNull(),
                'currencyCode' => $this->string(10),
                'customerId' => $this->integer()->notNull(),
            ]
        );
        
        $this->addForeignKey(
            'fk_sbbol2_customerAccount_customerId',
            $this->tableName,
            'customerId',
            '{{%sbbol2_customer}}',
            'id',
            'cascade'
        );

    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
