<?php

use yii\db\Migration;

class m180807_085644_add_fields_to_sbbol_request extends Migration
{
    private $tableName = '{{%sbbol_request}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'documentType', $this->string(300)->notNull());
        $this->addColumn($this->tableName, 'customerId', $this->string(100)->notNull());
        $this->addColumn($this->tableName, 'receiverDocumentId', $this->string(100));
        $this->addColumn($this->tableName, 'documentStatusRequestId', $this->string(100));
        $this->addColumn($this->tableName, 'responseHandlerParamsJson', $this->text());
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'documentType');
        $this->dropColumn($this->tableName, 'customerId');
        $this->dropColumn($this->tableName, 'receiverDocumentId');
        $this->dropColumn($this->tableName, 'documentStatusRequestId');
        $this->dropColumn($this->tableName, 'responseHandlerParamsJson');
    }
}
