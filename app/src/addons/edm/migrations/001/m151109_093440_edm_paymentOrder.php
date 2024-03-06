<?php

use yii\db\Schema;
use yii\db\Migration;

class m151109_093440_edm_paymentOrder extends Migration
{
    public function up()
    {
        $this->execute('DROP TABLE IF EXISTS `edm_paymentOrder`');

        $this->execute("CREATE TABLE `edm_paymentOrder` (
          `id` bigint(20) NOT NULL AUTO_INCREMENT,
          `registerId` bigint(20) unsigned DEFAULT NULL,
          `body` text DEFAULT NULL,
          `number` smallint(5) unsigned DEFAULT NULL,
          `date` date DEFAULT NULL,
          `sum` double DEFAULT NULL,
          `beneficiaryName` varchar(255) DEFAULT NULL,
          `payerAccount` varchar(30) DEFAULT NULL,
          `currency` varchar(4) DEFAULT NULL,
          `payerName` varchar(255) DEFAULT NULL,
          `paymentPurpose` varchar(255) DEFAULT NULL,
          `dateProcessing` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          `dateDue` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          PRIMARY KEY (`id`),
          KEY `registerId` (`registerId`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");
    }

    public function down()
    {
        $this->execute("DROP TABLE IF EXISTS `edm_paymentOrder`");

        return true;
    }
}
