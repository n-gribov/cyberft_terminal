<?php

use yii\db\Migration;

class m171024_112141_create_sbbol_requestLog extends Migration
{
    private $tableName = '{{%sbbol_requestLog}}';

    public function up()
    {
        $this->createTable(
            $this->tableName,
            [
                'id'           => $this->primaryKey(),
                'dateCreate'   => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'name'         => $this->string(255)->notNull(),
                'body'         => $this->text()->notNull(),
                'responseBody' => $this->text(),
                'digest'       => $this->text(),
            ],
            'ENGINE = MyISAM'
        );
        $this->createIndex('name', $this->tableName, 'name');
        $this->createIndex('dateCreate', $this->tableName, 'dateCreate');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
