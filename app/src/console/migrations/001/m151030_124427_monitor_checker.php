<?php

use yii\db\Schema;
use yii\db\Migration;

class m151030_124427_monitor_checker extends Migration
{
     public function up()
    {
        $this->execute('drop table if exists `monitor_event`');

           $this->createTable('monitor_checker', [
               'id' => Schema::TYPE_PK,
               'code'   => Schema::TYPE_STRING,
               'active'   => Schema::TYPE_BOOLEAN,
               'settings'   => Schema::TYPE_TEXT,
			   'opData' => Schema::TYPE_TEXT,
               'checkTime'   => Schema::TYPE_INTEGER,
           ]);

           $this->createIndex('code', 'monitor_checker', ['code']);
    }

    public function down()
    {
        $this->dropIndex('code', 'monitor_checker');
        $this->dropTable('monitor_checker');

        return true;
    }
}
