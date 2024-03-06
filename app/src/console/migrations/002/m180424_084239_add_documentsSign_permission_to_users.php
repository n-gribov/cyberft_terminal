<?php

use common\models\User;
use yii\db\Migration;

class m180424_084239_add_documentsSign_permission_to_users extends Migration
{
    public function safeUp()
    {
        $users = User::find()->all();
        foreach ($users as $user) {
            foreach (['edm', 'finzip', 'fileact'] as $serviceId) {
                $module = Yii::$app->getModule($serviceId);
                if ($module === null) {
                    continue;
                }

                $userExt = $module->getUserExtModel($user->id);
                if ($userExt === null) {
                    continue;
                }

                if (!$userExt->canAccess) {
                    continue;
                }

                $permissions = $userExt->permissions ? $userExt->permissions : [];
                if (!in_array('documentCreate', $permissions)) {
                    continue;
                }

                if (in_array('documentSign', $permissions)) {
                    continue;
                }

                echo "Granting document signing permission to {$user->email} for module $serviceId\n";

                $permissions[] = 'documentSign';
                $userExt->permissions = $permissions;
                // Сохранить модель в БД
                $userExt->save();
            }
        }
    }

    public function safeDown()
    {
        $users = User::find()->all();
        foreach ($users as $user) {
            foreach (['edm', 'finzip', 'fileact'] as $serviceId) {
                $module = Yii::$app->getModule($serviceId);
                if ($module === null) {
                    continue;
                }

                $userExt = $module->getUserExtModel($user->id);
                if ($userExt === null) {
                    continue;
                }

                $permissions = $userExt->permissions ? $userExt->permissions : [];
                $permissionIndex = array_search('documentSign', $permissions);
                if ($permissionIndex === false) {
                    continue;
                }

                echo "Revoking document signing permission to {$user->email} for module $serviceId\n";

                unset($permissions[$permissionIndex]);
                $userExt->permissions = $permissions;
                // Сохранить модель в БД
                $userExt->save();
            }
        }
    }
}
