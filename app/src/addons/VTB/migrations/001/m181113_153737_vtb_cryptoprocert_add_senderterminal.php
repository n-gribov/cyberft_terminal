<?php

use yii\db\Migration;

class m181113_153737_vtb_cryptoprocert_add_senderterminal extends Migration
{
    public function up()
    {
        $this->execute('alter table vtb_CryptoproCert add column senderTerminalAddress varchar(12)');
    }

    public function down()
    {
        $this->execute('alter table vtb_CryptoproCert drop column senderTerminalAddress');

        return true;
    }

}
