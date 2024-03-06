<?php

use yii\db\Schema;
use yii\db\Migration;

class m151002_074144_docEncryptedFileId extends Migration
{
    private $_tableName = '{{%document}}';

    public function up()
    {
        $this->addColumn($this->_tableName, 'encryptedStoredFileId', "bigint unsigned after actualStoredFileId");
    }

    public function down()
    {
        $this->dropColumn($this->_tableName, 'encryptedStoredFileId');
		return true;
    }
}
