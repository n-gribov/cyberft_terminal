<?php

use yii\db\Migration;

class m160310_144524_paymentregister_update extends Migration
{
    public function up()
    {
        $this->execute(
            "alter table `edm_paymentRegister`
             add column `statusComment` varchar(255)"
        );
    }

    public function down()
    {
        $this->execute(
            "alter table `edm_paymentRegister`
             drop column `statusComment`"
        );

        return true;
    }
}
