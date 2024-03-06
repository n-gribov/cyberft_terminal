<?php

use yii\db\Migration;

/**
 * Class m200616_131043_create_table_api_documentExportQueue
 */
class m200616_131043_create_table_api_documentExportQueue extends Migration
{
    private $tableName = '{{%api_documentExportQueue}}';

    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => $this->primaryKey(),
                'uuid' => $this->string()->notNull()->unique(),
                'path' => $this->string()->notNull(),
            ]
        );
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
