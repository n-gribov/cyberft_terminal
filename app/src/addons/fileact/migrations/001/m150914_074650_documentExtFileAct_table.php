<?php

use yii\db\Schema;
use yii\db\Migration;

class m150914_074650_documentExtFileAct_table extends Migration
{
    /**
     * Table name
     *
     * @var string $_tableName Table name
     */
    private $_tableName = '{{%documentExtFileAct}}';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable($this->_tableName,
            [
            'id'              => Schema::TYPE_PK,
            'documentId'      => Schema::TYPE_BIGINT.' unsigned DEFAULT NULL',
            'pduStoredFileId' => Schema::TYPE_BIGINT.' unsigned DEFAULT NULL',
            'binStoredFileId' => Schema::TYPE_BIGINT.' unsigned DEFAULT NULL',
            'zipStoredFileId' => Schema::TYPE_BIGINT.' unsigned DEFAULT NULL',
            ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable($this->_tableName);
    }
}