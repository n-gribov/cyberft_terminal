<?php

use yii\db\Schema;
use yii\db\Migration;

class m151030_102821_fileact_CryptoproCert_keyId extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE fileact_CryptoproCert ADD status VARCHAR(32) DEFAULT NULL AFTER id");
        $this->execute("ALTER TABLE fileact_CryptoproCert ADD keyId VARCHAR(255) DEFAULT NULL AFTER terminalId");
    }

    public function down()
    {
        $this->execute("ALTER TABLE fileact_CryptoproCert DROP status");
        $this->execute("ALTER TABLE fileact_CryptoproCert DROP keyId");

        return false;
    }
}
