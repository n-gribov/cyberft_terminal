<?php

use yii\db\Migration;

class m200410_113816_update_export_settings extends Migration
{
    public function safeUp()
    {
        try {
            $this->updateGlobalSettings();
        } catch (\Exception $exception) {
            echo "Failed to update global export settings, caused by: $exception\n";
        }

        foreach ($this->getTerminalsAddresses() as $terminalAddress) {
            try {
                $this->updateTerminalSettings($terminalAddress);
            } catch (\Exception $exception) {
                echo "Failed to update settings for $terminalAddress, caused by: $exception\n";
            }
        }
    }

    public function safeDown()
    {
    }

    private function updateTerminalSettings($terminalAddress)
    {
        if (!$this->terminalHasOwnExportSettings($terminalAddress)) {
            return;
        }

        echo "Turning off useGlobalExportSettings for $terminalAddress\n";
        $settingsUpdate = ['useGlobalExportSettings' => '0'];
        $terminalSettings = $this->getTerminalSettingsRecord('App', $terminalAddress);
        if ($terminalSettings) {
            list('id' => $id, 'data' => $serializedData) = $terminalSettings;
            $this->updateSettingsData($id, $serializedData, $settingsUpdate);
        } else {
            echo "Creating app settings for $terminalAddress\n";
            $this->insertSettings('App', $terminalAddress, serialize($settingsUpdate));
        }
    }

    private function terminalHasOwnExportSettings($terminalAddress): bool
    {
        $exportSetting = $this->getTerminalExportSettings($terminalAddress);
        if (empty($exportSetting)) {
            return false;
        }

        $props = [
            'edmExportXml',
            'ISO20022ExportXml',
            'swiftfinExportXml',
            'fileactExportXml',
            'finzipExportXml',
            'useSwiftfinFormat',
            'exportStatusReports',
            'exportStatementsTo1C',
            'exportStatementsToISO',
        ];
        foreach ($props as $prop) {
            $value = boolval($exportSetting[$prop] ?? false);
            if ($value) {
                return true;
            }
        }
        return false;
    }

    private function updateGlobalSettings()
    {
        // Т.к. раньше экспорт Swift выполнялся независимо от того, включена ли соответствущая
        // настройка, для совместимости включим ее.

        $swiftFinSettings = $this->getGlobalSettingsRecord('swiftfin:Swiftfin');
        if (!$swiftFinSettings) {
            return;
        }
        list('id' => $id, 'data' => $serializedData) = $swiftFinSettings;
        $this->updateSettingsData($id, $serializedData, ['exportIsActive' => true]);
    }

    private function getTerminalExportSettings(string $terminalAddress): ?array
    {
        $record = $this->getTerminalSettingsRecord('Export', $terminalAddress);
        if (!$record) {
            return null;
        }
        return unserialize($record['data']);
    }

    private function getTerminalSettingsRecord(string $code, string $terminalAddress): ?array
    {
        $row = Yii::$app->db
            ->createCommand(
                "select id, data from settings where terminalId = :terminalAddress and code = :code",
                [':terminalAddress' => $terminalAddress, ':code' => $code]
            )
            ->queryOne();
        return $row ?: null;
    }

    private function getGlobalSettingsRecord($code): ?array
    {
        $row = Yii::$app->db
            ->createCommand('select id, data from settings where terminalId is null and code = :code', [':code' => $code])
            ->queryOne();
        return $row ?: null;
    }

    private function insertSettings($code, $terminalAddress, string $serializedData)
    {
        $this->insert(
            'settings',
            [
                'terminalId' => $terminalAddress,
                'code' => $code,
                'data' => $serializedData,
            ]
        );
    }

    private function updateSettingsData($id, $serializedData, $update)
    {
        $data = unserialize($serializedData);
        $updatedData = serialize(array_merge($data, $update));
        $this->update(
            'settings',
            ['data' => $updatedData],
            ['id' => $id]
        );
    }

    private function getTerminalsAddresses(): array
    {
        return Yii::$app->db
            ->createCommand('select terminalId from terminal')
            ->queryColumn();
    }
}
