<?php

use yii\db\Migration;

class m170824_090807_create_edmForeignCurrencyOperationInformationItem extends Migration
{
    public function up()
    {
        $this->createTable('edmForeignCurrencyOperationInformationItem', [
            'id' => $this->primaryKey(),
            'paymentType' => $this->integer(),
            'number' => $this->string(),
            'docDate' => $this->string(),
            'codeFCO' => $this->string(),
            'operationDate' => $this->string(),
            'operationSum' => $this->integer(),
            'currencyId' => $this->integer(),
            'contractPassport' => $this->string(),
            'operationSumUnits' => $this->integer(),
            'currencyUnitsId' => $this->integer(),
            'refundDate' => $this->string(),
            'expectedDate' => $this->string(),
            'comment' => $this->text(),
            'informationId' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('edmForeignCurrencyOperationInformationItem');
    }
}
