<?php

use yii\db\Migration;
use yii\db\Schema;

class m150903_103445_commandTable extends Migration
{
    /**
     * Table name
     *
     * @var string $_tableName Table name
     */
    private $_tableName = '{{%command}}';

    /**
     * Entity index name
     *
     * @var string $_indexEntity Entity index
     */
    private $_indexEntity = 'commandEntityEntityId';

    /**
     * User index name
     *
     * @var string $_indexUser User index
     */
    private $_indexUser = 'commandUser';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable($this->_tableName,
            [
            'id'           => Schema::TYPE_PK,
            'code'         => Schema::TYPE_STRING,
            'entity'       => Schema::TYPE_STRING.' NOT NULL',
            'entityId'     => Schema::TYPE_BIGINT.' NOT NULL',
            'acceptsCount' => 'tinyint(3) unsigned NOT NULL',
            'status'       => Schema::TYPE_STRING.' NOT NULL',
            'userId'       => Schema::TYPE_BIGINT.' unsigned DEFAULT NULL',
            'args'         => Schema::TYPE_TEXT,
            'result'       => Schema::TYPE_TEXT,
            'dateCreate'   => Schema::TYPE_TIMESTAMP." NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Command creation date'",
            'dateUpdate'   => Schema::TYPE_TIMESTAMP." NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Command update date'",
            ], $tableOptions);

        $this->createIndex($this->_indexEntity, $this->_tableName,
            ['entity', 'entityId']);
        $this->createIndex($this->_indexUser, $this->_tableName, 'userId');
    }

    public function down()
    {
        $this->dropIndex($this->_indexUser, $this->_tableName);
        $this->dropIndex($this->_indexEntity, $this->_tableName);
        $this->dropTable($this->_tableName);
    }
}
