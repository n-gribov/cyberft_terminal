<?php

use yii\db\Schema;
use yii\db\Migration;

class m151109_120858_edm_registry extends Migration
{
    public function up()
    {
        $this->execute('DROP TABLE IF EXISTS `edm_paymentRegister`');

        $this->execute("CREATE TABLE `edm_paymentRegister` (
          `id` bigint(20) NOT NULL AUTO_INCREMENT,
          `accountId` bigint(20) unsigned DEFAULT NULL,
          `attachmentStorageId` bigint(20) unsigned DEFAULT NULL,
          `bodyStorageId` bigint(20) unsigned DEFAULT NULL,
          `sum` double DEFAULT NULL,
          `currency` varchar(4) DEFAULT NULL,
          `count` int(5) DEFAULT NULL,
          `sender` varchar(32) DEFAULT NULL,
          `status` varchar(100) DEFAULT NULL,
          `dateCreate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `dateUpdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          PRIMARY KEY (`id`),
          KEY `accountId` (`accountId`),
          KEY `attachmentStorageId` (`attachmentStorageId`),
          KEY `bodyStorageId` (`bodyStorageId`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");
    }

    public function down()
    {
        $this->execute("DROP TABLE IF EXISTS `edm_paymentRegister`");

        return true;
    }
}
