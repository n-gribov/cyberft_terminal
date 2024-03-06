<?php

use yii\db\Schema;
use yii\db\Migration;

class m151006_123527_user_activate_key extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `user` ADD  `activateKey` VARCHAR(4) NOT NULL");
        $this->execute("ALTER TABLE `user` MODIFY `status` smallint(6) NOT NULL DEFAULT '20'");

        return true;
    }

    public function down()
    {
        $this->execute("ALTER TABLE `user` DROP  `activateKey`");
        $this->execute("ALTER TABLE `user` MODIFY `status` smallint(6) NOT NULL DEFAULT '10'");

        return true;
    }
}
