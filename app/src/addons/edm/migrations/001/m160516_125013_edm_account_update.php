<?php

use yii\db\Migration;

class m160516_125013_edm_account_update extends Migration
{
    public function up()
    {
        $this->execute("alter table `edm_account` add column `type` enum('IND', 'ENT') not null default 'ENT'");
    }

    public function down()
    {
        $this->execute("alter table `edm_account` drop column `type`");

        return true;
    }
 }
