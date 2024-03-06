<?php

use yii\db\Migration;

class m151106_095826_user_auth_cert_deletePublicKeyColumn extends Migration
{
    /**
     * Table name
     *
     * @var string $_tableName Table name
     */
    private $_tableName = '{{%user_auth_cert}}';

    /**
     * @var string $_columnName Column name
     */
    private $_columnName = 'publicKey';

    public function up()
    {
        $this->dropColumn($this->_tableName, $this->_columnName);
    }

    public function down()
    {
        echo "m151106_095826_user_auth_cert_deletePublicKeyColumn cannot be reverted.\n";

        return false;
    }
}
