<?php

use yii\db\Migration;

class m190204_143347_create_sbbol_organization extends Migration
{
    private $organizationTableName = '{{%sbbol_organization}}';
    private $customerTableName = '{{%sbbol_customer}}';

    public function safeUp()
    {
        $this->createOrganizationTable();
        $this->fillOrganizationTable();
        $this->alterCustomerTable();
    }

    public function safeDown()
    {
        $this->undoAlterCustomerTable();
        $this->dropTable($this->organizationTableName);
    }

    private function fillOrganizationTable()
    {
        $organizations = (new \yii\db\Query())
            ->select(['inn', 'fullName'])
            ->from($this->customerTableName)
            ->distinct()
            ->all();

        $organizationsRows = array_map(
            function ($organization) {
                return array_values($organization);
            },
            $organizations
        );

        $this->batchInsert(
            $this->organizationTableName,
            ['inn', 'fullName'],
            $organizationsRows
        );
    }

    private function createOrganizationTable()
    {
        $this->createTable($this->organizationTableName, [
            'inn' => $this->string(32)->notNull()->unique(),
            'fullName' => $this->string(1000)->notNull(),
            'terminalAddress' => $this->string(32)->unique(),
        ]);

        $this->addPrimaryKey('pk_sbbol_organization', $this->organizationTableName, 'inn');

        $this->createIndex(
            'sbbol_organization_terminalAddress',
            $this->organizationTableName,
            'terminalAddress',
            true
        );
    }

    private function alterCustomerTable()
    {
        $this->alterColumn($this->customerTableName, 'inn', $this->string(32)->notNull());
        $this->dropColumn($this->customerTableName, 'terminalAddress');
        $this->addForeignKey(
            'fk_sbbol_customer_inn',
            $this->customerTableName,
            'inn',
            $this->organizationTableName,
            'inn'
        );
    }

    private function undoAlterCustomerTable()
    {
        $this->dropForeignKey('fk_sbbol_customer_inn', $this->customerTableName);
        $this->alterColumn($this->customerTableName, 'inn', $this->string(32));
        $this->addColumn($this->customerTableName, 'terminalAddress', $this->string(32)->unique());
    }
}
