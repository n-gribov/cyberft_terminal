<?php

use yii\db\Migration;

class m160603_075627_modify_isocrypto_table extends Migration
{
    public function up()
    {
        $this->addColumn('iso20022_CryptoproCert','validBefore',$this->date());
    }

    public function down()
    {
        $this->dropColumn('iso20022_CryptoproCert','validBefore');
        return true;
    }

}
