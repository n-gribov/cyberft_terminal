<?php

use yii\db\Schema;
use yii\db\Migration;

class m151113_195834_documentExtEdmStatementRequest extends Migration
{
    public function up()
    {
		$this->execute("CREATE TABLE `documentExtEdmStatementRequest` (
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`documentId` int(10) unsigned NOT NULL,
			`accountNumber` varchar(32) NOT NULL,
			`startDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`endDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB");
    }

    public function down()
    {
        $this->execute('DROP TABLE IF EXISTS `documentExtEdmStatementRequest`');

        return true;
    }
}
