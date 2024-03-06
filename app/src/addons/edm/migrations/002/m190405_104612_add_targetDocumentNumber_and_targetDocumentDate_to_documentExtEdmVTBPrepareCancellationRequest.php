<?php

use yii\db\Migration;

class m190405_104612_add_targetDocumentNumber_and_targetDocumentDate_to_documentExtEdmVTBPrepareCancellationRequest extends Migration
{
    private $tableName = '{{%documentExtEdmVTBPrepareCancellationRequest}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'targetDocumentNumber', $this->string());
        $this->addColumn($this->tableName, 'targetDocumentDate', $this->date());
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'targetDocumentNumber');
        $this->dropColumn($this->tableName, 'targetDocumentDate');
    }
}
