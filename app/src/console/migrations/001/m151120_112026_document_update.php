<?php

use yii\db\Migration;

class m151120_112026_document_update extends Migration
{
    public function up()
    {
		$this->execute('ALTER TABLE `document` MODIFY COLUMN `signaturesRequired` tinyint unsigned NOT NULL DEFAULT 0');
		$this->execute('ALTER TABLE `document` MODIFY COLUMN `signaturesCount` tinyint unsigned NOT NULL DEFAULT 0');
		$this->execute('ALTER TABLE `document` MODIFY COLUMN `origin` varchar(32) NULL');
		$this->execute("ALTER TABLE `document` MODIFY COLUMN `actualStoredFileId` bigint(20) unsigned NULL COMMENT 'Actual file (id in storage)'");
		$this->execute("ALTER TABLE `document` MODIFY COLUMN `encryptedStoredFileId` bigint(20) unsigned NULL COMMENT 'Encrypted file (id in storage)'");
		$this->execute("ALTER TABLE `document` MODIFY COLUMN `sender` varchar(32) NULL COMMENT 'Sender ID'");
        $this->execute("ALTER TABLE `document` MODIFY COLUMN `receiver` varchar(32) NULL COMMENT 'Receiver ID'");
    }

    public function down()
    {
        $this->execute('ALTER TABLE `document` MODIFY COLUMN `signaturesRequired` tinyint unsigned NOT NULL');
        $this->execute('ALTER TABLE `document` MODIFY COLUMN `signaturesCount` tinyint unsigned NOT NULL');
		$this->execute('ALTER TABLE `document` MODIFY COLUMN `origin` varchar(32) NOT NULL');
		$this->execute("ALTER TABLE `document` MODIFY COLUMN `actualStoredFileId` bigint(20) DEFAULT '0' COMMENT 'Actual file (id in storage)'");
		$this->execute("ALTER TABLE `document` MODIFY COLUMN `encryptedStoredFileId` bigint(20) unsigned NOT NULL");
        $this->execute("ALTER TABLE `document` MODIFY COLUMN `sender` varchar(32) NOT NULL COMMENT 'Sender ID'");
        $this->execute("ALTER TABLE `document` MODIFY COLUMN `receiver` varchar(32) NOT NULL COMMENT 'Receiver ID'");

        return true;
    }
}
