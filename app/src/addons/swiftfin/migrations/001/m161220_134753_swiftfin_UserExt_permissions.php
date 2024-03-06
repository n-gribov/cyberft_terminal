<?php

use yii\db\Migration;

class m161220_134753_swiftfin_UserExt_permissions extends Migration
{
    public function up()
    {
        $this->addColumn('swiftfin_UserExt', 'permissionsData', \yii\db\mysql\Schema::TYPE_TEXT);
    }

    public function down()
    {
        $this->dropColumn('swiftfin_UserExt', 'permissionsData');
        return true;
    }
}
