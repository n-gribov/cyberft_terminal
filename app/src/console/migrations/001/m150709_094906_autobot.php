<?php

use yii\db\Schema;
use yii\db\Migration;

class m150709_094906_autobot extends Migration
{
    public function up()
    {
		$this->execute('DROP TABLE IF EXISTS `autobot`');

		$this->execute("CREATE TABLE `autobot` (
			`id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
			`terminalId` varchar(12) NOT NULL COMMENT 'Terminal ID',
			`updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Updated at',
			`name` varchar(64) DEFAULT NULL COMMENT 'Key name',
			`primary` tinyint(1) DEFAULT '0' COMMENT 'Primary or additional key',
			`nominative` tinyint(1) DEFAULT '0' COMMENT 'Nominative key or not',
			`privateKey` text COMMENT 'Private key',
			`publicKey` text COMMENT 'Public key',
			`certificate` text COMMENT 'Certificate',
			`fingerprint` varchar(64) DEFAULT NULL COMMENT 'Fingerprint',
			`countryName` varchar(64) DEFAULT NULL COMMENT 'Country name',
			`stateOrProvinceName` varchar(64) DEFAULT NULL COMMENT 'State or province name',
			`localityName` varchar(64) DEFAULT NULL COMMENT 'Locality name',
			`organizationName` varchar(64) DEFAULT NULL COMMENT 'Organization name',
			`commonName` varchar(64) DEFAULT NULL COMMENT 'Common name',
			PRIMARY KEY (`id`),
			KEY `terminalId` (`terminalId`,`fingerprint`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    }

    public function down()
    {
		$this->execute('DROP TABLE IF EXISTS `autobot`');

        return true;
    }
}
