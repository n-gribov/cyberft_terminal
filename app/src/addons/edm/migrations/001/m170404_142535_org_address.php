<?php

use yii\db\Migration;

class m170404_142535_org_address extends Migration
{
    public function up()
    {
        $this->execute('alter table `edmDictOrganization` add column `address` varchar(255) not null default \'\'');
    }

    public function down()
    {
        $this->execute('alter table `edmDictOrganization` drop column `address`');

        return true;
    }

}
