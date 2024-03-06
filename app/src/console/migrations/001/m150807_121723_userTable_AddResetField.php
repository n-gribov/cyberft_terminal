<?php

use yii\db\Migration;

class m150807_121723_userTable_AddResetField extends Migration
{
	/**
	 * User table name
	 *
	 * @var string $_tableName Table name
	 */
	private $_tableName = 'user';

	public function up()
    {
		$this->addColumn($this->_tableName, 'isReset', "tinyint(1) DEFAULT '0' COMMENT 'Is reset mark. 0 - normal, 1 - password was reset'");
    }

    public function down()
    {
        $this->dropColumn($this->_tableName, 'isReset');
    }
}
