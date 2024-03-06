<?php

use yii\db\Migration;

class m161121_125658_create_edmpayeraccount extends Migration
{
    public function up()
    {
        $this->createTable('edmPayersAccounts', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'organizationId' => $this->integer(11)->notNull(),
            'number' => $this->string(32)->notNull(),
            'currencyId' => $this->integer(11)->notNull(),
            'bankBik' => $this->string(9)->notNull()
        ]);
    }

    public function down()
    {
        $this->dropTable('edmPayersAccounts');
        return true;
    }
}
