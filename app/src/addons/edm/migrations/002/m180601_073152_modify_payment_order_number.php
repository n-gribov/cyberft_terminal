<?php

use yii\db\Migration;

class m180601_073152_modify_payment_order_number extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('edm_paymentOrder', 'number', $this->integer(10) . ' unsigned');

        return true;
    }

    public function safeDown()
    {
        $this->alterColumn('edm_paymentOrder', 'number', $this->smallInteger(5) . ' unsigned');

        return true;
    }
}
