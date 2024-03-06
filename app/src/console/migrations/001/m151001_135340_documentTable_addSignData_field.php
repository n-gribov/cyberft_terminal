<?php

use yii\db\Schema;
use yii\db\Migration;

class m151001_135340_documentTable_addSignData_field extends Migration
{
    /**
     * Table name
     *
     * @var string $_tableName Table name
     */
    private $_tableName = '{{%document}}';

    public function up()
    {
        $this->addColumn($this->_tableName, 'signData', Schema::TYPE_TEXT." COMMENT 'Document data for signature'");
    }

    public function down()
    {
        $this->dropColumn($this->_tableName, 'signData');
    }
}
