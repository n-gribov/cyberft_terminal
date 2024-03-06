<?php

use yii\db\Schema;
use yii\db\Migration;

class m151029_081710_fileact_CryptoproKeys_status extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE fileact_CryptoproKeys ADD userId INT UNSIGNED NULL AFTER id");
        $this->execute("ALTER TABLE fileact_CryptoproKeys ADD status VARCHAR(32) DEFAULT NULL AFTER userId");
        $this->execute("ALTER TABLE fileact_CryptoproKeys ADD password text DEFAULT NULL");
        $this->execute("ALTER TABLE fileact_CryptoproKeys ADD active tinyint(1) DEFAULT 0");
    }

    public function down()
    {
        $this->execute("ALTER TABLE fileact_CryptoproKeys DROP userId");
        $this->execute("ALTER TABLE fileact_CryptoproKeys DROP status");
        $this->execute("ALTER TABLE fileact_CryptoproKeys DROP password");
        $this->execute("ALTER TABLE fileact_CryptoproKeys DROP active");

        return false;
    }
}
