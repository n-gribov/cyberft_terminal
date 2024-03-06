<?php

use yii\db\Schema;
use yii\db\Migration;

class m150908_120404_paymentOrder_update extends Migration
{
    private $_tableName = 'documentExtPaymentOrder';

    public function up()
    {
        $this->addColumn($this->_tableName, 'sum', "double(20) DEFAULT NULL");
        $this->addColumn($this->_tableName, 'beneficiaryName', "varchar(255) DEFAULT NULL");
        $this->addColumn($this->_tableName, 'payerAccount', "int(25) DEFAULT NULL");
        $this->addColumn($this->_tableName, 'currency', "varchar(4) DEFAULT NULL");
    }

    public function down()
    {
        $this->dropColumn($this->_tableName, 'sum');
        $this->dropColumn($this->_tableName, 'beneficiaryName');
        $this->dropColumn($this->_tableName, 'payerAccount');
        $this->dropColumn($this->_tableName, 'currency');
    }
}
