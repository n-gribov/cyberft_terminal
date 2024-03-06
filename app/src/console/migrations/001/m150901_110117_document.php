<?php

use yii\db\Schema;
use yii\db\Migration;

class m150901_110117_document extends Migration
{
    public function up()
    {
        $this->execute('DROP TABLE IF EXISTS `document`');

        $this->execute("CREATE TABLE `document` (
          `id` bigint(20) NOT NULL AUTO_INCREMENT,
          `direction` enum('', 'IN', 'OUT') DEFAULT '' COMMENT 'Message direction',
          `sender` varchar(32) NOT NULL COMMENT 'Sender ID',
          `receiver` varchar(32) NOT NULL COMMENT 'Receiver ID',
          `status` varchar(64) DEFAULT NULL COMMENT 'Status',
          `type` varchar(64) DEFAULT NULL COMMENT 'Document type',
          `typeGroup` varchar(64) DEFAULT NULL COMMENT 'Document group type',
          `origin` varchar(32) NOT NULL,
          `actualStoredFileId` bigint(20) DEFAULT '0' COMMENT 'Actual file (id in storage)',
          `uuid` varchar(64) DEFAULT NULL COMMENT 'Document uuid',
          `uuidReference` varchar(64) DEFAULT NULL COMMENT 'Referenced document uuid',
          `uuidRemote` varchar(64) DEFAULT NULL COMMENT 'Sender''s document uuid',
          `dateCreate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Message creation date',
          `dateUpdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Message update date',
          PRIMARY KEY (`id`),
          KEY `direction` (`direction`),
          KEY `typeGroup` (`typeGroup`),
          KEY `uuid` (`uuid`),
          KEY `uuidReference` (`uuidReference`),
          KEY `status` (`status`),
          KEY `dateCreate` (`dateCreate`)
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");
    }

    public function down()
    {
        $this->execute('DROP TABLE IF EXISTS `document`');

        return true;
    }
}
