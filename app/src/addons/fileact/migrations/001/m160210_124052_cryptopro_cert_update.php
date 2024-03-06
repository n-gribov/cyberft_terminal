<?php

use yii\db\Migration;

class m160210_124052_cryptopro_cert_update extends Migration
{
    public function up()
    {
        $this->execute("alter table `fileact_CryptoproCert` add column `serialNumber` varchar(255) after `keyId`");
    }

    public function down()
    {
        $this->execute("alter table `fileact_CryptoproCert` drop column `serialNumber`");

        return true;
    }

}
