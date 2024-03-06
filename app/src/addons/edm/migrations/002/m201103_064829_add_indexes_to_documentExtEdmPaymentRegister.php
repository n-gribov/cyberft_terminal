<?php

use yii\db\Migration;

class m201103_064829_add_indexes_to_documentExtEdmPaymentRegister extends Migration
{
    private const TABLE_NAME = '{{%documentExtEdmPaymentRegister}}';

    public function safeUp()
    {
        $this->createIndex('date', self::TABLE_NAME, 'date');
        $this->createIndex('sum', self::TABLE_NAME, 'sum');
        $this->createIndex('accountId', self::TABLE_NAME, 'accountId');
        $this->createIndex('accountNumber', self::TABLE_NAME, 'accountNumber');
        $this->createIndex('orgId', self::TABLE_NAME, 'orgId');
        $this->createIndex('businessStatus', self::TABLE_NAME, 'businessStatus');
        $this->createIndex('msgId', self::TABLE_NAME, 'msgId');
    }

    public function safeDown()
    {
        $this->dropIndex('date', self::TABLE_NAME);
        $this->dropIndex('sum', self::TABLE_NAME);
        $this->dropIndex('accountId', self::TABLE_NAME);
        $this->dropIndex('accountNumber', self::TABLE_NAME);
        $this->dropIndex('orgId', self::TABLE_NAME);
        $this->dropIndex('businessStatus', self::TABLE_NAME);
        $this->dropIndex('msgId', self::TABLE_NAME);
    }
}
