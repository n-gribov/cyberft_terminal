<?php
use yii\db\Migration;

class m160126_153021_swiftfin_dictBank extends Migration
{
    public function up()
    {
        $this->execute('DROP TABLE IF EXISTS `swiftfin_dictBank`');
        $this->execute(
            "CREATE TABLE `swiftfin_dictBank` (
                `swiftCode` char(8) NOT NULL,
                `branchCode` char(3) NOT NULL,
                `name` varchar(64) DEFAULT NULL,
                `address` varchar(128) DEFAULT NULL,
                UNIQUE KEY `swiftCode` (`swiftCode`,`branchCode`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8"
        );
    }

    public function down()
    {
        $this->execute('DROP TABLE IF EXISTS `swiftfin_dictBank`');

        return true;
    }
}
