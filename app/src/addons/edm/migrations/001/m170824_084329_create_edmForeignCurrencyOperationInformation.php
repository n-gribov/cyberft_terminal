<?php

use yii\db\Migration;

class m170824_084329_create_edmForeignCurrencyOperationInformation extends Migration
{
    public function up()
    {
        $this->createTable('edmForeignCurrencyOperationInformation', [
            'id' => $this->primaryKey(),
            'organizationId' => $this->integer(),
            'accountId' => $this->integer(),
            'number' => $this->string(),
            'correctionNumber' => $this->string(),
            'date' => $this->string(),
            'countryCode' => $this->string(),
            'terminalId' => $this->integer()
        ]);
    }

    public function down()
    {
        $this->dropTable('edmForeignCurrencyOperationInformation');
    }
}
