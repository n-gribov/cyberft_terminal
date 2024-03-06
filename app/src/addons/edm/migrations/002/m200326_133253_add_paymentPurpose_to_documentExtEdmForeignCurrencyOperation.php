<?php

use yii\db\Migration;

class m200326_133253_add_paymentPurpose_to_documentExtEdmForeignCurrencyOperation extends Migration
{
    private $tableName = '{{%documentExtEdmForeignCurrencyOperation}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'paymentPurpose', $this->text());
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'paymentPurpose');
    }
}
