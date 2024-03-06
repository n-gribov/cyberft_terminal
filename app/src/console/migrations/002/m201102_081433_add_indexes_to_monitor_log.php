<?php

use yii\db\Migration;

class m201102_081433_add_indexes_to_monitor_log extends Migration
{
    private const TABLE_NAME = '{{%monitor_log}}';

    public function safeUp()
    {
        $this->createIndex('terminalId', self::TABLE_NAME, 'terminalId');
        $this->createIndex('ip', self::TABLE_NAME, 'ip');
        $this->createIndex('initiatorType', self::TABLE_NAME, 'initiatorType');
    }

    public function safeDown()
    {
        $this->dropIndex('terminalId', self::TABLE_NAME);
        $this->dropIndex('ip', self::TABLE_NAME);
        $this->dropIndex('initiatorType', self::TABLE_NAME);
    }
}
