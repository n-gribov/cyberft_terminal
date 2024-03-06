<?php

use yii\db\Schema;
use yii\db\Migration;

class m150925_130134_documentExtEdmStatement extends Migration
{
    public function up()
    {
        $this->execute('DROP TABLE IF EXISTS `documentExtEdmStatement`');

        $this->execute("CREATE TABLE `documentExtEdmStatement` (
          `id` bigint(20) NOT NULL AUTO_INCREMENT,
          `documentId` bigint(20) unsigned DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `documentId` (`documentId`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");
    }

    public function down()
    {
        $this->execute("DROP TABLE IF EXISTS `documentExtEdmStatement`");

        return true;
    }
}
