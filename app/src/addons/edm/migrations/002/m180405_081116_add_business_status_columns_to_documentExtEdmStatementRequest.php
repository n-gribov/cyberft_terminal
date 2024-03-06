<?php

use yii\db\Migration;

class m180405_081116_add_business_status_columns_to_documentExtEdmStatementRequest extends Migration
{
    private $tableName = '{{%documentExtEdmStatementRequest}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'businessStatus', $this->string(4));
        $this->addColumn($this->tableName, 'businessStatusComment', $this->string());
        $this->addColumn($this->tableName, 'businessStatusDescription', $this->string());
        $this->addColumn($this->tableName, 'businessStatusErrorCode', $this->string(32));
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'businessStatus');
        $this->dropColumn($this->tableName, 'businessStatusComment');
        $this->dropColumn($this->tableName, 'businessStatusDescription');
        $this->dropColumn($this->tableName, 'businessStatusErrorCode');
    }
}
