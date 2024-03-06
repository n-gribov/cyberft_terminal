<?php

use yii\db\Migration;

class m160325_133126_participant_remoteSenderId extends Migration
{
    public function up()
    {
        $this->execute("alter table `participant_BICDir` add column `remoteSenderId` varchar(35)");
    }

    public function down()
    {
        $this->execute("alter table `participant_BICDir` drop column `remoteSenderId`");

        return true;
    }

}
