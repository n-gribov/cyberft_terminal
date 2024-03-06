<?php

use yii\db\Migration;

class m161019_073855_add_expire_date_column_to_cryptoproKeys extends Migration
{
    public function up()
    {
        $this->addColumn('cryptoproKeys', 'expireDate', $this->datetime());
    }

    public function down()
    {
        $this->dropColumn('cryptoproKeys', 'expireDate');
        return true;
    }
}
