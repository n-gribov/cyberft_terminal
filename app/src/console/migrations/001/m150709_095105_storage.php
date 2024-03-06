<?php

use yii\db\Schema;
use yii\db\Migration;

class m150709_095105_storage extends Migration
{
    public function up()
    {
		$this->execute('DROP TABLE IF EXISTS `storage`');

		$this->execute("CREATE TABLE `storage` (
			`id` bigint(20) NOT NULL AUTO_INCREMENT,
			`path` varchar(255) DEFAULT NULL,
			`originalFilename` varchar(255) DEFAULT NULL,
			`dateCreate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			`groupKey` varchar(64) DEFAULT NULL,
			`serviceId` varchar(64) DEFAULT NULL,
			`resourceId` varchar(64) DEFAULT NULL,
			`size` int(11) DEFAULT NULL,
			PRIMARY KEY (`id`),
			KEY `groupKey` (`groupKey`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    }

    public function down()
    {
        $this->execute('DROP TABLE IF EXISTS `storage`');

        return true;
    }
}
