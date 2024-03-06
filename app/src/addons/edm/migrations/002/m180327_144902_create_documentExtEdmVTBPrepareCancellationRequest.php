<?php

use yii\db\Migration;

class m180327_144902_create_documentExtEdmVTBPrepareCancellationRequest extends Migration
{
    private $tableName = '{{%documentExtEdmVTBPrepareCancellationRequest}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id'                           => $this->primaryKey(),
            'documentId'                   => $this->bigInteger()->notNull()->unique(),
            'targetDocumentUuid'           => $this->string(64)->notNull(),
            'messageForBank'               => $this->text(),
            'status'                       => $this->string(64)->notNull(),
            'targetDocumentInfo'           => $this->text(),
            'targetDocumentVTBReferenceId' => $this->string(64),
        ]);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
