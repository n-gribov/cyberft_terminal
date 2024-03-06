<?php

use common\models\Terminal;
use common\settings\SettingsAR;
use yii\db\Migration;

class m161212_073220_settings_terminals extends Migration
{
    public function up()
    {
        $this->execute('alter table `settings` add column `terminalId` varchar(12) null after `id`');
        $this->execute('alter table `settings` drop key `code`');
        $this->execute('alter table `settings` add unique key `term_code` (`terminalId`, `code`)');

        /**
         * В терминалах нужно получать кастомный список полей, иначе миграция упадет из-за того, что
         * некоторые поля в Terminal еще не созданы последующими миграциями
         */
        $defaultTerminal = Terminal::find()
            ->where(['status' => Terminal::STATUS_ACTIVE, 'isDefault' => 1])
            ->select('terminalId')
            ->one();

        if (empty($defaultTerminal)) {
            return true;
        }

        $defaultTerminalId = $defaultTerminal->id;

        $terminals = Terminal::find()->select(['terminalId'])->all();

        if (empty($terminals)) {
            return true;
        }

        $allSettings = SettingsAR::find()->all();

        foreach($allSettings as $model) {
            /**
             * Все существующие модели остаются с terminalId = null как дефолтные
             * Для всех терминалов создаются новые модели с теми же данными и с terminalId
             */
            foreach($terminals as $terminal) {
                $newModel = new SettingsAR([
                    'code' => $model->code,
                    'terminalId' => $terminal->terminalId,
                    'data' => $model->data
                ]);

                $result = $newModel->save();

                if (!$result) {
                    /**
                    * Если процессы не выключены, то регулярные джобы могли уже насрать новых моделей
                    * с непроинициализированныим данными, тогда получится ошибка дублирования уникальных полей
                    */
                    $existingModel = SettingsAR::findOne([
                        'code' => $model->code,
                        'terminalId' => $terminal->terminalId
                    ]);

                    if ($existingModel) {
                        $newModel = $existingModel;
                        $newModel->data = $model->data;
                        $result = $newModel->save();
                    }
                }

                if (!$result) {
                    echo 'ERROR: could not create new settings ' . $terminal->terminalId . ':' . $newModel->code . "\n";
                    var_dump($newModel->errors);
                } else {
                    echo 'Saved ' . $newModel->code . " settings\n";
                }
            }
        }

        /**
         * во всех AppSettings оставляем данные только об их собственном терминале
         */
        $appSettings = SettingsAR::findAll(['code' => 'app']);

        foreach($appSettings as $model) {

            if (!$model->terminalId) {
                // пропускаем дефолтные модели
                continue;
            }

            $terminalId = $model->terminalId;

            echo 'Processing ' . $terminalId . ':App data... ';
            $attributes = unserialize($model->data);

            $stomp = $attributes['stomp'];

            if (isset($stomp[$terminalId])) {
                $attributes['stomp'] = [$terminalId => $stomp[$terminalId]];
            } else {
                $attributes['stomp'] = [$terminalId => ['login' => $terminalId, 'password' => '']];
            }

            $model->data = serialize($attributes);

            if (!$model->save()) {
               echo "ERROR: could not update\n";
               var_dump($model->errors);
            } else {
                echo "OK\n";
            }
        }

        return true;
    }

    public function down()
    {
        /**
         * собираем данные о терминалах из всех AppSettings, кроме дефолтного назад в общую модель
         */
        $appSettings = SettingsAR::find()->where([
            'and',
            ['code' => 'app'],
            ['is not', 'terminalId', null]
        ])->all();

        $terminalData = [];

        foreach($appSettings as $model) {
            $attributes = unserialize($model->data);
            if (isset($attributes['stomp'][$model->terminalId])) {
                $terminalData[$model->terminalId] = $attributes['stomp'][$model->terminalId];
            }
        }

        /**
         * для остальных данных нужно найти либо дефолтную модель с нулевым терминалом,
         * либо модель с дефолтным терминалом
         */

        $defaultTerminalId = Yii::$app->terminals->defaultTerminal->terminalId;

        $defaultAppSettings = SettingsAR::findOne([
            'code' => 'app',
            'terminalId' => null
        ]);

        if (!$defaultAppSettings) {
            $defaultAppSettings = SettingsAR::findOne([
                'code' => 'app',
                'terminalId' => $defaultTerminalId
            ]);

        }

        if (!$defaultAppSettings) {
            echo "No default App Settings found.\n\n";

            return true;
        }

        $defaultAttributes = unserialize($defaultAppSettings->data);
        $defaultAttributes['stomp'] = $terminalData;
        $defaultAppSettings->data = serialize($defaultAttributes);
        $defaultAppSettings->save();

        /**
         * Удаляем все недефолтные модели
         */
        SettingsAR::deleteAll(['is not', 'terminalId', null]);

        $this->execute('alter table `settings` drop key `term_code`');
        $this->execute('alter table `settings` drop column `terminalId`');
        $this->execute('alter table `settings` add unique key `code` (`code`)');

        return true;
    }

}
