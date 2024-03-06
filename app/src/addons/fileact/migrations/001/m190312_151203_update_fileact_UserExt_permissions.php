<?php

use addons\fileact\models\FileActUserExt;
use yii\db\Migration;

class m190312_151203_update_fileact_UserExt_permissions extends Migration
{
    public function safeUp()
    {
        foreach (FileActUserExt::find()->all() as $record) {
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
        foreach (FileActUserExt::find()->all() as $record) {
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
