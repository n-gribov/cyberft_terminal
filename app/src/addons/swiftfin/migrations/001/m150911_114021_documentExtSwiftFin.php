<?php

use yii\db\Schema;
use yii\db\Migration;

class m150911_114021_documentExtSwiftFin extends Migration
{
    public function up()
    {
        $this->execute('DROP TABLE IF EXISTS `documentExtSwiftFin`');

        $this->execute("CREATE TABLE `documentExtSwiftFin` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `documentId` bigint(20) unsigned DEFAULT NULL,
              `operationReference` varchar(32) DEFAULT NULL,
              `nestedItemsCount` smallint(5) unsigned DEFAULT NULL,
              `sum` double(18,2) unsigned DEFAULT NULL,
              `currency` char(3) DEFAULT NULL,
              `date` timestamp NULL DEFAULT NULL,
              `absId` varchar(64) DEFAULT NULL COMMENT 'ABS ID',
              `valueDate` timestamp NULL DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `documentId` (`documentId`),
              KEY `operationReference` (`operationReference`),
              KEY `nestedItemsCount` (`nestedItemsCount`),
              KEY `date` (`date`),
              KEY `sum` (`sum`),
              KEY `valueDate` (`valueDate`),
              KEY `currency` (`currency`)
            ) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8");
    }

    public function down()
    {
        $this->execute("DROP TABLE IF EXISTS `documentExtSwiftFin`");

        return true;
    }
}
