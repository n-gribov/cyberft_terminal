<?php

use yii\db\Migration;

class m150916_140546_compoundConditions extends Migration
{
    public function up()
    {
		$this->execute('DROP TABLE IF EXISTS `compoundCondition`');
		$this->execute('CREATE TABLE `compoundCondition` (
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`serviceId` varchar(32) NOT NULL,
			`type` varchar(32) NOT NULL,
			`searchPath` varchar(255) NOT NULL,
			`body` text NOT NULL,
			PRIMARY KEY (`id`),
			KEY `serviceId` (`serviceId`,`type`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8');

    }

    public function down()
    {
		$this->execute('DROP TABLE IF EXISTS `compoundCondition`');
        return true;
    }

}
