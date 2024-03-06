<?php

use yii\db\Migration;

class m201103_075921_add_indexes_to_edm_paymentOrder extends Migration
{
    private const TABLE_NAME = '{{%edm_paymentOrder}}';

    public function safeUp()
    {
        $this->createIndex('number', self::TABLE_NAME, 'number');
        $this->createIndex('date', self::TABLE_NAME, 'date');
        $this->createIndex('sum', self::TABLE_NAME, 'sum');
        $this->createIndex('currency', self::TABLE_NAME, 'currency');
        $this->createIndex('dateProcessing', self::TABLE_NAME, 'dateProcessing');
        $this->createIndex('dateDue', self::TABLE_NAME, 'dateDue');
        $this->createIndex('businessStatus', self::TABLE_NAME, 'businessStatus');
    }

    public function safeDown()
    {
        $this->dropIndex('number', self::TABLE_NAME);
        $this->dropIndex('date', self::TABLE_NAME);
        $this->dropIndex('sum', self::TABLE_NAME);
        $this->dropIndex('currency', self::TABLE_NAME);
        $this->dropIndex('dateProcessing', self::TABLE_NAME);
        $this->dropIndex('dateDue', self::TABLE_NAME);
        $this->dropIndex('businessStatus', self::TABLE_NAME);
    }
}
