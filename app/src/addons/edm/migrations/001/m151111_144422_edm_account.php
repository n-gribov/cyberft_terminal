<?php

use yii\db\Schema;
use yii\db\Migration;

class m151111_144422_edm_account extends Migration
{
    public function up()
    {
		$this->execute("CREATE TABLE `edm_account` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`number` varchar(32) NOT NULL,
				`validFrom` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`validTill` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				`currency` varchar(8) NOT NULL,
				`balance` decimal(10,0) DEFAULT NULL,
				`expectedBalance` decimal(10,0) DEFAULT NULL,
				`dateActualized` timestamp NULL,
				`bankBik` char(9) DEFAULT NULL,
				`active` tinyint(4) NOT NULL,
				`title` varchar(255) DEFAULT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `number` (`number`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8"
		);
    }

    public function down()
    {
        $this->execute('DROP TABLE IF EXISTS edm_account');

        return true;
    }
}
