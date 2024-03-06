<?php

use yii\db\Migration;

class m181128_103220_increase_sbbol_requestLog_text_fields extends Migration
{
    private $tableName = '{{%sbbol_requestLog}}';

    public function safeUp()
    {
        $this->alterColumn($this->tableName, 'body', 'longtext not null');
        $this->alterColumn($this->tableName, 'responseBody', 'longtext');
        $this->alterColumn($this->tableName, 'digest', 'longtext');
    }

    public function safeDown()
    {
        $this->alterColumn($this->tableName, 'body', $this->text()->notNull());
        $this->alterColumn($this->tableName, 'responseBody', $this->text());
        $this->alterColumn($this->tableName, 'digest', $this->text());
    }
}
