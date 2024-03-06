<?php

use yii\db\Migration;

class m151106_090731_user_auth_cert_FingerprintField extends Migration
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
    private $_columnName = 'fingerprint';

    /**
     * @var string $_indexName Index name
     */
    private $_indexName = 'user_auth_certIndexFingerprint';

    public function up()
    {
        $this->addColumn($this->_tableName, $this->_columnName, "varchar(64) DEFAULT NULL COMMENT 'Fingerprint'");
        $this->createIndex($this->_indexName, $this->_tableName, $this->_columnName);
    }

    public function down()
    {
        $this->dropIndex($this->_indexName, $this->_tableName);
        $this->dropColumn($this->_tableName, $this->_columnName);
    }
}
