<?php

use yii\db\Migration;

class m170118_073656_monitor_log_id extends Migration
{
    public function up()
    {
        $this->execute("alter table `monitor_log` modify column `id` bigint unsigned not null auto_increment");
        $this->execute("alter table `monitor_log` modify column `entityId` bigint unsigned");
        $this->execute("alter table `monitor_log` modify column `eventCode` varchar(64)");
        $this->execute("alter table `monitor_log` modify column `entity` varchar(64)");
    }

    public function down()
    {
        $this->execute("alter table `monitor_log` modify column `id` int unsigned not null auto_increment");
        $this->execute("alter table `monitor_log` modify column `entityId` int unsigned");
        $this->execute("alter table `monitor_log` modify column `eventCode` varchar(255)");
        $this->execute("alter table `monitor_log` modify column `entity` varchar(255)");

        return true;
    }

}
