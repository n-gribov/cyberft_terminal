<?php

use yii\db\Schema;
use yii\db\Migration;

class m150910_094738_document_attemptsStatus extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `document` ADD `attemptsCount` int(11) DEFAULT NULL AFTER `signaturesCount`");
        $this->execute("ALTER TABLE `document` ADD `statusJob` varchar(32) DEFAULT NULL AFTER `attemptsCount`");
        $this->execute("ALTER TABLE `document` ADD `statusDate` datetime DEFAULT NULL AFTER `statusJob`");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `document` DROP `attemptsCount`");
        $this->execute("ALTER TABLE `document` DROP `statusJob`");
        $this->execute("ALTER TABLE `document` DROP `statusDate`");

        return true;
    }
}
