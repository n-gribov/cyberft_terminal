<?php

use yii\db\Schema;
use yii\db\Migration;

class m150709_105403_participantTables extends Migration
{
    public function up()
    {
		$this->execute('DROP TABLE IF EXISTS `participant`');

		$this->execute("CREATE TABLE `participant` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`address` varchar(16) DEFAULT NULL,
			`info` text,
			PRIMARY KEY (`id`),
			UNIQUE KEY `address_UNIQUE` (`address`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8");

		$this->execute('DROP TABLE IF EXISTS `participant_autobots`');

		$this->execute("CREATE TABLE `participant_autobots` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`terminal_id` varchar(12) NOT NULL,
			`participant_id` varchar(12) NOT NULL,
			`autobot_id` int(11) NOT NULL,
			PRIMARY KEY (`id`),
			KEY `terminal_id` (`terminal_id`,`participant_id`),
			KEY `autobot_id` (`autobot_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Настройки подписания сертификатов автоботами'");
    }

    public function down()
    {
		$this->execute('DROP TABLE IF EXISTS `participant`');
		$this->execute('DROP TABLE IF EXISTS `participant_autobots`');

        return true;
    }
}
