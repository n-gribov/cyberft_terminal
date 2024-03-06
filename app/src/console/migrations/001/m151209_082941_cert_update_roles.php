<?php

use yii\db\Migration;
use common\modules\certManager\models\Cert;

class m151209_082941_cert_update_roles extends Migration
{
    public function up()
    {
        $this->execute('update `'
                . Cert::tableName()
                . '` set role='
                . Cert::ROLE_SIGNER_BOT
                . ' where role in(0, 1, 3)');
    }

    public function down()
    {
        echo "m151209_082941_cert_update_roles cannot be reverted.\n";

        return false;
    }
    
}
