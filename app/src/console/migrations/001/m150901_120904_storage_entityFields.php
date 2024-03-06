<?php

use yii\db\Schema;
use yii\db\Migration;

class m150901_120904_storage_entityFields extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE  `storage` ADD  `entity` VARCHAR( 62 ) NOT NULL AFTER  `id` ,
ADD INDEX (  `entity` )");
        $this->execute("ALTER TABLE  `storage` MODIFY  `entityId` bigint(20) DEFAULT NULL AFTER  `entity`");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `storage` DROP `entity`");

        return true;
    }
}
