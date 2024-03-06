<?php

use yii\db\Schema;
use yii\db\Migration;

class m171016_130317_create_riaffeisen_userExt extends Migration
{
    private $tableName = '{{%raiffeisen_userExt}}';

    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_PK,
                'userId' => Schema::TYPE_BIGINT . ' unsigned DEFAULT NULL',
                'canAccess' => Schema::TYPE_SMALLINT . ' unsigned DEFAULT NULL',
                'permissionsData' => Schema::TYPE_TEXT,
            ],
            $tableOptions
        );
    }

    public function down()
    {
        $this->dropTable($this->tableName);

        return true;
    }
}
