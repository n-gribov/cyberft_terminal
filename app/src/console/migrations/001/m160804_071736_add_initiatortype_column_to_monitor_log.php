<?php

use yii\db\Migration;

class m160804_071736_add_initiatortype_column_to_monitor_log extends Migration
{
    public function up()
    {
        $this->addColumn('monitor_log', 'initiatorType', 'tinyint(3) unsigned after `userId`');
    }

    public function down()
    {
        $this->dropColumn('monitor_log', 'initiatorType');
        return true;
    }
}
