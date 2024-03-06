<?php

use yii\db\Migration;

class m161121_154710_state extends Migration
{
    public function up()
    {
        $this->execute(
            'create table state('
                . 'documentId bigint unsigned not null primary key'
                . ', dateRetry timestamp not null default CURRENT_TIMESTAMP'
                . ', code varchar(128) not null'
                . ', terminalId varchar(16) not null'
                . ', status varchar(32) not null'
                . ', data text)'
        );

        $this->execute('alter table state add key terminalId(terminalId)');
        $this->execute('alter table state add key dateRetry(dateRetry)');

    }

    public function down()
    {
        $this->execute('drop table if exists state');

        return true;
    }

}
