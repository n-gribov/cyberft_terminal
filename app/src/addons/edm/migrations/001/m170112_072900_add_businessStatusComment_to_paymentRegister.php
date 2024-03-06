<?php

use yii\db\Migration;

class m170112_072900_add_businessStatusComment_to_paymentRegister extends Migration
{
    public function up()
    {
        // Добавление нового поля в PaymentRegisterAR для детального описания бизнес-статуса
        // @CYB-3435
        //$this->addColumn('edm_paymentRegister', 'businessStatusComment', $this->text());

        // Такое же поле надо добавить в таблицу связанных с реестром платежных поручений
        $this->addColumn('edm_paymentOrder', 'businessStatusComment', $this->text());

    }

    public function down()
    {
        //$this->dropColumn('edm_paymentRegister', 'businessStatusComment');
        $this->dropColumn('edm_paymentOrder', 'businessStatusComment');
    }

}
