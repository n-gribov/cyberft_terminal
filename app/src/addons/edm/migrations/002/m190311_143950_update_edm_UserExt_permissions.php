<?php

use addons\edm\models\EdmUserExt;
use yii\db\Migration;

class m190311_143950_update_edm_UserExt_permissions extends Migration
{
    public function safeUp()
    {
        foreach (EdmUserExt::find()->all() as $record) {
            $permissions = $record->permissions;
            if (!is_array($permissions)) {
                $permissions = [];
            }
            $permissions = array_values($permissions); // Sometimes it is saved as associative array
            if ($record->canAccess) {
                $permissions[] = 'documentView';
            } else {
                $permissions = [];
            }
            $record->permissions = $permissions;
            // Сохранить модель в БД
            $record->save();
        }
    }

    public function safeDown()
    {
        foreach (EdmUserExt::find()->all() as $record) {
            $permissions = $record->permissions;
            if (!is_array($permissions)) {
                $permissions = [];
            }
            $permissions = array_values(
                array_filter(
                    $permissions,
                    function ($item) {
                        return $item !== 'documentView';
                    }
                )
            );
            $record->permissions = $permissions;
            // Сохранить модель в БД
            $record->save();
        }
    }
}
