<?php

use yii\db\Migration;

class m210111_133028_move_xml_validation_option_to_app_settings extends Migration
{
    public function safeUp()
    {
        if ($this->isIsoXmlValidationEnabled()) {
            $this->enableGlobalXmlValidation();
        }
    }

    public function safeDown()
    {
    }

    private function getIsoSettings(): ?array
    {
        $record = $this->getSettingsRecord('ISO20022:ISO20022');
        if (!$record) {
            return null;
        }
        return unserialize($record['data']);
    }

    private function getSettingsRecord(string $code): ?array
    {
        $row = Yii::$app->db
            ->createCommand(
                "select id, data from settings where terminalId is null and code = :code",
                [':code' => $code]
            )
            ->queryOne();
        return $row ?: null;
    }

    private function isIsoXmlValidationEnabled(): bool
    {
        $isoSettings = $this->getIsoSettings();
        if ($isoSettings === null) {
            return false;
        }
        return boolval($isoSettings['validateXsd'] ?? null);
    }

    private function enableGlobalXmlValidation(): void
    {
        $appSettings = $this->getSettingsRecord('app');
        if (!$appSettings) {
            echo "No app settings found\n";
            return;
        }
        list('id' => $id, 'data' => $serializedData) = $appSettings;
        $this->updateSettingsData($id, $serializedData, ['validateXmlOnImport' => true]);
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
}
