<?php

use yii\db\Migration;

class m200115_125138_create_edm_scheduled_request_previous_table extends Migration
{
    private $tableName = '{{%edm_scheduledRequestPrevious}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
	    'accountNumber' => $this->string()->notNull()->unique(),
	    'startTime'     => $this->time(),
	    'endTime'       => $this->time(),
	    'lastTime'      => $this->time(),
	    'currentDay'    => $this->date(),
	    'interval'      => $this->integer(),
	    'weekDays'      => $this->string()
        ]);
        $this->addPrimaryKey('pk_accountNumber', $this->tableName, 'accountNumber');
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
