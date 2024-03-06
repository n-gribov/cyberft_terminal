<?php

use yii\db\Schema;
use yii\db\Migration;

class m151012_075835_swiftfin_authorization extends Migration
{
    public function up()
    {
        $this->execute('alter table `documentExtSwiftFin` add column `extStatus` varchar(32) not null after nestedItemsCount');
		$this->execute('alter table `swiftfin_UserExt` add column `role` varchar(32) not null');
		$this->execute('CREATE TABLE `swiftfin_userExtAuthorization` (
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`userExtId` int(10) unsigned NOT NULL,
			`docType` varchar(32) NOT NULL,
			`currency` varchar(8) NOT NULL,
			`minSum` bigint(20) unsigned NOT NULL,
			`maxSum` bigint(20) unsigned NOT NULL,
			PRIMARY KEY (`id`),
			KEY `userExtId` (`userExtId`)
		) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->execute('alter table `documentExtSwiftFin` drop column `extStatus`');
		$this->execute('alter table `swiftfin_UserExt` drop column `role`');
		$this->execute('drop table `swiftfin_UserExtAuthorization`');
        return true;
    }
}
