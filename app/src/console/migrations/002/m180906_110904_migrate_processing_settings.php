<?php

use yii\db\Migration;

class m180906_110904_migrate_processing_settings extends Migration
{
    public function safeUp()
    {
        $this->batchInsert(
            'processing',
            ['name', 'address', 'dsn', 'isDefault'],
            [
                ['Test Processing CYBERUM@EST',    'CYBERUM@TEST', 'tcp://localhost:40090', true],
                ['CyberFT Processing CYBERUM@FTX', 'CYBERUM@AFTX', 'tcp://localhost:40091', false],
                ['Test Processing PSGTEST@PRC',    'PSGTEST@APRC', 'tcp://localhost:40092', false],
                ['CyberFT Processing PLATRUM@FTX', 'PLATRUM@AFTX', 'tcp://localhost:40093', false],
            ]
        );

        /** @var \common\settings\AppSettings $appSettings */
        $appSettings = Yii::$app->settings->get('app');
        $processingSettings = $appSettings->processing;
        if (array_key_exists('testMode', $processingSettings)) {
            if ($processingSettings['testMode']) {
                $appSettings->processing['dsn'] = 'tcp://localhost:40090';
                $appSettings->processing['address'] = 'CYBERUM@TEST';
            } else {
                $appSettings->processing['dsn'] = 'tcp://localhost:40091';
                $appSettings->processing['address'] = 'CYBERUM@AFTX';
            }
            // Сохранить модель в БД
            $appSettings->save();
        }
    }

    public function safeDown()
    {
        $this->delete('processing');
    }
}
