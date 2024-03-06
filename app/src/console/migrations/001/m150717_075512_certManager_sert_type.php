<?php

use yii\db\Schema;
use yii\db\Migration;

class m150717_075512_certManager_sert_type extends Migration
{
	public function up()
	{
		$this->execute("ALTER TABLE  `cert` ADD  `certType` VARCHAR( 64 ) NOT NULL DEFAULT  'Undefined' AFTER  `type` ");
	}

	public function down()
	{
		$this->execute("ALTER TABLE  `cert` DROP  `CertType`");

		return false;
	}
}
