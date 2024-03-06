<?php

use yii\db\Schema;
use yii\db\Migration;

class m151030_144241_monitor_log_datecreate extends Migration
{
    public function up()
    {
        $this->execute(
            'ALTER TABLE `monitor_log` MODIFY COLUMN `dateCreated` int NULL'
        );
    }

    public function down()
    {
        $this->execute('ALTER TABLE `monitor_log` MODIFY COLUMN `dateCreated` DATETIME NOT NULL');

        return true;
    }
}
