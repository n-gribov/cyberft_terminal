<?php

use yii\db\Migration;

class m150709_110811_swiftfin extends Migration
{
    public function up()
    {
		$this->execute('DROP TABLE IF EXISTS `swiftfin_templates`');

		$this->execute("CREATE TABLE `swiftfin_templates` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`docType` varchar(32) NOT NULL,
			`title` varchar(255) NOT NULL,
			`comment` text NOT NULL,
			`text` text NOT NULL,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Шаблоны SwiftFin'");
    }

    public function down()
    {
		$this->execute('DROP TABLE IF EXISTS `swiftfin_templates`');

        return true;
    }
}
