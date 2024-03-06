<?php

use yii\db\Migration;

class m180618_111724_increase_documentExtFinZip_desr extends Migration
{
    private $tableName = '{{%documentExtFinZip}}';

    public function safeUp()
    {
        $this->alterColumn($this->tableName, 'descr', $this->string(2000));
    }

    public function safeDown()
    {
        $this->alterColumn($this->tableName, 'descr', $this->string(255));
    }
}
