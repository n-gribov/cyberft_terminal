<?php

use yii\db\Migration;

class m180214_141405_create_documentExtEdmBankLetter extends Migration
{
    private $tableName = '{{%documentExtEdmBankLetter}}';
    private $documentIdForeignKeyName = 'documentExtEdmBankLetter_documentId_to_document_id';
    private $storedFileIdForeignKeyName = 'documentExtEdmBankLetter_storedFileId_to_storage_id';

    public function up()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => $this->primaryKey(),
                'documentId' => $this->bigInteger()->notNull()->unique(),
                'subject' => $this->string(255)->notNull(),
                'storedFileId' => $this->bigInteger(),
            ]
        );
        $this->addForeignKey(
            $this->documentIdForeignKeyName,
            $this->tableName,
            'documentId',
            '{{%document}}',
            'id'
        );
        $this->addForeignKey(
            $this->storedFileIdForeignKeyName,
            $this->tableName,
            'storedFileId',
            '{{%storage}}',
            'id'
        );
    }

    public function down()
    {
        $this->dropForeignKey($this->documentIdForeignKeyName, $this->tableName);
        $this->dropForeignKey($this->storedFileIdForeignKeyName, $this->tableName);
        $this->dropTable($this->tableName);
    }
}
