<?php

use addons\edm\jobs\ExportJob\ExportFormat;
use addons\edm\models\EdmPayerAccount;
use yii\db\Migration;

class m201019_071908_move_iso_1c_export_settings_to_edm extends Migration
{
    public function safeUp()
    {
        if (!$this->isIso1cExportEnabled()) {
            return true;
        }

        $this->update(
            EdmPayerAccount::tableName(),
            ['todaysStatementsExportFormat' => ExportFormat::ONE_C],
            ['todaysStatementsExportFormat' => null]
        );
        $this->update(
            EdmPayerAccount::tableName(),
            ['previousDaysStatementsExportFormat' => ExportFormat::ONE_C],
            ['previousDaysStatementsExportFormat' => null]
        );
    }

    public function safeDown()
    {
    }

    private function getIsoSettings(): ?array
    {
        $serializedData = Yii::$app->db
            ->createCommand("select data from settings where terminalId is null and code = 'ISO20022:ISO20022'")
            ->queryScalar();
        return empty($serializedData) ? null : unserialize($serializedData);
    }

    private function isIso1cExportEnabled(): bool
    {
        $isoSettings = $this->getIsoSettings();
        if ($isoSettings === null) {
            return false;
        }
        return boolval($isoSettings['exportIncomingStatementsTo1c'] ?? null);
    }
}
