<?php

use yii\db\Migration;

/**
 * Добавление полей для бизнес-статуса и его описания для ext-таблицы документов валютных операций
 * @task CYB-3794
 */
class m180405_121212_add_business_status_field_to_fco_ext extends Migration
{
    protected $extTable = 'documentExtEdmForeignCurrencyOperation';

    public function up()
    {
        $this->addColumn($this->extTable, 'businessStatus', $this->string(4));
        $this->addColumn($this->extTable, 'businessStatusDescription', $this->string());
        $this->addColumn($this->extTable, 'businessStatusComment', $this->text());

        return true;
    }

    public function down()
    {
        $this->dropColumn($this->extTable, 'businessStatus');
        $this->dropColumn($this->extTable, 'businessStatusDescription');
        $this->dropColumn($this->extTable, 'businessStatusComment');

        return true;
    }
}
