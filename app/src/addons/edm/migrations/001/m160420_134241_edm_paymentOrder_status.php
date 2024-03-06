<?php

use yii\db\Migration;

class m160420_134241_edm_paymentOrder_status extends Migration
{
    public function up()
    {
        $this->execute("alter table `edm_paymentOrder` add column `businessStatus` varchar(4) not null");
        $this->execute("alter table `edm_paymentOrder` add column `businessStatusDescription` varchar(255) not null");
        $this->execute("alter table `edm_paymentRegister` add column `businessStatus` varchar(4) not null");
        $this->execute("alter table `edm_paymentRegister` add column `businessStatusDescription` varchar(255) not null");
    }

    public function down()
    {
        $this->execute("alter table `edm_paymentOrder` drop column `businessStatus`");
        $this->execute("alter table `edm_paymentOrder` drop column `businessStatusDescription`");
        $this->execute("alter table `edm_paymentRegister` drop column `businessStatus`");
        $this->execute("alter table `edm_paymentRegister` drop column `businessStatusDescription`");

        return true;
    }
}
