<?php

use yii\db\Migration;

class m190712_083818_create_sbbol2_documentImportRequest extends Migration
{
    private $tableName = '{{%sbbol2_documentImportRequest}}';
    
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => $this->primaryKey(),
                'documentId' => $this->bigInteger()->notNull()->unique(),
                'createDate' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'importAttemptDate' => $this->timestamp()->defaultValue(null),
                'statusCheckDate' => $this->timestamp()->defaultValue(null),
                'externalDocumentId' => $this->string(),
                'bankDocumentStatus' => $this->string(),
                'bankComment' => $this->string(),
                'documentFieldsHash' => $this->string(),
                'status' => $this->string()                
            ]
        );
        
        $this->addForeignKey(
            'fk_sbbol2_documentImportRequest_documentId',
            $this->tableName,
            'documentId',
            '{{%document}}',
            'id',
            'cascade'
        );

    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
