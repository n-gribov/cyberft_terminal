<?php

use yii\db\Schema;
use yii\db\Migration;

class m151030_105508_monitor_log extends Migration
{
    public function up()
    {
        $this->createTable('monitor_log', [
            'id' => Schema::TYPE_PK,
            'dateCreated' => Schema::TYPE_DATETIME,
            'eventCode'   => Schema::TYPE_STRING,
            'entity'   => Schema::TYPE_STRING,
            'entityId' => Schema::TYPE_INTEGER,
            'params'   => Schema::TYPE_TEXT,
        ],
        'ENGINE=MyISAM'
        );

        $this->createIndex('eventCode', 'monitor_log', ['eventCode']);
    }

    public function down()
    {
        $this->dropIndex('eventCode', 'monitor_log');
        $this->dropTable('monitor_log');

        return true;
    }
}
