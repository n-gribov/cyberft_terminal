<?php

use yii\db\Migration;

class m160204_081816_cryptopro_keys_update extends Migration
{
    public function up()
    {
        $this->execute("alter table `cryptoproKeys` add column `serialNumber` varchar(255) after `keyId`");
        $this->execute("alter table `cryptoproKeys` add key(`serialNumber`)");
    }

    public function down()
    {
        $this->execute("alter table `cryptoproKeys` drop column `serialNumber`");

        return true;
    }
}