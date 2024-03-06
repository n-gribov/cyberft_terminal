<?php

use yii\db\Migration;

class m180321_074424_join_edm_ForeignCurrencyPaymentTemplates_beneficiaryBankName_and_beneficiaryBankAddress extends Migration
{
    private $tableName = 'edm_ForeignCurrencyPaymentTemplates';

    public function up()
    {
        $this->addColumn($this->tableName, 'beneficiaryBankNameAndAddress', $this->string());

        $templates = Yii::$app->db->createCommand('select * from edm_ForeignCurrencyPaymentTemplates')->queryAll();
        foreach ($templates as $template) {
            $beneficiaryBankParts = [
                $template['beneficiaryBankName'],
                $template['beneficiaryBankAddress'],
            ];
            $beneficiaryBankParts = array_filter($beneficiaryBankParts);
            $beneficiaryBankNameAndAddress = implode("\r\n", $beneficiaryBankParts);

            $this->execute(
                'update edm_ForeignCurrencyPaymentTemplates set beneficiaryBankNameAndAddress = :beneficiaryBankNameAndAddress where id = :id',
                [':beneficiaryBankNameAndAddress' => $beneficiaryBankNameAndAddress, ':id' => $template['id']]
            );
        }

        $this->dropColumn($this->tableName, 'beneficiaryBankName');
        $this->dropColumn($this->tableName, 'beneficiaryBankAddress');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'beneficiaryBankNameAndAddress');
        $this->addColumn($this->tableName, 'beneficiaryBankName', $this->string());
        $this->addColumn($this->tableName, 'beneficiaryBankAddress', $this->string());
    }
}
