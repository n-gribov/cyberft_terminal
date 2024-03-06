<?php

use yii\db\Schema;
use yii\db\Migration;

class m150917_131423_documentExtEdmPaymentOrder_addDueColumns extends Migration
{
    /**
     * Table name
     *
     * @var string $_tableName Table name
     */
    private $_tableName = '{{%documentExtEdmPaymentOrder}}';

    public function up()
    {
        $this->addColumn($this->_tableName, 'dateProcessing',
            Schema::TYPE_TIMESTAMP);
        $this->addColumn($this->_tableName, 'dateDue', Schema::TYPE_TIMESTAMP);
    }

    public function down()
    {
        $this->dropColumn($this->_tableName, 'dateDue');
        $this->dropColumn($this->_tableName, 'dateProcessing');
    }
}