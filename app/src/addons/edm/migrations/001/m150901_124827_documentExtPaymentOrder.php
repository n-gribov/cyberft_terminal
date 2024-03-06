<?php

use yii\db\Schema;
use yii\db\Migration;

class m150901_124827_documentExtPaymentOrder extends Migration
{
    public function up()
    {
        $this->execute('DROP TABLE IF EXISTS `documentExtPaymentOrder`');

        $this->execute("CREATE TABLE `documentExtPaymentOrder` (
          `id` bigint(20) NOT NULL AUTO_INCREMENT,
          `documentId` bigint(20) unsigned DEFAULT NULL,
          `number` smallint(5) unsigned DEFAULT NULL,
          `date` date DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `documentId` (`documentId`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");
    }

    public function down()
    {
        $this->execute('DROP TABLE IF EXISTS `documentExtPaymentOrder`');

        return true;
    }
}
