<?php

use yii\db\Migration;

class m170215_125244_foreignCurrency_extStatus extends Migration
{
    public function up()
    {
        $this->execute(" alter table `documentExtEdmForeignCurrencyOperation` change column `status` `extStatus` varchar(64) not null");
    }

    public function down()
    {
        $this->execute(" alter table `documentExtEdmForeignCurrencyOperation` change column `extStatus` `status` varchar(64) not null");

        return true;
    }

}
