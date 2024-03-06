<?php

use yii\db\Migration;

class m191111_081658_create_userPreviousPassword extends Migration
{
    private $tableName = '{{%userPreviousPassword}}';

    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id'           => $this->primaryKey(),
                'userId'       => $this->integer()->unsigned()->notNull(),
                'createDate'   => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'passwordHash' => $this->string()->notNull(),
            ]
        );

        $this->addForeignKey(
            'fk_userPreviousPassword_userId',
            $this->tableName,
            'userId',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
