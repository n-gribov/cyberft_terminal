<?php

use addons\ISO20022\models\ISO20022UserExt;
use yii\db\Migration;

class m190312_151315_update_iso20022_UserExt_permissions extends Migration
{
    public function safeUp()
    {
        foreach (ISO20022UserExt::find()->all() as $record) {
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
        foreach (ISO20022UserExt::find()->all() as $record) {
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
