<?php

use yii\db\Migration;

class m160603_122735_modify_fileact_table extends Migration
{
    public function up()
    {
        $this->addColumn('fileact_CryptoproCert','validBefore',$this->date());
    }

    public function down()
    {
        $this->dropColumn('fileact_CryptoproCert','validBefore');
        return true;
    }

}
