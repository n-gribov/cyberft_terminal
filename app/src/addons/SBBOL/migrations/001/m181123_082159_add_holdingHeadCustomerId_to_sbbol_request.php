<?php

use yii\db\Migration;

class m181123_082159_add_holdingHeadCustomerId_to_sbbol_request extends Migration
{
    private $tableName = '{{%sbbol_request}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'holdingHeadCustomerId', $this->string()->notNull());
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'holdingHeadCustomerId');
    }
}
