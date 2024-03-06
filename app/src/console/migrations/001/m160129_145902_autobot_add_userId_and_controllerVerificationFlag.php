<?php

use yii\db\Schema;
use yii\db\Migration;

class m160129_145902_autobot_add_userId_and_controllerVerificationFlag extends Migration
{
    private $_tableName = "autobot";
    private $_refTableName = "user";
    private $_fkName = "fk_autobot_userId_to_user_id";

    public function up()
    {
        $this->addColumn($this->_tableName, 'userId', Schema::TYPE_INTEGER . '(10) unsigned DEFAULT NULL');
        $this->addColumn($this->_tableName, 'controllerVerificationFlag', Schema::TYPE_INTEGER . ' DEFAULT 0');
        return true;
    }

    public function down()
    {
        $this->dropColumn($this->_tableName, 'userId');
        $this->dropColumn($this->_tableName, 'controllerVerificationFlag');
        return true;
    }
    
}
