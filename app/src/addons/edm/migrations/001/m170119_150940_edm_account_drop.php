<?php

use yii\db\Migration;

class m170119_150940_edm_account_drop extends Migration
{
    public function up()
    {
        $this->execute('drop table if exists `edm_account`');
    }

    public function down()
    {
        echo "m170119_150940_edm_account_drop cannot be reverted.\n";
        echo "use migration m151111_144422_edm_account to restore the table if needed.\n";

        return true;
    }

}
