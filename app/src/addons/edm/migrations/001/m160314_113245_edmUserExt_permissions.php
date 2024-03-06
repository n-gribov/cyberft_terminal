<?php

use yii\db\Migration;

class m160314_113245_edmUserExt_permissions extends Migration
{
    public function up()
    {
       $this->addColumn('edm_UserExt', 'permissionsData', \yii\db\mysql\Schema::TYPE_TEXT);
    }

    public function down()
    {
        $this->dropColumn('edm_UserExt', 'permissionsData');

        return true;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
