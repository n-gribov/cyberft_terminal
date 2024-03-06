<?php

use yii\db\Migration;

class m151215_180559_iso20022extstatus extends Migration
{
    public function up()
    {
        $this->addColumn('documentExtISO20022', 'extStatus', 'varchar(32)');
    }

    public function down()
    {
        $this->dropColumn('documentExtISO20022', 'extStatus');

        return true;
    }
    
}
