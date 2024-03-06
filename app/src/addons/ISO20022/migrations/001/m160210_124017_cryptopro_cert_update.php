<?php

use yii\db\Migration;

class m160210_124017_cryptopro_cert_update extends Migration
{
    public function up()
    {
        $this->execute("alter table `iso20022_CryptoproCert` add column `serialNumber` varchar(255) after `keyId`");
    }

    public function down()
    {
        $this->execute("alter table `iso20022_CryptoproCert` drop column `serialNumber`");

        return true;
    }

}
