<?php

use yii\db\Migration;

class m180316_071714_join_edm_ForeignCurrencyPaymentTemplates_columns extends Migration
{
    private $tableName = 'edm_ForeignCurrencyPaymentTemplates';

    public function up()
    {
        $this->addColumn($this->tableName, 'beneficiary', $this->string());

        $templates = Yii::$app->db->createCommand('select * from edm_ForeignCurrencyPaymentTemplates')->queryAll();
        foreach ($templates as $template) {
            $beneficiaryParts = [
                $template['beneficiaryName'],
                $template['beneficiaryCountry'],
                $template['beneficiaryAddress'],
            ];
            $beneficiaryParts = array_filter($beneficiaryParts);
            $beneficiary = implode("\r\n", $beneficiaryParts);

            $this->execute(
                'update edm_ForeignCurrencyPaymentTemplates set beneficiary = :beneficiary where id = :id',
                [':beneficiary' => $beneficiary, ':id' => $template['id']]
            );
        }

        $this->dropColumn($this->tableName, 'beneficiaryName');
        $this->dropColumn($this->tableName, 'beneficiaryCountry');
        $this->dropColumn($this->tableName, 'beneficiaryAddress');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'beneficiary');
        $this->addColumn($this->tableName, 'beneficiaryName', $this->string());
        $this->addColumn($this->tableName, 'beneficiaryCountry', $this->string());
        $this->addColumn($this->tableName, 'beneficiaryAddress', $this->string());
    }
}
