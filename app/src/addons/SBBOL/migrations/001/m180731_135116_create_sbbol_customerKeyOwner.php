<?php

use yii\db\Migration;

class m180731_135116_create_sbbol_customerKeyOwner extends Migration
{
    private $tableName = '{{%sbbol_customerKeyOwner}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'           => $this->string(100)->notNull()->unique(),
            'fullName'     => $this->string(500)->notNull(),
            'position'     => $this->string(500),
            'signDeviceId' => $this->string(100)->notNull(),
            'customerId'   => $this->string(100)->notNull(),
        ]);

        $this->addPrimaryKey('pk_customerKeyOwner', $this->tableName, 'id');

        $this->addForeignKey(
            'fk_sbbol_customerKeyOwner_customerId',
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
