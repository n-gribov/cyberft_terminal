<?php

use yii\db\Migration;

class m180330_074942_create_vtb_customerAccount extends Migration
{
    private $tableName = '{{%vtb_customerAccount}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'         => $this->primaryKey(),
            'customerId' => $this->integer()->notNull(),
            'number'     => $this->string(32)->notNull(),
            'bankBik'    => $this->string(9)->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
