<?php

use yii\db\Migration;

class m160627_080101_modify_documentExtISO20022 extends Migration
{
    public function up()
    {
        $this->addColumn('documentExtISO20022','subject',$this->string(255));
        $this->addColumn('documentExtISO20022','descr',$this->string(255));
        $this->addColumn('documentExtISO20022','typeCode',$this->string(10));

        $this->execute('DROP TABLE IF EXISTS `documentExtISO20022Auth026`');
    }

    public function down()
    {
        $this->dropColumn('documentExtISO20022','subject');
        $this->dropColumn('documentExtISO20022','descr');
        $this->dropColumn('documentExtISO20022','typeCode');
    }
}
