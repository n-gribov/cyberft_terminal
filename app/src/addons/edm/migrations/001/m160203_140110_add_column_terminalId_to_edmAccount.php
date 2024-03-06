<?php

use yii\db\Schema;
use yii\db\Migration;
use common\models\Terminal;

class m160203_140110_add_column_terminalId_to_edmAccount extends Migration
{
    private $_tableName = 'edm_account';
    private $_columnName = 'terminalId';
    public function up()
    {
        $this->addColumn($this->_tableName, 'terminalId', Schema::TYPE_INTEGER . ' DEFAULT NULL');
        $this->db->schema->refresh();
        $terminals = Terminal::findAll(['status' => Terminal::STATUS_ACTIVE]);
        if (count($terminals) === 1) {
            $this->db->createCommand()->update($this->_tableName, ['terminalId' => $terminals[0]->id])->execute();
        }
        return true;
    }

    public function down()
    {
        $this->dropColumn($this->_tableName, $this->_columnName);
        return true;
    }

}
