<?php

use yii\db\Schema;
use yii\db\Migration;

class m151223_074235_paymentRegister_signing extends Migration
{
    public function up()
    {
        $this->addColumn('edm_paymentRegister', 'signaturesRequired', Schema::TYPE_SMALLINT);
        $this->addColumn('edm_paymentRegister', 'signaturesCount', Schema::TYPE_SMALLINT);
    }

    public function down()
    {
        $this->dropColumn('edm_paymentRegister', 'signaturesRequired');
        $this->dropColumn('edm_paymentRegister', 'signaturesCount');

        return true;
    }
}
