<?php

use common\models\Terminal;
use yii\db\Migration;

class m161110_132959_paymentPurpose_terminalId extends Migration
{
    public function up()
    {
        $this->execute('alter table edmDictPaymentPurpose add column terminalId int unsigned');
        $terminal = Terminal::getDefaultTerminal();
        if (!empty($terminal)) {
            $this->execute('update edmDictPaymentPurpose set terminalId = ' . $terminal->id);
        }
    }

    public function down()
    {
        $this->execute('alter table edmDictPaymentPurpose drop column terminalId');

        return true;
    }

}
