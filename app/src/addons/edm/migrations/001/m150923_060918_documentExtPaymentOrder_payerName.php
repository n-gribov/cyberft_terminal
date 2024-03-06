<?php

use yii\db\Schema;
use yii\db\Migration;

class m150923_060918_documentExtPaymentOrder_payerName extends Migration
{
    private $_tableName = 'documentExtEdmPaymentOrder';

    public function up()
    {
        $this->addColumn($this->_tableName, 'payerName', "varchar(255) DEFAULT NULL");
        $this->addColumn($this->_tableName, 'paymentPurpose', "varchar(255) DEFAULT NULL");
        $this->alterColumn($this->_tableName, 'payerAccount', "varchar(30) DEFAULT NULL");
    }

    public function down()
    {
        $this->dropColumn($this->_tableName, 'payerName');
        $this->dropColumn($this->_tableName, 'paymentPurpose');
        $this->alterColumn($this->_tableName, 'payerAccount', "int(25) DEFAULT NULL");
    }
}
