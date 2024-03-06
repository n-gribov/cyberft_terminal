<?php

use yii\db\Migration;

class m180321_125434_add_beneficiaryBankAccount_and_intermediaryBankAccount_to_edm_ForeignCurrencyPaymentTemplates extends Migration
{
    private $tableName = 'edm_ForeignCurrencyPaymentTemplates';

    public function up()
    {
        $this->addColumn($this->tableName, 'beneficiaryBankAccount', $this->string());
        $this->addColumn($this->tableName, 'intermediaryBankAccount', $this->string());
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'beneficiaryBankAccount');
        $this->dropColumn($this->tableName, 'intermediaryBankAccount');
    }
}
