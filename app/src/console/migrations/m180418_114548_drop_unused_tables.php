<?php

use yii\db\Migration;

class m180418_114548_drop_unused_tables extends Migration
{
    public function up()
    {
        $this->execute('drop table if exists iso20022_source');
        $this->execute('drop table if exists swiftfin_document');
        $this->execute('drop table if exists finzip_document');
        $this->execute('drop table if exists fileact_document');
    }

    public function down()
    {
        return true;
    }

}
