<?php

use yii\db\Schema;
use yii\db\Migration;

class m151028_095527_documentExtFileAct_extStatus extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE documentExtFileAct ADD extStatus VARCHAR(32) DEFAULT NULL AFTER documentId");
        $this->execute("ALTER TABLE documentExtFileAct ADD cryptoproSigningLog text DEFAULT NULL");
    }

    public function down()
    {
        $this->execute("ALTER TABLE documentExtFileAct DROP extStatus");
        $this->execute("ALTER TABLE documentExtFileAct DROP cryptoproSigningLog");

        return false;
    }
}
