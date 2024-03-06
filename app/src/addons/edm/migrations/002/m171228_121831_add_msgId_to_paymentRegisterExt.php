<?php

use yii\db\Migration;

class m171228_121831_add_msgId_to_paymentRegisterExt extends Migration
{
    public function up()
    {
        $this->addColumn('documentExtEdmPaymentRegister', 'msgId', $this->string(64));
    }

    public function down()
    {
        $this->dropColumn('documentExtEdmPaymentRegister', 'msgId');
    }
}
