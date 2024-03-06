<?php

use yii\db\Migration;

class m201103_072337_add_indexes_to_edmPayersAccounts extends Migration
{
    private const TABLE_NAME = '{{%edmPayersAccounts}}';

    public function safeUp()
    {
        $this->createIndex('currencyId', self::TABLE_NAME, 'currencyId');
        $this->createIndex('bankBik', self::TABLE_NAME, 'bankBik');
        $this->createIndex('payerName', self::TABLE_NAME, 'payerName');
    }

    public function safeDown()
    {
        $this->dropIndex('currencyId', self::TABLE_NAME);
        $this->dropIndex('bankBik', self::TABLE_NAME);
        $this->dropIndex('payerName', self::TABLE_NAME);
    }
}
