<?php

use yii\db\Migration;
use common\models\User;
use common\models\CommonUserExt;

class m161026_114357_create_common_UserExt extends Migration
{
    public function up()
    {
        // Миграция добавляет таблицу для хранения
        // дополнительных сервисов пользователя
        // CYB-2978

        $this->createTable('common_UserExt', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer(),
            'type' => $this->string(),
            'canAccess' => $this->boolean(),
            'data' => $this->string()
        ]);

        // Главному администратору по-умолчанию
        // устанавливаем возможность управлять сертификатами
        $user = User::find()
                ->where(['role' => User::ROLE_ADMIN, 'status' => User::STATUS_ACTIVE])
                ->orderBy(['created_at' => 'asc'])
                ->one();

        if ($user) {
            $commonExtUser = new CommonUserExt();
            $commonExtUser->userId = $user->id;
            $commonExtUser->type = CommonUserExt::CERTIFICATES;
            $commonExtUser->canAccess = 1;
            $commonExtUser->save();
        }
    }

    public function down()
    {
        $this->dropTable('common_UserExt');
        return true;
    }
}
