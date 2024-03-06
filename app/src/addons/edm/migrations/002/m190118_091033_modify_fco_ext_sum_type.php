<?php

use yii\db\Migration;

class m190118_091033_modify_fco_ext_sum_type extends Migration
{
    //CYB-4238 В базе не сохранялась сумма в рублях больше, чем 99 999 999.99, в отличие от суммы в валюте
    
    private $tableName = "documentExtEdmForeignCurrencyOperation";
    
    public function up()
    {        
        $this->alterColumn($this->tableName, 'sum', $this->decimal(18, 2));
        return true;
    }

    public function down()
    {        
        $this->alterColumn($this->tableName, 'sum', $this->decimal(10, 2));
        return true;
    }

}
