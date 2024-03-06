<?php

use yii\db\Migration;

class m150929_121420_documentTable_addFileId_field extends Migration
{
    /**
     * Table name
     *
     * @var string $_tableName Table name
     */
    private $_tableName = '{{%document}}';

    public function up()
    {
        $this->addColumn($this->_tableName, 'fileId', "varchar(64) DEFAULT NULL COMMENT 'File ID for cftcp'");
    }

    public function down()
    {
        $this->dropColumn($this->_tableName, 'fileId');
    }
}
