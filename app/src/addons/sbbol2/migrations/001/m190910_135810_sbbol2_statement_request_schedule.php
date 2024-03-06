<?php

use yii\db\Migration;

/**
 * Class m190910_135810_sbbol2_statement_request_schedule
 */
class m190910_135810_sbbol2_statement_request_schedule extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('create table sbbol2_scheduled_request(
           id bigint unsigned not null auto_increment primary key,
           type varchar(64) not null,
           attempt tinyint unsigned not null default 0,
           maxAttempts tinyint unsigned not null default 1,
           status varchar(32) not null,
           dateCreate timestamp not null default CURRENT_TIMESTAMP,
           dateUpdate timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
           customerId int unsigned not null,
           requestJsonData text
        )');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute('drop table if exists sbbol2_scheduled_request');

        return true;
    }

}
