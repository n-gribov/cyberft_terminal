<?php

use yii\db\Migration;

class m190205_153825_create_edm_sbbolAccount extends Migration
{
    private $tableName = '{{%edm_sbbolAccount}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'         => $this->string(100)->notNull()->unique(),
            'number'     => $this->string(100)->notNull()->unique(),
            'customerId' => $this->string(100)->notNull(),
        ]);
        $this->addPrimaryKey('pk_sbbolAccount', $this->tableName, 'id');
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
