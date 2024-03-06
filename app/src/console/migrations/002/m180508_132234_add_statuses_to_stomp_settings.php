<?php

use common\models\Terminal;
use common\modules\autobot\models\StompSettingsForm;
use yii\db\Migration;

/**
 * Установка статусов для stomp-настроек терминалов
 * @task CYB-4072
 */
class m180508_132234_add_statuses_to_stomp_settings extends Migration
{
    private $processingTableName = '{{%processing}}';

    public function up()
    {
        $processingTableWasCreatedBefore = $this->tableExists('processing');
        if (!$processingTableWasCreatedBefore) {
            $this->createProcessingTable();
        }

        $terminals = Terminal::find()->select('terminalId')->all();

        foreach($terminals as $terminal) {
            $appSettings = Yii::$app->settings->get('app', $terminal->terminalId);

            if (!isset($appSettings->stomp)) {
                continue;
            }

            if (!isset($appSettings->stomp[$terminal->terminalId])) {
                $appSettings->stomp[$terminal->terminalId] = [];
            }

            $stompSettings = $appSettings->stomp[$terminal->terminalId];

            if (isset($stompSettings['password']) && !empty($stompSettings['password'])) {
                $stompSettings['status'] = StompSettingsForm::STATUS_CONFIRMED;
            } else {
                $stompSettings['password'] = '';
                $stompSettings['status'] = StompSettingsForm::STATUS_WAITING_TO_SEND;
            }

            $appSettings->stomp[$terminal->terminalId] = $stompSettings;
            $appSettings->save();
        }

        if (!$processingTableWasCreatedBefore) {
            $this->dropProcessingTable();
        }

        return true;
    }

    public function down()
    {
        $terminals = Terminal::find()->select('terminalId')->all();

        foreach($terminals as $terminal) {
            $appSettings = Yii::$app->settings->get('app', $terminal->terminalId);

            if (!isset($appSettings->stomp) ||
                empty($appSettings->stomp) ||
                !isset($appSettings->stomp[$terminal->terminalId])) {
                continue;
            }

            $stompSettings = $appSettings->stomp[$terminal->terminalId];

            if (!isset($stompSettings['status'])) {
                continue;
            }

            unset($stompSettings['status']);

            $appSettings->stomp[$terminal->terminalId] = $stompSettings;
            $appSettings->save();
        }

        return true;
    }

    private function tableExists(string $name): bool
    {
        $tableName = $this->db->tablePrefix . $name;
        return $this->db->getTableSchema($tableName, true) !== null;
    }

    private function createProcessingTable()
    {
        $this->createTable(
            $this->processingTableName,
            [
                'id'        => $this->primaryKey(),
                'name'      => $this->string()->notNull()->unique(),
                'address'   => $this->string(12)->notNull()->unique(),
                'dsn'       => $this->string()->notNull()->unique(),
                'isDefault' => $this->boolean()->defaultValue(false),
            ]
        );

        $this->batchInsert(
            'processing',
            ['name', 'address', 'dsn', 'isDefault'],
            [
                ['Test Processing CYBERUM@EST',    'CYBERUM@TEST', 'tcp://localhost:40090', true],
            ]
        );
    }

    private function dropProcessingTable()
    {
        $this->dropTable($this->processingTableName);
    }
}
