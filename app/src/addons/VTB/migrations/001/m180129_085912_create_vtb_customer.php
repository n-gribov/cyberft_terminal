<?php

use yii\db\Migration;

class m180129_085912_create_vtb_customer extends Migration
{
    private $tableName = '{{%vtb_customer}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id'         => $this->primaryKey(),
            'customerId' => $this->integer()->notNull()->unique(),
            'fullName'   => $this->string(500)->notNull(),
            'inn'        => $this->string(32),
            'terminalId' => $this->string(32),
        ]);
    }

    public function down()
    {
        $this->dropTable('vtb_customer');
    }
}
