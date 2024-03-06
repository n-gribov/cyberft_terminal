<?php

use common\base\BaseBlock;
use yii\db\Migration;

class m201124_083822_update_auto_signing_settings extends Migration
{
    public function safeUp()
    {
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

    private function updateTerminalSettings(string $terminalAddress): void
    {
        foreach (array_keys(Yii::$app->addon->getRegisteredAddons()) as $serviceId) {
            try {
                $this->updateAddonTerminalSettings($serviceId, $terminalAddress);
            } catch (\Exception $exception) {
                echo "Failed to update $serviceId settings for $terminalAddress, caused by: $exception\n";
            }
        }
    }

    private function updateAddonTerminalSettings(string $serviceId, string $terminalAddress): void
    {
        /** @var BaseBlock $module */
        $module = Yii::$app->getModule($serviceId);
        if ($module === null || empty($module::SETTINGS_CODE)) {
            return;
        }
        $settingsRecord = $this->getTerminalSettingsRecord($module::SETTINGS_CODE, $terminalAddress);
        if ($settingsRecord === null) {
            return;
        }
        list('id' => $id, 'data' => $serializedData) = $settingsRecord;
        $settings = unserialize($serializedData);
        if (!is_array($settings)) {
            return;
        }
        $settingsUpdate = ['useAutosigning' => $this->hasAutoSigning($settings)];
        $this->updateSettingsData($id, $serializedData, $settingsUpdate);
    }

    private function hasAutoSigning(array $addonSettings): bool
    {
        if (!isset($addonSettings['autoSigning']) || !is_array($addonSettings['autoSigning'])) {
            return false;
        }
        // autoSigning looks like ['File' => 'sign', 'XmlFile' => 'sign', ...]
        return in_array('sign', $addonSettings['autoSigning']);
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
