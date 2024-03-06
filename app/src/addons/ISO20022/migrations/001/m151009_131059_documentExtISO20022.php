<?php

use yii\db\Schema;
use yii\db\Migration;

class m151009_131059_documentExtISO20022 extends Migration
{
    public function up()
    {
        $this->execute('DROP TABLE IF EXISTS `documentExtIso20022`');

        $this->execute("CREATE TABLE `documentExtISO20022` (
          `id` bigint(20) NOT NULL AUTO_INCREMENT,
          `documentId` bigint(20) unsigned DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `documentId` (`documentId`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");
    }

    public function down()
    {
        $this->execute("DROP TABLE IF EXISTS `documentExtIso20022`");

        return true;
    }
}
