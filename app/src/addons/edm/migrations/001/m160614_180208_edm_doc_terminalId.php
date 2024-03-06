<?php

//use addons\edm\models\PaymentRegister\PaymentRegisterAR;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrder;
use common\models\Terminal;
use yii\db\Migration;

class m160614_180208_edm_doc_terminalId extends Migration
{
    public function up()
    {
        $this->execute("alter table `edm_paymentOrder` add column `terminalId` int unsigned null");
        //$this->execute("alter table `edm_paymentRegister` add column `terminalId` int unsigned null");

        $terminal = Terminal::getDefaultTerminal();
        if (!empty($terminal)) {
            PaymentRegisterPaymentOrder::updateAll(['terminalId' => $terminal->id]);
            //PaymentRegisterAR::updateAll(['terminalId' => $terminal->id]);
        }

        return true;
    }

    public function down()
    {
        $this->execute("alter table `edm_paymentOrder` drop column `terminalId`");
        //$this->execute("alter table `edm_paymentRegister` drop column `terminalId`");

        return true;
    }

}
