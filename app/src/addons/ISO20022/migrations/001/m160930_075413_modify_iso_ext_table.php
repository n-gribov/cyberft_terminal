<?php

use yii\db\Migration;

class m160930_075413_modify_iso_ext_table extends Migration
{
    public function up()
    {
        $this->addColumn('documentExtISO20022', 'errorCode', $this->string());
        $this->addColumn('documentExtISO20022', 'errorDescription', $this->string());
        $this->addColumn('documentExtISO20022', 'statusCode', $this->string());
    }

    public function down()
    {
        $this->dropColumn('documentExtISO20022', 'errorCode');
        $this->dropColumn('documentExtISO20022', 'errorDescription');
        $this->dropColumn('documentExtISO20022', 'statusCode');
        return true;
    }
}
