<?php

use yii\db\Migration;

class m170710_113400_add_fields_to_monitor_log extends Migration
{
    public function up()
    {
        $this->addColumn('monitor_log', 'ip', $this->string());
        $this->addColumn('monitor_log','terminalId', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('monitor_log', 'ip');
        $this->dropColumn('monitor_log','terminalId');
    }
}
