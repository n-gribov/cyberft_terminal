<?php

use yii\db\Migration;

class m180524_092140_create_documentExtEdmVTBCancellationRequest extends Migration
{
    private $tableName = '{{%documentExtEdmVTBCancellationRequest}}';
    private $documentIdForeignKeyName = 'documentExtEdmVTBCancellationRequest_documentId_FK';

    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => $this->primaryKey(),
                'documentId' => $this->bigInteger()->notNull()->unique(),
                'prepareCancellationRequestDocumentId' => $this->bigInteger(),
                'businessStatus' => $this->string(4),
                'businessStatusComment' => $this->string(),
                'businessStatusDescription' => $this->string(),
                'businessStatusErrorCode' => $this->string(32),
            ]
        );

        $this->addForeignKey(
            $this->documentIdForeignKeyName,
            $this->tableName,
            'documentId',
            '{{%document}}',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey($this->documentIdForeignKeyName, $this->tableName);
        $this->dropTable($this->tableName);
    }
}
