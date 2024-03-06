<?php

use yii\db\Migration;

class m161114_075936_add_edm_templates extends Migration
{
    public function up()
    {
        // Для хранения шаблонов платежных поручений было принятно решение использовать отдельную таблицу,
        // несмотря на то что она по большей части дублирует таблицу edm_paymentOrder
        // CYB-3310
        $this->createTable('edm_paymentOrderTemplates',[
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'body' => $this->text(),
            'number' => $this->smallInteger(5)->unsigned(),
            'date' => $this->date(),
            'sum' => $this->double(),
            'beneficiaryName' => $this->string(),
            'beneficiaryCheckingAccount' => $this->string(30),
            'payerAccount' => $this->string(30),
            'currency' => $this->string(4),
            'payerName' => $this->string(),
            'paymentPurpose' => $this->string(),
            'dateProcessing' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'dateDue' => $this->timestamp()->notNull()->defaultValue('0000-00-00 00:00:00'),
            'terminalId' => $this->integer(10)->unsigned(),
            'vat' => $this->string(),
            'paymentPurposeNds' => $this->string()
        ]);
    }

    public function down()
    {
        $this->dropTable('edm_paymentOrderTemplates');
        return true;
    }
}
