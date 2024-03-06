<?php

use yii\db\Migration;

class m180228_133705_add_extStatus_and_fileName_documentExtEdmBankLetter extends Migration
{
    private $tableName = '{{%documentExtEdmBankLetter}}';

    public function up()
    {
        $this->addColumn($this->tableName, 'extStatus', $this->string(32));
        $this->addColumn($this->tableName, 'fileName', $this->string(255));
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'extStatus');
        $this->dropColumn($this->tableName, 'fileName');
    }
}
