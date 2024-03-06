<?php

use yii\db\Migration;

class m180426_063355_create_documentExtEdmVTBContractRequest_and_documentExtEdmVTBContractRequestContract extends Migration
{
    private $requestTableName = '{{%documentExtEdmVTBContractRequest}}';
    private $requestDocumentIdForeignKeyName = 'documentExtEdmVTBContractRequest_documentId_FK';
    private $requestOrganizationIdForeignKeyName = 'documentExtEdmVTBContractRequest_organizationId_FK';
    private $contractTableName = '{{%documentExtEdmVTBContractRequestContract}}';
    private $contractRequestIdForeignKeyName = 'documentExtEdmVTBContractRequestContract_requestId_FK';
    private $contractCurrencyIdForeignKeyName = 'documentExtEdmVTBContractRequestContract_currencyId_FK';

    public function safeUp()
    {
        $this->createRequestTable();
        $this->createContractTable();
    }

    public function safeDown()
    {
        $this->dropRequestTable();
        $this->dropContractTable();
    }

    private function createRequestTable()
    {
        $this->createTable(
            $this->requestTableName,
            [
                'id'                        => $this->primaryKey(),
                'documentId'                => $this->bigInteger()->notNull()->unique(),
                'number'                    => $this->string(32)->notNull(),
                'type'                      => $this->string(32)->notNull(),
                'date'                      => $this->date()->notNull(),
                'organizationId'            => $this->integer(),
                'businessStatus'            => $this->string(4),
                'businessStatusComment'     => $this->string(),
                'businessStatusDescription' => $this->string(),
            ]
        );

        $this->addForeignKey(
            $this->requestDocumentIdForeignKeyName,
            $this->requestTableName,
            'documentId',
            '{{%document}}',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            $this->requestOrganizationIdForeignKeyName,
            $this->requestTableName,
            'organizationId',
            '{{%edmDictOrganization}}',
            'id'
        );
    }

    private function createContractTable()
    {
        $this->createTable(
            $this->contractTableName,
            [
                'id'         => $this->primaryKey(),
                'requestId'  => $this->integer()->notNull(),
                'number'     => $this->string(100),
                'date'       => $this->date(),
                'type'       => $this->string(32)->notNull(),
                'amount'     => $this->float(2),
                'currencyId' => $this->integer(),
            ]
        );

        $this->addForeignKey(
            $this->contractRequestIdForeignKeyName,
            $this->contractTableName,
            'requestId',
            $this->requestTableName,
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            $this->contractCurrencyIdForeignKeyName,
            $this->contractTableName,
            'currencyId',
            '{{%edmDictCurrencies}}',
            'id'
        );
    }

    private function dropRequestTable()
    {
        $this->dropForeignKey($this->contractCurrencyIdForeignKeyName, $this->contractTableName);
        $this->dropForeignKey($this->contractRequestIdForeignKeyName, $this->contractTableName);
        $this->dropTable($this->contractTableName);
    }

    private function dropContractTable()
    {
        $this->dropForeignKey($this->requestDocumentIdForeignKeyName, $this->requestTableName);
        $this->dropForeignKey($this->requestOrganizationIdForeignKeyName, $this->requestTableName);
        $this->dropTable($this->requestTableName);
    }
}
