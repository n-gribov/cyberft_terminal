<?php

use yii\db\Migration;

class m210427_105158_add_fields_to_import_errors extends Migration
{
    private const TABLE_NAME = '{{%import_errors}}';

    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME, 'documentNumber', $this->string(100));
        $this->addColumn(self::TABLE_NAME, 'documentCurrency', $this->string(3));
        $this->addColumn(self::TABLE_NAME, 'documentType', $this->string());
        $this->addColumn(self::TABLE_NAME, 'documentTypeGroup', $this->string());
        $this->addColumn(self::TABLE_NAME, 'senderTerminalAddress', $this->string(12));
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, 'documentNumber');
        $this->dropColumn(self::TABLE_NAME, 'documentCurrency');
        $this->dropColumn(self::TABLE_NAME, 'documentType');
        $this->dropColumn(self::TABLE_NAME, 'documentTypeGroup');
        $this->dropColumn(self::TABLE_NAME, 'senderTerminalAddress');
    }
}
