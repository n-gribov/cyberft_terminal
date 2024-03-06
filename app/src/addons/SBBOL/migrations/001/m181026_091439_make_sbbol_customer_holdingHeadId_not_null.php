<?php

use yii\db\Migration;

class m181026_091439_make_sbbol_customer_holdingHeadId_not_null extends Migration
{
    private $tableName = '{{%sbbol_customer}}';

    public function safeUp()
    {
        $this->execute('update sbbol_customer set holdingHeadId = id where isHoldingHead = 1');

        $this->dropHoldingHeadIdForeignKey();
        $this->alterColumn(
            $this->tableName,
            'holdingHeadId',
            $this->string(100)->notNull()
        );
        $this->createHoldingHeadIdForeignKey();
    }

    public function safeDown()
    {
        $this->dropHoldingHeadIdForeignKey();
        $this->alterColumn(
            $this->tableName,
            'holdingHeadId',
            $this->string(100)
        );
        $this->createHoldingHeadIdForeignKey();

        $this->execute('update sbbol_customer set holdingHeadId = null where isHoldingHead = 1');
    }

    private function dropHoldingHeadIdForeignKey()
    {
        $this->dropForeignKey('fk_sbbol_customer_holdingHeadId', $this->tableName);
    }

    private function createHoldingHeadIdForeignKey()
    {
        $this->addForeignKey(
            'fk_sbbol_customer_holdingHeadId',
            $this->tableName,
            'holdingHeadId',
            $this->tableName,
            'id'
        );
    }
}
