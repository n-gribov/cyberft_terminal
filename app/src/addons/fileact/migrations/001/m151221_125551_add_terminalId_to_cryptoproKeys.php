<?php

use yii\db\Schema;
use yii\db\Migration;

class m151221_125551_add_terminalId_to_cryptoproKeys extends Migration
{
    private $_tableName = "cryptoproKeys";
    private $_refTableName = "terminal";
    private $_fkName = "fk_terminalId_to_terminal_id";

    public function up()
    {
        $this->addColumn($this->_tableName, 'terminalId', Schema::TYPE_INTEGER);
        $this->addForeignKey($this->_fkName, $this->_tableName, 'terminalId', $this->_refTableName, 'id', 'CASCADE', 'CASCADE');
        return true;
    }

    public function down()
    {       
        $this->dropForeignKey($this->_fkName, $this->_tableName);
        $this->dropColumn($this->_tableName, 'terminalId');
        return true;
    }
    
}
