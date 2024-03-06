<?php

use yii\db\Migration;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrder;
use addons\edm\models\PaymentOrder\PaymentOrderType;

class m160707_111507_modify_edm_payment_order extends Migration
{
    public function up()
    {

        $this->addColumn('edm_paymentOrder','beneficiaryCheckingAccount','varchar(30) AFTER `beneficiaryName`');

        // Перебор всех существующих документов, запись значений в новое поле
        $payRegPaymentOrders = PaymentRegisterPaymentOrder::find()->select('id,body')->all();

        foreach ($payRegPaymentOrders as $item) {
            $paymentOrder = (new PaymentOrderType)->loadFromString($item->body);

            $this->update('edm_paymentOrder',
                ['beneficiaryCheckingAccount' => $paymentOrder->beneficiaryCheckingAccount],
                ['id' => $item->id]
            );
        }

    }

    public function down()
    {
        $this->dropColumn('edm_paymentOrder','beneficiaryCheckingAccount');
        return true;
    }
}
