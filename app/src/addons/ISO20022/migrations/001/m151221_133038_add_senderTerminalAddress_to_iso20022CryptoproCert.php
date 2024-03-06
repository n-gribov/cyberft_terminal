<?php

use yii\db\Schema;
use yii\db\Migration;
use addons\ISO20022\models\ISO20022CryptoproCert;
use common\models\Terminal;

class m151221_133038_add_senderTerminalAddress_to_iso20022CryptoproCert extends Migration
{
    private $_tableName = "iso20022_CryptoproCert";
    private $_refTableName = "terminal";
    private $_fkName = "fk_iso20022cryptoproCert_terminalId_to_terminal_id";

    public function up()
    {
        $this->renameColumn($this->_tableName, 'terminalId', 'senderTerminalAddress');
        $this->addColumn($this->_tableName, 'terminalId', Schema::TYPE_INTEGER);
        $this->addForeignKey($this->_fkName, $this->_tableName, 'terminalId', $this->_refTableName, 'id', 'CASCADE', 'CASCADE');
        $this->fillTerminalId();
        return true;
    }

    public function down()
    {       
        $this->dropForeignKey($this->_fkName, $this->_tableName, 'terminalId');
        $this->dropColumn($this->_tableName, 'terminalId');
        $this->renameColumn($this->_tableName, 'senderTerminalAddress', 'terminalId');
        return true;
    }

    public function fillTerminalId()
    {
        $cryptoproCerts = ISO20022CryptoproCert::find()->all();
        $defaultTerminal = Terminal::getDefaultTerminal();
        foreach ($cryptoproCerts as $cert) {
            $cert->terminalId = $defaultTerminal->id;
            // Сохранить модель в БД
            $cert->save();
        }
    }
    
}
