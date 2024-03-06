<?php

use common\settings\SettingsAR;
use yii\db\Migration;

class m181130_120819_rename_statements_export_settings extends Migration
{
    public function safeUp()
    {
        $this->upgradeGlobalExportSettings();
        $this->upgradeTerminalsExportSettings();
    }

    public function safeDown()
    {
        $this->downgradeGlobalExportSettings();
        $this->downgradeTerminalsExportSettings();
    }

    private function upgradeGlobalExportSettings()
    {
        // Rename EdmSettings[statementExportEnabled] to EdmSettings[exportStatementsTo1C]
        $settingsRecord = SettingsAR::findOne(['terminalId' => 'default', 'code' => 'edm:Edm']);
        if (!$settingsRecord) {
            return;
        }

        $this->copySettingsDataAttribute(
            $settingsRecord,
            'statementExportEnabled',
            'exportStatementsTo1C',
            false
        );
    }

    private function upgradeTerminalsExportSettings()
    {
        // Rename ExportSettings[use1cFormat] to ExportSettings[exportStatementsTo1C]
        $settingsRecords = SettingsAR::find()
            ->where(['not', ['terminalId' => null]])
            ->andWhere(['not', ['terminalId' => 'default']])
            ->andWhere(['code' => 'Export'])
            ->all();

        foreach ($settingsRecords as $settingsRecord) {
            $this->copySettingsDataAttribute(
                $settingsRecord,
                'use1cFormat',
                'exportStatementsTo1C',
                false
            );
        }
    }

    private function downgradeGlobalExportSettings()
    {
        // Rename EdmSettings[exportStatementsTo1C] to EdmSettings[statementExportEnabled]
        $settingsRecord = SettingsAR::findOne(['terminalId' => 'default', 'code' => 'edm:Edm']);
        if (!$settingsRecord) {
            return;
        }

        $this->copySettingsDataAttribute(
            $settingsRecord,
            'exportStatementsTo1C',
            'statementExportEnabled',
            false
        );
    }

    private function downgradeTerminalsExportSettings()
    {
        // Rename ExportSettings[exportStatementsTo1C] to ExportSettings[use1cFormat]
        $settingsRecords = SettingsAR::find()
            ->where(['not', ['terminalId' => null]])
            ->andWhere(['not', ['terminalId' => 'default']])
            ->andWhere(['code' => 'Export']);

        foreach ($settingsRecords as $settingsRecord) {
            $this->copySettingsDataAttribute(
                $settingsRecord,
                'exportStatementsTo1C',
                'use1cFormat',
                false
            );
        }
    }

    private function copySettingsDataAttribute(SettingsAR $settings, $oldKey, $newKey, $defaultValue = null)
    {
        $data = unserialize($settings->data);
        if (array_key_exists($oldKey, $data)) {
            $data[$newKey] = $data[$oldKey];
        } elseif ($defaultValue !== null) {
            $data[$newKey] = $defaultValue;
        } else {
            return;
        }

        $settings->data = serialize($data);
        $settings->save();
    }
}
