<?php

use yii\db\Migration;

class m151229_132429_paymentOrderExt_update extends Migration
{
    public function up()
    {
        $this->execute('alter table `documentExtEdmPaymentOrder` add column `registerId` bigint unsigned after `documentId`');
    }

    public function down()
    {
        $this->execute('alter table `documentExtEdmPaymentOrder` drop column `registerId`');

        return true;
    }
}
