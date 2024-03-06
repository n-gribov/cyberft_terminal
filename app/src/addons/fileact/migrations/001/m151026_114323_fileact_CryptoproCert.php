<?php

use yii\db\Schema;
use yii\db\Migration;

class m151026_114323_fileact_CryptoproCert extends Migration
{
    private $_tableName = '{{%fileact_CryptoproCert}}';

    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable($this->_tableName,
            [
                'id' => Schema::TYPE_PK,
                'terminalId' => Schema::TYPE_STRING.' DEFAULT NULL',
                'ownerName' => Schema::TYPE_STRING.' DEFAULT NULL',
                'certData' => Schema::TYPE_TEXT.' DEFAULT NULL',
            ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable($this->_tableName);
    }
}
