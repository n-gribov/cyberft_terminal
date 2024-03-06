<?php

use yii\db\Migration;

class m180723_112119_increase_documentExtFinZip_desr_again extends Migration
{
    private $tableName = '{{%documentExtFinZip}}';

    public function safeUp()
    {
        $this->alterColumn($this->tableName, 'descr', $this->string(65535));
    }

    public function safeDown()
    {
        $this->alterColumn($this->tableName, 'descr', $this->string(2000));
    }
}
