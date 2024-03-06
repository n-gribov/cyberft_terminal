<?php

use yii\db\Schema;
use yii\db\Migration;
use addons\fileact\models\FileactCryptoproCert;
use common\models\Terminal;

class m151221_154951_add_senderTerminalAddress_to_fileactCryptoproCert extends Migration
{
    private $_tableName = "fileact_CryptoproCert";
    private $_refTableName = "terminal";
    private $_fkName = "fk_fileactCryptoproCert_terminalId_to_terminal_id";

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
        $cryptoproCerts = FileactCryptoproCert::find()->all();
        $defaultTerminal = Terminal::getDefaultTerminal();
        foreach ($cryptoproCerts as $cert) {
            $cert->terminalId = $defaultTerminal->id;
            // Сохранить модель в БД
            $cert->save();
        }
    }
    
}

