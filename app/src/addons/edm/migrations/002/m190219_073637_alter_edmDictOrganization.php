<?php

use yii\db\Migration;

class m190219_073637_alter_edmDictOrganization extends Migration
{

    private $_tableName = 'edmDictOrganization';

    public function up()
    {
        $this->alterColumn($this->_tableName, 'locationLatin', $this->string(33));
        $this->alterColumn($this->_tableName, 'nameLatin', $this->string(33));
        $this->alterColumn($this->_tableName, 'addressLatin', $this->string(33));
    }

    public function down()
    {
        echo "m190219_073637_alter_edmDictOrganization cannot be reverted.\n";

        return true;
    }

}
