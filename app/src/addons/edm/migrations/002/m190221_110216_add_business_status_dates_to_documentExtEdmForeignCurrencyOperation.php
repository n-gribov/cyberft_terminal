<?php

use yii\db\Migration;

class m190221_110216_add_business_status_dates_to_documentExtEdmForeignCurrencyOperation extends Migration
{
    private $tableName = '{{%documentExtEdmForeignCurrencyOperation}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'dateProcessing', $this->timestamp()->defaultValue(null));
        $this->addColumn($this->tableName, 'dateDue', $this->timestamp()->defaultValue(null));
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'dateProcessing');
        $this->dropColumn($this->tableName, 'dateDue');
    }
}
