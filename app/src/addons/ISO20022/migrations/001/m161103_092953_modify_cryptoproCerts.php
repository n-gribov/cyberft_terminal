<?php

use yii\db\Migration;

class m161103_092953_modify_cryptoproCerts extends Migration
{
    public function up()
    {
        // По задаче теперь требуется хранить дату и время действия сертификата
        // CYB-3090
        $this->alterColumn('iso20022_CryptoproCert', 'validBefore', $this->dateTime());
    }

    public function down()
    {
        $this->alterColumn('iso20022_CryptoproCert', 'validBefore', $this->date());
        return true;
    }
}
