<?php

use yii\db\Migration;

class m180321_141220_join_edm_ForeignCurrencyPaymentTemplates_intermediaryBankName_and_intermediaryBankAddress extends Migration
{
    private $tableName = 'edm_ForeignCurrencyPaymentTemplates';

    public function up()
    {
        $this->addColumn($this->tableName, 'intermediaryBankNameAndAddress', $this->string());

        $templates = Yii::$app->db->createCommand('select * from edm_ForeignCurrencyPaymentTemplates')->queryAll();
        foreach ($templates as $template) {
            $intermediaryBankParts = [
                $template['intermediaryBankName'],
                $template['intermediaryBankAddress'],
            ];
            $intermediaryBankParts = array_filter($intermediaryBankParts);
            $intermediaryBankNameAndAddress = implode("\r\n", $intermediaryBankParts);

            $this->execute(
                'update edm_ForeignCurrencyPaymentTemplates set intermediaryBankNameAndAddress = :intermediaryBankNameAndAddress where id = :id',
                [':intermediaryBankNameAndAddress' => $intermediaryBankNameAndAddress, ':id' => $template['id']]
            );
        }

        $this->dropColumn($this->tableName, 'intermediaryBankName');
        $this->dropColumn($this->tableName, 'intermediaryBankAddress');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'intermediaryBankNameAndAddress');
        $this->addColumn($this->tableName, 'intermediaryBankName', $this->string());
        $this->addColumn($this->tableName, 'intermediaryBankAddress', $this->string());
    }
}
