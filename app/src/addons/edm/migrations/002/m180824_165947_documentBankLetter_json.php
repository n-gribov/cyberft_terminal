<?php

use yii\db\Migration;

class m180824_165947_documentBankLetter_json extends Migration
{
    public function up()
    {
        $this->execute('alter table `documentExtEdmBankLetter` add column `jsonData` text');
    }

    public function down()
    {
        $this->execute('alter table `documentExtEdmBankLetter` drop column `jsonData`');

        return true;
    }

}
