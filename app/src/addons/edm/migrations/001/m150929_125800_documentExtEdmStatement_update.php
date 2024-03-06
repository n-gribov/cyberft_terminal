<?php

use yii\db\Migration;

class m150929_125800_documentExtEdmStatement_update extends Migration
{
    private $_tableName = 'documentExtEdmStatement';

    public function up()
    {
        $this->addColumn($this->_tableName, 'number', 'varchar(255) DEFAULT NULL');
        $this->addColumn($this->_tableName, 'companyName', 'varchar(255) DEFAULT NULL');
        $this->addColumn($this->_tableName, 'accountNumber', 'varchar(255) DEFAULT NULL');
        $this->addColumn($this->_tableName, 'openingBalance', 'double DEFAULT NULL');
        $this->addColumn($this->_tableName, 'debitTurnover', 'double DEFAULT NULL');
        $this->addColumn($this->_tableName, 'creditTurnover', 'double DEFAULT NULL');
        $this->addColumn($this->_tableName, 'closingBalance', 'double DEFAULT NULL');
        $this->addColumn($this->_tableName, 'accountBik', 'char(9) DEFAULT NULL');
        $this->addColumn($this->_tableName, 'dateCreated', "timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'");
        $this->addColumn($this->_tableName, 'periodStart', 'date DEFAULT NULL');
        $this->addColumn($this->_tableName, 'periodEnd', 'date DEFAULT NULL');
        $this->addColumn($this->_tableName, 'prevLastOperationDate', "timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'");
    }

    public function down()
    {
        $this->dropColumn($this->_tableName, 'number');
        $this->dropColumn($this->_tableName, 'dateCreated');
        $this->dropColumn($this->_tableName, 'openingBalance');
        $this->dropColumn($this->_tableName, 'debitTurnover');
        $this->dropColumn($this->_tableName, 'creditTurnover');
        $this->dropColumn($this->_tableName, 'closingBalance');
        $this->dropColumn($this->_tableName, 'accountNumber');
        $this->dropColumn($this->_tableName, 'accountBik');
        $this->dropColumn($this->_tableName, 'periodStart');
        $this->dropColumn($this->_tableName, 'periodEnd');
        $this->dropColumn($this->_tableName, 'companyName');
        $this->dropColumn($this->_tableName, 'prevLastOperationDate');
    }
}
