<?php

use addons\edm\jobs\ExportJob\ExportFormat;
use addons\edm\models\EdmPayerAccount;
use yii\db\Migration;

class m200407_110148_add_export_settings_to_edmPayersAccounts extends Migration
{
    private $tableName = '{{%edmPayersAccounts}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'todaysStatementsExportFormat', $this->string());
        $this->addColumn($this->tableName, 'previousDaysStatementsExportFormat', $this->string());
        $this->addColumn($this->tableName, 'useIncrementalExportForTodaysStatements', $this->boolean()->defaultValue(false));

        try {
            $this->migrateExportSettings();
        } catch (\Exception $exception) {
            echo "Failed to copy settings, caused by $exception\n";
        }
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'todaysStatementsExportFormat');
        $this->dropColumn($this->tableName, 'previousDaysStatementsExportFormat');
        $this->dropColumn($this->tableName, 'useIncrementalExportForTodaysStatements');
    }

    private function migrateExportSettings()
    {
        $this->migrateGlobalSettings();

        $organizations = $this->getOrganizations();
        foreach ($organizations as $organization) {
            list('id' => $organizationId, 'terminalAddress' => $terminalAddress) = $organization;
            $this->migrateTerminalSettings($organizationId, $terminalAddress);
        }
    }

    private function migrateTerminalSettings($organizationId, $terminalAddress)
    {
        $settings = $this->getTerminalExportSettings($terminalAddress);
        if (empty($settings)) {
            return;
        }

        list($exportFormat, $useIncrementalExportForTodaysStatements) = $this->extractExportOptions($settings);

        if ($exportFormat) {
            echo "Update accounts for $terminalAddress, export format: $exportFormat, incremental: $useIncrementalExportForTodaysStatements\n";
            $this->updateAccounts($exportFormat, $useIncrementalExportForTodaysStatements, ['organizationId' => $organizationId]);
        }
    }

    private function migrateGlobalSettings(): void
    {
        $settings = $this->getEdmSettings();
        if (empty($settings)) {
            return;
        }

        list($exportFormat, $useIncrementalExportForTodaysStatements) = $this->extractExportOptions($settings);

        if ($exportFormat) {
            echo "Update all accounts, export format: $exportFormat, incremental: $useIncrementalExportForTodaysStatements\n";
            $this->updateAccounts($exportFormat, $useIncrementalExportForTodaysStatements);
        }
    }

    private function updateAccounts($exportFormat, $useIncrementalExportForTodaysStatements, $condition = [])
    {
        $this->update(
            EdmPayerAccount::tableName(),
            [
                'todaysStatementsExportFormat' => $exportFormat,
                'previousDaysStatementsExportFormat' => $exportFormat,
                'useIncrementalExportForTodaysStatements' => $useIncrementalExportForTodaysStatements,
            ],
            $condition
        );
    }

    private function extractExportOptions(array $settings): array
    {
        $exportStatementsToISO = boolval($settings['exportStatementsToISO'] ?? false);
        $exportStatementsTo1C = boolval($settings['exportStatementsTo1C'] ?? false);
        $useIncrementalExportForTodaysStatements = intval($settings['useIncrementalExportForTodaysStatements'] ?? 0);

        $exportFormat = null;
        if ($exportStatementsToISO) {
            $exportFormat = ExportFormat::ISO20022;
        } else if ($exportStatementsTo1C) {
            $exportFormat = ExportFormat::ONE_C;
        }

        return [$exportFormat, $useIncrementalExportForTodaysStatements];
    }

    private function getOrganizations()
    {
        return Yii::$app->db
            ->createCommand('select org.id, terminal.terminalId as terminalAddress from edmDictOrganization org inner join terminal on terminal.id = org.terminalId')
            ->queryAll();
    }

    private function getEdmSettings(): ?array
    {
        $serializedData = Yii::$app->db
            ->createCommand("select data from settings where terminalId is null and code = 'edm:Edm'")
            ->queryScalar();
        return empty($serializedData) ? null : unserialize($serializedData);
    }

    private function getTerminalExportSettings(string $terminalAddress): ?array
    {
        $serializedData = Yii::$app->db
            ->createCommand("select data from settings where terminalId = :terminalAddress and code = 'Export'", [':terminalAddress' => $terminalAddress])
            ->queryScalar();
        return empty($serializedData) ? null : unserialize($serializedData);
    }
}
