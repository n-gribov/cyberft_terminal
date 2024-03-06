<?php

use yii\db\Migration;

class m160414_075611_storage_descriptor extends Migration
{
    public function up()
    {
        $this->execute("alter table `storage` add column `descriptor` varchar(64)");
    }

    public function down()
    {
        $this->execute("alter table `storage` drop column `descriptor`");

        return true;
    }

}
