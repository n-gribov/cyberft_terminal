<?php

use yii\db\Schema;
use yii\db\Migration;

class m150730_092532_userAuthCertTable extends Migration
{
	/**
	 * @var string $userAuthCertTable User auth cert table name
	 */
	private $userAuthCertTable = 'user_auth_cert';

	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}

		$this->createTable($this->userAuthCertTable,
			[
			'id' => Schema::TYPE_PK,
			'userId' => Schema::TYPE_INTEGER. '(10) unsigned NOT NULL',
			'status' => "tinyint(1) DEFAULT '0' COMMENT 'Active status. 0 - inactive, 0 - active'",
			'name' => "varchar(64) DEFAULT NULL COMMENT 'Key name'",
			'publicKey' => Schema::TYPE_TEXT." DEFAULT NULL COMMENT 'Public key'",
			'certificate' => Schema::TYPE_TEXT." DEFAULT NULL COMMENT 'Certificate'",
			], $tableOptions);

		$this->createIndex('userId', $this->userAuthCertTable, 'userId');
	}

	public function down()
	{
		$this->dropTable($this->userAuthCertTable);
	}
}