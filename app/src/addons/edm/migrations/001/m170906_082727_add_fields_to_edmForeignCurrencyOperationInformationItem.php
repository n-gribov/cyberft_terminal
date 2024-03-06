<?php

use yii\db\Migration;

class m170906_082727_add_fields_to_edmForeignCurrencyOperationInformationItem extends Migration
{
    public function up()
    {
        $this->addColumn('edmForeignCurrencyOperationInformationItem', 'notRequiredSection1', $this->boolean());
        $this->addColumn('edmForeignCurrencyOperationInformationItem', 'notRequiredSection2', $this->boolean());
        $this->addColumn('edmForeignCurrencyOperationInformationItem', 'contractNumber', $this->string());
    }

    public function down()
    {
        $this->dropColumn('edmForeignCurrencyOperationInformationItem', 'notRequiredSection1');
        $this->dropColumn('edmForeignCurrencyOperationInformationItem', 'notRequiredSection2');
        $this->dropColumn('edmForeignCurrencyOperationInformationItem', 'contractNumber');

        return true;
    }
}
