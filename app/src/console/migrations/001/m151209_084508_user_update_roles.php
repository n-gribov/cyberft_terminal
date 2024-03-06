<?php

use common\models\User;
use yii\db\Migration;

class m151209_084508_user_update_roles extends Migration
{
    public function up()
    {
        $this->execute('update `'
                . User::tableName()
                . '` set role='
                . User::ROLE_USER
                . ' where role in(20, 30, 40, 41)');

    }

    public function down()
    {
        echo "m151209_084508_user_update_roles cannot be reverted.\n";

        return false;
    }
    
}
