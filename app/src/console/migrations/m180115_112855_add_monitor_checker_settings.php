<?php

use yii\db\Migration;
use common\modules\monitor\models\CheckerAR;
use common\modules\monitor\models\CheckerSettingsAR;

/**
 * @task CYB-3734
 * Создание отдельной таблицы для мультитерминального хранения настроек чекеров
 */
class m180115_112855_add_monitor_checker_settings extends Migration
{
    public function up()
    {
        $this->createTable('monitor_checker_settings', [
            'id' => $this->primaryKey(),
            'checkerId' => $this->integer(),
            'terminalId' => $this->integer()->defaultValue(null),
            'active' => $this->boolean()->defaultValue(0),
            'activeSince' => $this->string(),
            'settings' => $this->text(),
            'opData' => $this->text()
        ]);

        // Перенос старых настроек из таблицы чекеров в новую таблицу
        $checkers = CheckerAR::find()->all();

        foreach($checkers as $checker) {
            $data = [];
            $data['checkerId'] = $checker->id;
            $data['settingsData'] = unserialize($checker->settings);
            $data['opData'] = unserialize($checker->opData);
            $data['active'] = $checker->active;

            $checkerSettings = new CheckerSettingsAR([
                'checkerId' => $data['checkerId'],
                'active' => $data['active'],
                'settingsData' => $data['settingsData'],
                'opSettings' => $data['opData']
            ]);

            // Сохранить модель в БД
            $checkerSettings->save();
        }

        $this->dropColumn('monitor_checker', 'active');
        $this->dropColumn('monitor_checker', 'settings');
        $this->dropColumn('monitor_checker', 'opData');

    }

    public function down()
    {
        $this->dropTable('monitor_checker_settings');
        $this->addColumn('monitor_checker', 'active', $this->boolean());
        $this->addColumn('monitor_checker', 'settings', $this->text());
        $this->addColumn('monitor_checker', 'opData', $this->text());

        return true;
    }
}
