<?php

use yii\db\Migration;

class m180906_083711_create_processing extends Migration
{
    private $tableName = '{{%processing}}';

    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id'        => $this->primaryKey(),
                'name'      => $this->string()->notNull()->unique(),
                'address'   => $this->string(12)->notNull()->unique(),
                'dsn'       => $this->string()->notNull()->unique(),
                'isDefault' => $this->boolean()->defaultValue(false),
            ]
        );
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
