<?php

use yii\db\Schema;
use yii\db\Migration;

class m151030_121711_user_failed_login_count extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'failedLoginCount', Schema::TYPE_SMALLINT . ' NOT NULL');
    }

    public function down()
    {
        $this->dropColumn('user', 'failedLoginCount');

        return true;
    }

}
