<?php

use yii\db\Migration;

class m160426_121027_edm_paymentOrder_fixdates extends Migration
{
    public function up()
    {
        $this->execute("alter table `edm_paymentOrder` modify column `dateProcessing` timestamp null default null");
        $this->execute("alter table `edm_paymentOrder` modify column `dateDue` timestamp null default null");
        $this->execute("update `edm_paymentOrder` set `dateDue` = null where `dateDue` = '0000-00-00 00:00:00'");
    }

    public function down()
    {
        $this->execute("update `edm_paymentOrder` set `dateDue` = '0000-00-00 00:00:00' where `dateDue` is null");
        $this->execute("alter table `edm_paymentOrder` modify column `dateProcessing` timestamp not null default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP");
        $this->execute("alter table `edm_paymentOrder` modify column `dateDue` timestamp not null default '0000-00-00 00:00:00'");

        return true;
    }

}
