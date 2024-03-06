<?php

use yii\db\Migration;

class m170206_125114_user_enableTerminalSelect extends Migration
{
    public function up()
    {
        $this->execute("alter table `user` add column `disableTerminalSelect` tinyint unsigned not null default 0");
    }

    public function down()
    {
        $this->execute("alter table `user` drop column `disableTerminalSelect`");

        return true;
    }

}
