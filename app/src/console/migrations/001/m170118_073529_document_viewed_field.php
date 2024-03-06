<?php

use yii\db\Migration;

class m170118_073529_document_viewed_field extends Migration
{
    public function up()
    {
        $this->execute("alter table `document` add column `viewed` tinyint unsigned not null default 0");
        $this->execute("update `document` set `viewed` = 1");

    }

    public function down()
    {
        $this->execute("alter table `document` drop column `viewed`");

        return true;
    }

}
