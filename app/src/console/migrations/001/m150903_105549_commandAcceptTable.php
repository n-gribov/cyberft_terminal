<?php

use yii\db\Migration;
use yii\db\Schema;

class m150903_105549_commandAcceptTable extends Migration
{
    /**
     * Table name
     *
     * @var string $_tableName Table name
     */
    private $_tableName = '{{%command_accept}}';

    /**
     * User and command index
     *
     * @var string $_indexUserCommand User and command index
     */
    private $_indexUserCommand = 'commandAcceptUserCommand';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable($this->_tableName,
            [
            'id'           => Schema::TYPE_PK,
            'userId'       => Schema::TYPE_BIGINT.' unsigned NOT NULL',
            'commandId'    => Schema::TYPE_BIGINT.' unsigned NOT NULL',
            'acceptResult' => Schema::TYPE_STRING.' NOT NULL',
            'data'         => Schema::TYPE_TEXT,
            'dateCreate'   => Schema::TYPE_TIMESTAMP.' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            ], $tableOptions);

        $this->createIndex($this->_indexUserCommand, $this->_tableName,
            ['userId', 'commandId']);
    }

    public function down()
    {
        $this->dropIndex($this->_indexUserCommand, $this->_tableName);
        $this->dropTable($this->_tableName);
    }
}
