<?php

use yii\db\Migration;

class m190430_081544_create_sbbol2_UserExt extends Migration
{
    private $tableName = '{{%sbbol2_UserExt}}';

    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => $this->primaryKey(),
                'userId' => $this->bigInteger(),
                'canAccess' => $this->smallInteger(),
                'permissionsData' => $this->text(),
            ]
        );
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
