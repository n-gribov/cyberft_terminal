<?php

use yii\db\Migration;

class m151105_091937_monitor_log_addIndex extends Migration
{
    /**
     * Table name
     *
     * @var string $_tableName Table name
     */
    private $_tableName = '{{%monitor_log}}';

    /**
     * @var string $_indexEntityEntityId Index name for (entity, entityId)
     */
    private $_indexEntityEntityId = 'monitorLogIndexEntityEntityId';

    public function up()
    {
        $this->createIndex($this->_indexEntityEntityId, $this->_tableName, ['entity', 'entityId']);
    }

    public function down()
    {
        $this->dropIndex($this->_indexEntityEntityId, $this->_tableName);
    }
}
