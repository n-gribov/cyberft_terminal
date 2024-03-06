<?php

use yii\db\Migration;

/**
 * Изменение формата полей с суммами для отображения значений с копейками
 * @task CYB-4219
 */
class m180719_112348_modify_fco_ext_sum_types extends Migration
{
    private $tableName = "documentExtEdmForeignCurrencyOperation";

    public function up()
    {
        $this->alterColumn($this->tableName, 'currencySum', $this->decimal(10, 2));
        $this->alterColumn($this->tableName, 'sum', $this->decimal(10, 2));

        return true;
    }

    public function down()
    {
        $this->alterColumn($this->tableName, 'currencySum', $this->decimal(10));
        $this->alterColumn($this->tableName, 'sum', $this->decimal(10));

        return true;
    }
}
