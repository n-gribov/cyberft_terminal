<?php

use yii\db\Migration;

class m180313_105059_add_message_to_documentExtEdmBankLetter extends Migration
{
    private $tableName = '{{%documentExtEdmBankLetter}}';

    public function up()
    {
        $this->addColumn($this->tableName, 'message', $this->text());
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'message');
    }
}
