<?php

use addons\swiftfin\models\SwiftFinUserExt;
use yii\db\Migration;

class m190312_151405_update_swiftfin_UserExt_permissions extends Migration
{
    public function safeUp()
    {
        foreach (SwiftFinUserExt::find()->all() as $record) {
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
        foreach (SwiftFinUserExt::find()->all() as $record) {
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
