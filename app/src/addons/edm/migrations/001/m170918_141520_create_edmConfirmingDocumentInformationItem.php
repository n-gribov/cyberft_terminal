<?php

use yii\db\Migration;

class m170918_141520_create_edmConfirmingDocumentInformationItem extends Migration
{
    public function up()
    {
        $this->createTable('edmConfirmingDocumentInformationItem', [
            'id' => $this->primaryKey(),
            'informationId' => $this->integer(),
            'number' => $this->string(),
            'date' => $this->string(),
            'notRequiredNumber' => $this->boolean(),
            'code' => $this->string(),
            'sumDocument' => $this->integer(),
            'sumContract' => $this->integer(),
            'currencyIdDocument' => $this->integer(),
            'currencyIdContract' => $this->integer(),
            'type' => $this->string(),
            'expectedDate' => $this->string(),
            'countryCode' => $this->string(),
            'comment' => $this->text()
        ]);
    }

    public function down()
    {
        $this->dropTable('edmConfirmingDocumentInformationItem');

        return true;
    }
}
