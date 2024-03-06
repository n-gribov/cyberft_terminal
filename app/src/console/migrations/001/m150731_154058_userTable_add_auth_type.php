<?php

use yii\db\Migration;

class m150731_154058_userTable_add_auth_type extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE  `user` ADD  `authType` tinyint(1) DEFAULT '0' COMMENT 'Auth type. 0 - Password, 1 - Key'");
    }

    public function down()
    {
        $this->execute("ALTER TABLE  `user` DROP  `authType`");

		return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
