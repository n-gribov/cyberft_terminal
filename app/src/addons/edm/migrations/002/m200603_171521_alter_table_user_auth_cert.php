<?php

use yii\db\Migration;

/**
 * Class m200603_171521_alter_table_user_auth_cert
 */
class m200603_171521_alter_table_user_auth_cert extends Migration
{
    private $tableName = '{{%user_auth_cert}}';
    
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'status', $this->string(10));
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'status');
    }
}
