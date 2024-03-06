<?php

use yii\db\Migration;

class m161220_134642_finzip_UserExt_permissions extends Migration
{
    public function up()
    {
        $this->addColumn('finzip_UserExt', 'permissionsData', \yii\db\mysql\Schema::TYPE_TEXT);
    }

    public function down()
    {
        $this->dropColumn('finzip_UserExt', 'permissionsData');
        return true;
    }
}
