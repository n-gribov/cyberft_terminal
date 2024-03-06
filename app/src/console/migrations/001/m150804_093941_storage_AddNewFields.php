<?php

use yii\db\Migration;

class m150804_093941_storage_AddNewFields extends Migration
{
	/**
	 * Storage table name
	 *
	 * @var string Table name
	 */
	private $storageTable = 'storage';

	/**
	 * Storage index name
	 *
	 * @var string Index name
	 */
	private $indexName = 'storageServiceIdEntityIdFileType';

	public function up()
    {
		$this->addColumn($this->storageTable, 'entityId', 'bigint(20) DEFAULT NULL');
		$this->addColumn($this->storageTable, 'fileType', 'varchar(32) DEFAULT NULL');
		$this->addColumn($this->storageTable, 'context', 'text DEFAULT NULL');
		$this->addColumn($this->storageTable, 'status', 'varchar(64) DEFAULT NULL');

		$this->createIndex($this->indexName, $this->storageTable, ['serviceId', 'entityId', 'fileType']);
    }

    public function down()
    {
		$this->dropIndex($this->indexName, $this->storageTable);

        $this->dropColumn($this->storageTable, 'entityId');
		$this->dropColumn($this->storageTable, 'fileType');
		$this->dropColumn($this->storageTable, 'context');
		$this->dropColumn($this->storageTable, 'status');
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
