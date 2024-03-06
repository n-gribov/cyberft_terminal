<?php

use yii\db\Migration;

class m170607_093842_create_foreignCurrencyPaymentBeneficiary extends Migration
{
    public function up()
    {
        // Справочник получателей валютных платежей
        // @CYB-3747
        $this->createTable('edmForeignCurrencyPaymentBeneficiary', [
            'id' => $this->primaryKey(),
            'account' => $this->string(),
            'name' => $this->string(),
            'address' => $this->string(),
            'location' => $this->string(),
            'terminalId' => $this->integer()
        ]);
    }

    public function down()
    {
        $this->dropTable('edmForeignCurrencyPaymentBeneficiary');
    }
}
