<?php

use yii\db\Migration;

class m150709_112104_edm extends Migration
{
    public function up()
    {
		$this->execute('SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0');
		$this->execute('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0');

		$this->execute('DROP TABLE IF EXISTS `edmDictBank`');

		$this->execute("CREATE TABLE `edmDictBank` (
			`bik` char(9) NOT NULL,
			`okpo` char(9) DEFAULT NULL,
			`account` char(20) DEFAULT NULL,
			`name` varchar(255) DEFAULT NULL,
			`city` char(20) DEFAULT NULL,
			`address` varchar(255) DEFAULT NULL,
			`postalCode` char(6) DEFAULT NULL,
			`terminalId` char(12) DEFAULT NULL,
			PRIMARY KEY (`bik`),
			KEY `name` (`name`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8");

		$this->execute('DROP TABLE IF EXISTS `edmDictContractor`');

		$this->execute("CREATE TABLE `edmDictContractor` (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`bankBik` char(9) NOT NULL,
			`type` enum('INDIVIDUAL','JURISTIC') NOT NULL DEFAULT 'JURISTIC',
			`role` varchar(16) DEFAULT NULL,
			`kpp` char(9) NOT NULL,
			`inn` varchar(12) NOT NULL,
			`account` char(20) NOT NULL,
			`currency` char(3) NOT NULL DEFAULT 'RUR',
			`name` varchar(255) DEFAULT NULL,
			PRIMARY KEY (`id`),
			KEY `fk_rbsDictCorrespondent_rbsDictBank1` (`bankBik`),
			KEY `name` (`name`,`type`),
			KEY `account` (`account`,`type`),
			CONSTRAINT `fk_rbsDictCorrespondent_rbsDictBank1` FOREIGN KEY (`bankBik`) REFERENCES `edmDictBank` (`bik`) ON DELETE NO ACTION ON UPDATE NO ACTION
			) ENGINE=InnoDB DEFAULT CHARSET=utf8");

		$this->execute('DROP TABLE IF EXISTS `edmDictPaymentPurpose`');

		$this->execute("CREATE TABLE `edmDictPaymentPurpose` (
			`id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
			`value` varchar(180) NOT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8");

		$this->execute('SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS');
		$this->execute('SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS');

    }

    public function down()
    {
		$this->execute('SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0');
		$this->execute('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0');

		$this->execute('DROP TABLE IF EXISTS `edmDictBank`');
		$this->execute('DROP TABLE IF EXISTS `edmDictContractor`');
		$this->execute('DROP TABLE IF EXISTS `edmDictPaymentPurpose`');

		$this->execute('SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS');
		$this->execute('SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS');

        return true;
    }

}
