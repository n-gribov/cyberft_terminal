<?php

use yii\db\Schema;
use yii\db\Migration;

class m150709_092552_cert extends Migration
{
    public function up()
    {
		$this->execute('SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0');
		$this->execute('SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0');

		$this->execute('DROP TABLE IF EXISTS `cert`');

		$this->execute("CREATE TABLE `cert` (
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`userId` int(10) unsigned DEFAULT NULL,
			`type` tinyint(4) NOT NULL DEFAULT '0',
			`validFrom` timestamp NULL DEFAULT NULL,
			`validBefore` timestamp NULL DEFAULT NULL,
			`useBefore` timestamp NULL DEFAULT NULL,
			`participantCode` char(4) NOT NULL,
			`countryCode` char(2) NOT NULL,
			`sevenSymbol` char(1) NOT NULL,
			`delimiter` char(1) NOT NULL,
			`terminalCode` char(1) DEFAULT NULL,
			`participantUnitCode` char(3) DEFAULT NULL,
			`fingerprint` varchar(64) NOT NULL COMMENT 'хэш сертификата',
			`status` varchar(16) DEFAULT NULL COMMENT 'Дополнительный статус сертификата',
			`fullName` varchar(255) DEFAULT NULL,
			`email` varchar(255) DEFAULT NULL,
			`phone` varchar(255) DEFAULT NULL,
			`post` varchar(255) DEFAULT NULL,
			`role` smallint(3) unsigned NOT NULL DEFAULT '0',
			`signAccess` enum('','ALL') NOT NULL DEFAULT 'ALL',
			`ownerId` int(10) unsigned DEFAULT NULL,
			`body` text NOT NULL,
			PRIMARY KEY (`id`),
			UNIQUE KEY `terminalId` (`countryCode`,`participantCode`,`sevenSymbol`,`delimiter`,`terminalCode`,`fingerprint`,`participantUnitCode`),
			KEY `userId` (`userId`),
			KEY `ownerId` (`ownerId`),
			KEY `fingerprint` (`fingerprint`),
			CONSTRAINT `userOwnCert` FOREIGN KEY (`ownerId`) REFERENCES `user` (`id`),
			CONSTRAINT `userUploadCert` FOREIGN KEY (`userId`) REFERENCES `user` (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8");

		$this->execute('SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS');
		$this->execute('SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS');

    }

    public function down()
    {
        $this->execute('DROP TABLE IF EXISTS `cert`');

        return true;
    }
}
