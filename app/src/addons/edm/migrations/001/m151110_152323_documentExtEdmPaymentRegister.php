<?php

use yii\db\Schema;
use yii\db\Migration;

class m151110_152323_documentExtEdmPaymentRegister extends Migration
{
    public function up()
    {
        $this->execute('DROP TABLE IF EXISTS `documentExtEdmPaymentRegister`');

        $this->execute("CREATE TABLE `documentExtEdmPaymentRegister` (
          `id` bigint(20) NOT NULL AUTO_INCREMENT,
          `documentId` bigint(20) unsigned DEFAULT NULL,
          `registerId` bigint(20) unsigned DEFAULT NULL,
          `date` date DEFAULT NULL,
          `sum` double DEFAULT NULL,
          `count` int DEFAULT NULL,
          `comment` text DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `registerId` (`registerId`),
          KEY `documentId` (`documentId`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");
    }

    public function down()
    {
        $this->execute('DROP TABLE IF EXISTS `documentExtEdmPaymentRegister`');

        return true;
    }
}
