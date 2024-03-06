<?php

use yii\db\Migration;

/**
 * Хранение шаблонов валютных платежей
 * @task CYB-3773
 */
class m180129_083801_create_fcp_templates_table extends Migration
{
    public function up()
    {
        $this->createTable('edm_ForeignCurrencyPaymentTemplates', [
            'id' => $this->primaryKey(),
            'templateName' => $this->string(),
            'sum' => $this->string(),
            'currency' => $this->string(),
            'payerAccount' => $this->string(),
            'payerInn' => $this->string(),
            'payerName' => $this->string(),
            'payerAddress' => $this->string(),
            'payerLocation' => $this->string(),
            'payerBank' => $this->string(),
            'payerBankName' => $this->string(),
            'payerBankAddress' => $this->string(),
            'intermediaryBank' => $this->string(),
            'intermediaryBankName' => $this->string(),
            'intermediaryBankAddress' => $this->string(),
            'beneficiaryAccount' => $this->string(),
            'beneficiaryName' => $this->string(),
            'beneficiaryAddress' => $this->string(),
            'beneficiaryCountry' => $this->string(),
            'beneficiaryBank' => $this->string(),
            'beneficiaryBankName' => $this->string(),
            'beneficiaryBankAddress' => $this->string(),
            'information' => $this->string(),
            'commission' => $this->string(),
            'commissionSum' => $this->string(),
            'additionalInformation' => $this->string(),
            'terminalId' => $this->integer()
        ]);
    }

    public function down()
    {
        $this->dropTable('edm_ForeignCurrencyPaymentTemplates');
    }
}
