<?php

use yii\db\Migration;

class m191021_144814_add_bankName_to_raiffeisen_customerAccount extends Migration
{
    private $tableName = '{{%raiffeisen_customerAccount}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'bankName', $this->string(1000)->notNull());
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'bankName');
    }
}
