<?php

use yii\db\Migration;

class m210201_083911_add_terminal_address_to_api_documentExportQueue extends Migration
{
    private const TABLE_NAME = '{{%api_documentExportQueue}}';

    public function safeUp()
    {
        $this->addColumn(
            self::TABLE_NAME,
            'terminalAddress',
            $this->string(12)->notNull()
        );
        $this->createIndex('idx_api_documentExportQueue_terminalAddress', self::TABLE_NAME, 'terminalAddress');
    }

    public function safeDown()
    {
        $this->dropIndex('idx_api_documentExportQueue_terminalAddress', self::TABLE_NAME);
        $this->dropColumn(
            self::TABLE_NAME,
            'terminalAddress'
        );
    }
}
