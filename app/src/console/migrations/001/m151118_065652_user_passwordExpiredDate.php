<?php

use yii\db\Schema;
use yii\db\Migration;

class m151118_065652_user_passwordExpiredDate extends Migration
{
    private $_tableName = 'user';

    public function up()
    {
        $this->addColumn($this->_tableName, 'passwordUpdateDate', "datetime DEFAULT 0");
    }

    public function down()
    {
        $this->dropColumn($this->_tableName, 'passwordUpdateDate');
    }
}
