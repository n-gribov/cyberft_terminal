<?php

use yii\db\Migration;

/**
 * Class m200507_210811_add_table_userAuthCertBeneficiary
 */
class m200507_210811_add_table_userAuthCertBeneficiary extends Migration
{
    public function up()
    {
        $this->execute('DROP TABLE IF EXISTS userAuthCertBeneficiary');
	
        $this->execute("CREATE TABLE userAuthCertBeneficiary ( 
            keyId int(10) UNSIGNED, 
            terminalId varchar(12),
            PRIMARY KEY (keyId, terminalId)
        )");
    }

    public function down()
    {
        $this->execute("DROP TABLE IF EXISTS userAuthCertBeneficiary");

        return true;
    }  
}
