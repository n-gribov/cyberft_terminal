<?php

use yii\db\Migration;

class m210422_092935_rename_statement_request_settings extends Migration
{
    public function safeUp()
    {
        $this->copySettingsValue('requestDailyStatements', 'requestYesterdaysStatements');
        $this->copySettingsValue('requestHourlyStatements', 'requestTodaysStatements');
    }

    public function safeDown()
    {
        $this->copySettingsValue('requestYesterdaysStatements', 'requestDailyStatements');
        $this->copySettingsValue('requestTodaysStatements', 'requestHourlyStatements');
    }

    private function copySettingsValue(string $fromKey, string $toKey): void
    {
        $sbbolSettings = $this->getSbbolSettingsRecord();
        if (!$sbbolSettings) {
            return;
        }
        list('id' => $id, 'data' => $serializedData) = $sbbolSettings;
        $data = unserialize($serializedData);
        if (isset($data[$fromKey])) {
            $this->updateSettingsData($id, $serializedData, [$toKey => $data[$fromKey]]);
        }
    }

    private function getSbbolSettingsRecord(): ?array
    {
        $row = Yii::$app->db
            ->createCommand('select id, data from settings where terminalId is null and code = :code', [':code' => 'SBBOL:SBBOL'])
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
}
