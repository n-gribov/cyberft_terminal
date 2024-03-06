<?php

use yii\db\Migration;

class m210621_064715_change_edmConfirmingDocumentInformationItem_sum_columns_type extends Migration
{
    private const TABLE_NAME = '{{%edmConfirmingDocumentInformationItem}}';

    public function safeUp()
    {
        $this->alterColumn(self::TABLE_NAME,'sumDocument', $this->decimal(35, 2));
        $this->alterColumn(self::TABLE_NAME,'sumContract', $this->decimal(35, 2));
    }

    public function safeDown()
    {
        $this->alterColumn(self::TABLE_NAME,'sumDocument', $this->integer(11));
        $this->alterColumn(self::TABLE_NAME,'sumContract', $this->integer(11));
    }
}
