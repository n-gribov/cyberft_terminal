<?php

use yii\db\Migration;

class m210422_160604_migrate_requests_settings extends Migration
{
    public function safeUp()
    {
        $settings = $this->getSettingsRecord();
        if (!$settings) {
            return;
        }

        $update = [];

        $processAsyncRequestsInterval = getenv('RAIFFEISEN_STATUS_CHECK_INTERVAL');
        if ($processAsyncRequestsInterval) {
            $update['processAsyncRequestsInterval'] = max(1, intval(round($processAsyncRequestsInterval / 60)));
        }

        $requestIncomingDocumentsInterval = getenv('RAIFFEISEN_INCOMING_REQUEST_INTERVAL');
        if ($requestIncomingDocumentsInterval) {
            $update['requestIncomingDocumentsInterval'] = $this->fixIncomingRequestInterval($requestIncomingDocumentsInterval / 60);
        }

        $requestIncomingDocumentsHourFrom = getenv('RAIFFEISEN_INCOMING_REQUEST_HOUR_FROM');
        if ($requestIncomingDocumentsHourFrom) {
            $update['requestIncomingDocumentsTimeFrom'] = sprintf('%02d:00', $requestIncomingDocumentsHourFrom);
        }

        $requestIncomingDocumentsHourTo = getenv('RAIFFEISEN_INCOMING_REQUEST_HOUR_TO');
        if ($requestIncomingDocumentsHourTo) {
            $update['requestIncomingDocumentsTimeTo'] = sprintf('%02d:59', $requestIncomingDocumentsHourTo);
        }

        list('id' => $id, 'data' => $serializedData) = $settings;
        $this->updateSettingsData($id, $serializedData, $update);
    }

    public function safeDown()
    {
    }

    private function getSettingsRecord(): ?array
    {
        $row = Yii::$app->db
            ->createCommand('select id, data from settings where terminalId is null and code = :code', [':code' => 'raiffeisen:Raiffeisen'])
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

    private function fixIncomingRequestInterval($value): int
    {
        // Find closest value from options allowed in settings
        $intervals = [5, 10, 15, 20, 30, 60];
        $deltas = array_map(
            function ($current) use ($value) {
                return abs($value - $current);
            },
            $intervals
        );
        $indexOfMin = array_search(min($deltas), $deltas);
        return $intervals[$indexOfMin];
    }
}
