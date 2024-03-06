<?php

use yii\db\Schema;
use yii\db\Migration;

class m150909_071757_document_upgrade extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE  `document` ADD  `signaturesRequired` tinyint(3) unsigned NOT NULL AFTER `uuidRemote`,
ADD INDEX (  `signaturesRequired` )");
        $this->execute("ALTER TABLE  `document` ADD  `signaturesCount` tinyint(3) unsigned NOT NULL AFTER `signaturesRequired`, ADD INDEX (  `signaturesCount` )");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `storage` DROP `signaturesRequired`");
        $this->execute("ALTER TABLE `storage` DROP `signaturesCount`");

        return true;
    }
}
