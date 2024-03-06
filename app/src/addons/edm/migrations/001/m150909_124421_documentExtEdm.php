<?php

use yii\db\Schema;
use yii\db\Migration;

class m150909_124421_documentExtEdm extends Migration
{
    public function up()
    {
        $this->execute('DROP TABLE IF EXISTS `documentExtEdm`');

        $this->execute("CREATE TABLE `documentExtEdm` (
          `id` bigint(20) NOT NULL AUTO_INCREMENT,
          `documentId` bigint(20) unsigned DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `documentId` (`documentId`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");

        $this->execute("RENAME TABLE `documentExtPaymentOrder` TO  `documentExtEdmPaymentOrder`");
    }

    public function down()
    {
        $this->execute("DROP TABLE IF EXISTS `documentExtEdm`");
        $this->execute("RENAME TABLE `documentExtEdmPaymentOrder` TO  `documentExtPaymentOrder`");

        return true;
    }
}
