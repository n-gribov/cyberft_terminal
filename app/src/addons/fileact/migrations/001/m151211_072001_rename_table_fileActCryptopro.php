<?php

use yii\db\Schema;
use yii\db\Migration;

class m151211_072001_rename_table_fileActCryptopro extends Migration
{
    private $newTableName = "cryptoproKeys";
    private $oldTableName = "fileact_CryptoproKeys";

    public function up()
    {
        $this->execute('RENAME TABLE '.$this->oldTableName.' TO '.$this->newTableName.';');
        return true;
    }

    public function down()
    {
        $this->execute('RENAME TABLE '.$this->newTableName.' TO '.$this->oldTableName.';');
        return true;
    }

}
