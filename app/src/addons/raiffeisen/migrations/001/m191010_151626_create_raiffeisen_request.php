<?php

use yii\db\Migration;

class m191010_151626_create_raiffeisen_request extends Migration
{
    private $tableName = '{{%raiffeisen_request}}';

    public function up()
    {
        $this->createTable(
            $this->tableName,
            [
                'id'                        => $this->primaryKey(),
                'status'                    => $this->string(64)->notNull(),
                'senderRequestId'           => $this->string(64),
                'receiverRequestId'         => $this->string(64),
                'dateCreate'                => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'responseCheckDate'         => 'TIMESTAMP NULL',
                'documentType'              => $this->string(300)->notNull(),
                'customerId'                => $this->integer()->notNull(),
                'holdingHeadCustomerId'     => $this->integer()->notNull(),
                'receiverDocumentId'        => $this->string(100),
                'documentStatusRequestId'   => $this->string(100),
                'responseHandlerParamsJson' => $this->text(),
                'incomingDocumentId'        => $this->bigInteger(),
                'receiverRequestStatus'     => $this->string(),
                'receiverDocumentStatus'    => $this->string(),
            ]
        );
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
