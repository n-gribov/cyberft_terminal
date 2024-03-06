<?php

use yii\db\Migration;

class m181106_143326_add_columns_to_sbbol_request extends Migration
{
    private $tableName = '{{%sbbol_request}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'incomingDocumentId', $this->bigInteger());
        $this->addColumn($this->tableName, 'receiverRequestStatus', $this->string());
        $this->addColumn($this->tableName, 'receiverDocumentStatus', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'incomingDocumentId');
        $this->dropColumn($this->tableName, 'receiverRequestStatus');
        $this->dropColumn($this->tableName, 'receiverDocumentStatus');
    }
}
