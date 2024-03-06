<?php

use yii\db\Migration;

class m200916_120127_update_api_documentExportQueue extends Migration
{
    private const TABLE_NAME = '{{%api_documentExportQueue}}';

    public function safeUp()
    {
        $this->createIndex('pk_documentExportQueue_uuid', self::TABLE_NAME, 'uuid', true);
        $this->alterColumn(
            self::TABLE_NAME,
            'path',
            $this->string(4000)->notNull()
        );
    }

    public function safeDown()
    {
        $this->dropIndex('pk_documentExportQueue_uuid', self::TABLE_NAME);
        $this->alterColumn(
            self::TABLE_NAME,
            'path',
            $this->string()->notNull()
        );
    }
}
