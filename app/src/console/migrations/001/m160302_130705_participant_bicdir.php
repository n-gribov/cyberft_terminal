<?php

use yii\db\Migration;

class m160302_130705_participant_bicdir extends Migration
{
    public function up()
    {
        $this->execute(
            "CREATE TABLE `participant_BICDir`(
                `id` int unsigned not null,
                `participantBIC` varchar(12) not null primary key,
                `providerBIC` varchar(12) not null,
                `type` tinyint unsigned not null,
                `name` varchar(128) not null,
                `institutionName` varchar(128),
                `creditOrgFlag` tinyint unsigned,
                `status` tinyint not null,
                `blocked` tinyint not null,
                `countryCode` varchar(3),
                `city` varchar(128),
                `validFrom` timestamp null,
                `validBefore` timestamp null,
                `website` varchar(255),
                `phone` varchar(255),
                `lang` tinyint unsigned
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8"
        );
    }

    public function down()
    {
        $this->execute('drop table if exists `participant_BICDir`');

        return true;
    }
}
