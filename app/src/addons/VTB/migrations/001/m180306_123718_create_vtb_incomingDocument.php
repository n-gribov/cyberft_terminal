<?php

use yii\db\Migration;

class m180306_123718_create_vtb_incomingDocument extends Migration
{
    private $tableName = '{{%vtb_incomingDocument}}';
    private $externalIdIndexName = '{{%vtb_incomingDocumentExternalId}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'dateCreate' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'externalId' => $this->string(64)->notNull(),
            'customerId' => $this->integer()->notNull(),
            'documentId' => $this->bigInteger()->notNull(),
        ]);
        $this->createIndex($this->externalIdIndexName, $this->tableName, 'externalId');
    }

    public function down()
    {
        $this->dropIndex($this->externalIdIndexName, $this->tableName);
        $this->dropTable($this->tableName);
    }
}
