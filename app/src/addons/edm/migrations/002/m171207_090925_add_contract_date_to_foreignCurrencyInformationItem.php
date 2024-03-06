<?php

use yii\db\Migration;

class m171207_090925_add_contract_date_to_foreignCurrencyInformationItem extends Migration
{
    public function up()
    {
        $this->addColumn('edmForeignCurrencyOperationInformationItem', 'contractDate', $this->string());
    }

    public function down()
    {
        $this->dropColumn('edmForeignCurrencyOperationInformationItem', 'contractDate');
    }
}
