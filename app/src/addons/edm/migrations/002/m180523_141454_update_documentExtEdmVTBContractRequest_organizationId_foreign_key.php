<?php

use yii\db\Migration;

class m180523_141454_update_documentExtEdmVTBContractRequest_organizationId_foreign_key extends Migration
{
    private $tableName = '{{%documentExtEdmVTBContractRequest}}';
    private $organizationIdForeignKeyName = 'documentExtEdmVTBContractRequest_organizationId_FK';

    public function safeUp()
    {
        $this->dropForeignKey($this->organizationIdForeignKeyName, $this->tableName);
        $this->addForeignKey(
            $this->organizationIdForeignKeyName,
            $this->tableName,
            'organizationId',
            '{{%edmDictOrganization}}',
            'id',
            'SET NULL'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey($this->organizationIdForeignKeyName, $this->tableName);
        $this->addForeignKey(
            $this->organizationIdForeignKeyName,
            $this->tableName,
            'organizationId',
            '{{%edmDictOrganization}}',
            'id'
        );
    }
}
