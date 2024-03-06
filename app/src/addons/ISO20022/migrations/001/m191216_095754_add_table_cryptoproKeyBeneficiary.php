<?php

use yii\db\Migration;

/**
 * Class m191216_095754_add_table_cryptoproKeyBeneficiary
 */
class m191216_095754_add_table_cryptoproKeyBeneficiary extends Migration
{
    public function up()
    {
        $this->execute('DROP TABLE IF EXISTS cryptoproKeyBeneficiary');
	
	$this->execute("CREATE TABLE cryptoproKeyBeneficiary ( 
		keyId int(10) UNSIGNED, 
		terminalId varchar(12),
		PRIMARY KEY (keyId, terminalId)
	)");
    }

    public function down()
    {
        $this->execute("DROP TABLE IF EXISTS cryptoproKeyBeneficiary");

        return true;
    }    
}