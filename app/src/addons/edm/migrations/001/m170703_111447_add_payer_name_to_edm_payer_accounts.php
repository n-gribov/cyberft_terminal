<?php

use yii\db\Migration;

class m170703_111447_add_payer_name_to_edm_payer_accounts extends Migration
{
    public function up()
    {
        // Добавление уникального
        // наименования плательщика для счета организации
        // @CYB-3793
        $this->addColumn('edmPayersAccounts', 'payerName', $this->string());
    }

    public function down()
    {
        $this->dropColumn('edmPayersAccounts', 'payerName');
        return true;
    }
}
