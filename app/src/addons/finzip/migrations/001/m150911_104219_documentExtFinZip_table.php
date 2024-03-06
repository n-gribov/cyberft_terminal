<?php

use yii\db\Schema;
use yii\db\Migration;

class m150911_104219_documentExtFinZip_table extends Migration
{
    /**
     * Table name
     *
     * @var string $_tableName Table name
     */
    private $_tableName = '{{%documentExtFinZip}}';

    /**
     * File count index
     *
     * @var string $_indexFileCount File count index
     */
    private $_indexFileCount = 'documentExtFinZipFileCount';

    /**
     * Subject index
     *
     * @var string $_indexSubject Subject index
     */
    private $_indexSubject = 'documentExtFinZipSubject';

    /**
     * Descr index
     *
     * @var string $_indexDescr Descr index
     */
    private $_indexDescr = 'documentExtFinZipDescr';

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
            'zipStoredFileId' => Schema::TYPE_BIGINT.' unsigned DEFAULT NULL',
            'fileCount'       => Schema::TYPE_INTEGER.' unsigned DEFAULT NULL',
            'subject'         => 'varchar(255) DEFAULT NULL',
            'descr'           => 'varchar(255) DEFAULT NULL',
            ], $tableOptions);

        $this->createIndex($this->_indexFileCount, $this->_tableName,
            'fileCount');

        $this->createIndex($this->_indexSubject, $this->_tableName, 'subject');

        $this->createIndex($this->_indexDescr, $this->_tableName, 'descr');
    }

    public function down()
    {
        $this->dropIndex($this->_indexDescr, $this->_tableName);
        $this->dropIndex($this->_indexSubject, $this->_tableName);
        $this->dropIndex($this->_indexFileCount, $this->_tableName);
        $this->dropTable($this->_tableName);
    }
}