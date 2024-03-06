<?php

use yii\db\Migration;

class m190409_090353_add_columns_to_vtb_customerAccount extends Migration
{
    private $tableName = '{{%vtb_customerAccount}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'bankBranchId', $this->bigInteger());
        $this->addColumn($this->tableName, 'bankBranchName', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'bankBranchId');
        $this->dropColumn($this->tableName, 'bankBranchName');
    }
}
