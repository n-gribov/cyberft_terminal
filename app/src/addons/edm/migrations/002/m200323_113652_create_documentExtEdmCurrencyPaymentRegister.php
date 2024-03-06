<?php

use yii\db\Migration;

class m200323_113652_create_documentExtEdmCurrencyPaymentRegister extends Migration
{
    private $tableName = '{{%documentExtEdmCurrencyPaymentRegister}}';

    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => $this->bigPrimaryKey(),
                'documentId' => $this->bigInteger()->notNull()->unique(),
                'date' => $this->date(),
                'debitAccount' => $this->string(),
                'paymentsCount' => $this->integer(),
                'uuid' => $this->string(),
                'businessStatus' => $this->string(4),
                'businessStatusDescription' => $this->string(),
                'businessStatusComment' => $this->string(),
            ]
        );
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
