<?php

use addons\finzip\models\FinZipUserExt;
use yii\db\Migration;

class m190312_151248_update_finzip_UserExt_permissions extends Migration
{
    public function safeUp()
    {
        foreach (FinZipUserExt::find()->all() as $record) {
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
            $record->save();
        }
    }

    public function safeDown()
    {
        foreach (FinZipUserExt::find()->all() as $record) {
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
            $record->save();
        }
    }
}
