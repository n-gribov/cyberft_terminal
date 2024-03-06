<?php

use yii\db\Migration;

class m161103_094600_modify_fileact_cryptoproCerts extends Migration
{
    public function up()
    {
        // По задаче теперь требуется хранить дату и время действия сертификата
        // CYB-3090
        $this->alterColumn('fileact_CryptoproCert', 'validBefore', $this->dateTime());
    }

    public function down()
    {
        $this->alterColumn('fileact_CryptoproCert', 'validBefore', $this->date());
        return true;
    }
}
