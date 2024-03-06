<?php

use yii\db\Migration;

class m171228_131622_create_vtb_documentImportRequest extends Migration
{
    private $tableName = '{{%vtb_documentImportRequest}}';
    private $documentForeignKeyName = 'fk_vtb_document_import_request_document';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id'                     => $this->primaryKey(),
            'status'                 => $this->string(64)->notNull(),
            'documentId'             => $this->bigInteger(20)->notNull(),
            'dateCreate'             => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'importAttemptDate'      => 'TIMESTAMP NULL',
            'statusCheckDate'        => 'TIMESTAMP NULL',
            'externalRequestId'      => $this->string(64),
            'externalDocumentStatus' => $this->integer(),
            'externalDocumentInfo'   => $this->text(),
        ]);
        $this->addForeignKey(
            $this->documentForeignKeyName,
            $this->tableName,
            'documentId',
            '{{%document}}',
            'id'
        );
    }

    public function down()
    {
        $this->dropForeignKey($this->documentForeignKeyName, $this->tableName);
        $this->dropTable($this->tableName);
    }
}
