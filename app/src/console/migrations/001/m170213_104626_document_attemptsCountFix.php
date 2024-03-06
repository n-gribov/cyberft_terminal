<?php

use yii\db\Migration;

class m170213_104626_document_attemptsCountFix extends Migration
{
    public function up()
    {
        $this->execute('alter table `document` modify column `attemptsCount` int unsigned not null default 0');
    }

    public function down()
    {
        $this->execute('alter table `document` modify column `attemptsCount` int unsigned null');

        return true;
    }
}
