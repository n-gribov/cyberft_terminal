<?php

use yii\db\Migration;

/**
 * Class m200206_113054_alter_table_documentExtEdmStatement
 */
class m200206_113054_alter_table_documentExtEdmStatement extends Migration
{
    private $tableName = '{{%documentExtEdmStatement}}';
    
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'extStatus', $this->string(32));
        $this->addColumn($this->tableName, 'msgId', $this->string(64));
        $this->addColumn($this->tableName, 'originalFilename', $this->string());
        $this->addColumn($this->tableName, 'errorCode', $this->string());
        $this->addColumn($this->tableName, 'errorDescription', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'extStatus');
        $this->dropColumn($this->tableName, 'msgId');
        $this->dropColumn($this->tableName, 'originalFilename');
        $this->dropColumn($this->tableName, 'errorCode');
        $this->dropColumn($this->tableName, 'errorDescription');
    }
}
