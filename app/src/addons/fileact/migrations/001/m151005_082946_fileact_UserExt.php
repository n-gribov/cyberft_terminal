<?php

use yii\db\Schema;
use yii\db\Migration;

class m151005_082946_fileact_UserExt extends Migration
{
    private $_tableName = '{{%fileact_UserExt}}';

    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable($this->_tableName,
            [
                'id' => Schema::TYPE_PK,
                'userId' => Schema::TYPE_BIGINT.' unsigned DEFAULT NULL',
                'canAccess' => Schema::TYPE_SMALLINT.' unsigned DEFAULT NULL',
            ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable($this->_tableName);
    }
}
