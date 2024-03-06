<?php

use yii\db\Schema;
use yii\db\Migration;

class m150709_110253_user extends Migration
{
    public function up()
    {
		$this->execute('SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0');
		$this->execute('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0');

		$this->execute('DROP TABLE IF EXISTS `user`');

		$this->execute("CREATE TABLE `user` (
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`email` varchar(45) NOT NULL,
			`name` varchar(45) NOT NULL,
			`auth_key` varchar(32) NOT NULL,
			`password_hash` varchar(255) NOT NULL,
			`password_reset_token` varchar(255) DEFAULT NULL,
			`role` smallint(6) NOT NULL DEFAULT '10',
			`signerLevel` tinyint(3) unsigned DEFAULT NULL,
			`status` smallint(6) NOT NULL DEFAULT '10',
			`created_at` int(11) NOT NULL,
			`updated_at` int(11) NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8");

		$this->execute('SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS');
		$this->execute('SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS');

    }

    public function down()
    {
		$this->execute('SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0');
		$this->execute('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0');
		$this->execute('DROP TABLE IF EXISTS `user`');
		$this->execute('SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS');
		$this->execute('SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS');

		return true;
    }
}
