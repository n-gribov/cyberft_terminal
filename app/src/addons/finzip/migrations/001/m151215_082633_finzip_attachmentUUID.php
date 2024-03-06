<?php

use yii\db\Schema;
use yii\db\Migration;

class m151215_082633_finzip_attachmentUUID extends Migration
{
    public function up()
    {
        $this->addColumn('documentExtFinZip', 'attachmentUUID', 'varchar(64) null');
    }

    public function down()
    {
        $this->dropColumn('documentExtFinZip', 'attachmentUUID');
        return true;
    }

}
