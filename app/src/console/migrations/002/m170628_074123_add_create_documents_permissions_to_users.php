<?php

use yii\db\Migration;
use common\models\User;
use yii\helpers\ArrayHelper;

class m170628_074123_add_create_documents_permissions_to_users extends Migration
{
    public function up()
    {
        // Все существующие пользователи, которые имеют доступ к модулям,
        // могут также создавать документы в рамках этого модуля
        // CYB-3713

        // Получаем всех активных пользователей
        $users = User::find()->select('id')->where(['status' => User::STATUS_ACTIVE])->asArray()->all();
        $users = ArrayHelper::getColumn($users, 'id');

        $addons = array_keys(Yii::$app->addon->getRegisteredAddons());

        foreach($users as $userId) {
            // Проверяем доступность сервисов у пользователя
            foreach($addons as $serviceId) {
                $module = Yii::$app->getModule($serviceId);

                $settings = $module->getUserExtModel($userId);

                // Если нет настроек для аддона
                if ($settings->getIsNewRecord()) {
                    continue;
                }

                // Если модуль недоступен
                if ($settings->canAccess == 0) {
                    continue;
                }

                $permissions = $settings->permissions ? $settings->permissions : [];

                // Если право создания документов уже установлено
                if (in_array('documentCreate', $permissions)) {
                    continue;
                }

                // Добавления права создания документа данного модуля
                $permissions[] = 'documentCreate';

                $settings->permissions = $permissions;
                // Сохранить модель в БД
                $settings->save();
            }
        }
    }
}
