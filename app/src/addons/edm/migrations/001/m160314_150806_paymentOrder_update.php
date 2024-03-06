<?php

use yii\db\Migration;

class m160314_150806_paymentOrder_update extends Migration
{
    public function up()
    {
        $this->execute(
            "ALTER TABLE `edm_paymentOrder` ADD COLUMN `status` varchar(32) not null default ''"
        );

        $this->execute(
            "ALTER TABLE `edm_paymentOrder` ADD KEY(`status`)"
        );
    }

    public function down()
    {
        $this->execute(
            "ALTER TABLE `edm_paymentOrder` DROP COLUMN `status`"
        );

        return true;
    }

}
