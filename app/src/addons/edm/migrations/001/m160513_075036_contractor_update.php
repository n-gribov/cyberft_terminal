<?php

use yii\db\Migration;

class m160513_075036_contractor_update extends Migration
{
    public function up()
    {
        $this->execute("alter table edmDictContractor add column `type2` varchar(32) not null");
        $this->execute("update edmDictContractor set `type2` = `type`");
        $this->execute("alter table edmDictContractor modify column `type` enum('IND', 'ENT') not null default 'ENT'");
        $this->execute("update edmDictContractor set `type` = 'ENT' where `type2` = 'JURISTIC'");
        $this->execute("update edmDictContractor set `type` = 'IND' where `type2` = 'INDIVIDUAL'");
        $this->execute("alter table edmDictContractor drop column `type2`");
    }

    public function down()
    {
        $this->execute("alter table edmDictContractor add column `type2` varchar(32) not null");
        $this->execute("update edmDictContractor set `type2` = `type`");
        $this->execute("alter table edmDictContractor modify column `type` enum('INDIVIDUAL', 'JURISTIC') not null default 'JURISTIC'");
        $this->execute("update edmDictContractor set `type` = 'JURISTIC' where `type2` = 'ENT'");
        $this->execute("update edmDictContractor set `type` = 'INDIVIDUAL' where `type2` = 'IND'");
        $this->execute("alter table edmDictContractor drop column `type2`");

        return true;
    }

}
