<?php

use yii\db\Migration;

class m171110_141857_change_edmForeignCurrencyOperationInformationItem_sum_columns_type extends Migration
{
    private $tableName = '{{%edmForeignCurrencyOperationInformationItem}}';

    public function up()
    {
        $this->alterColumn($this->tableName,'operationSum', $this->decimal(35, 2));
        $this->alterColumn($this->tableName,'operationSumUnits', $this->decimal(35, 2));
    }

    public function down()
    {
        return true;
    }
}
