<?php

use addons\edm\models\EdmUserExt;
use yii\db\Migration;

class m190307_132756_add_hideZeroTurnoverStatements_to_edm_UserExt extends Migration
{
    private $tableName = '{{%edm_UserExt}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'hideZeroTurnoverStatements', $this->boolean()->defaultValue(false));
        Yii::$app->db->schema->refresh();

        foreach (EdmUserExt::find()->all() as $record) {
            $permissions = $record->permissions;
            if (!is_array($permissions) || empty($permissions)) {
                continue;
            }
            $permissions = array_values($permissions); // Sometimes it is saved as associative array
            if (in_array('hideStatementsNullTurnovers', $permissions)) {
                $permissions = array_values(
                    array_filter($permissions, function ($item) {
                        return $item !== 'hideStatementsNullTurnovers';
                    })
                );
                $record->permissions = $permissions;
                $record->hideZeroTurnoverStatements = true;
                // Сохранить модель в БД
                $record->save();
            }
        }
    }

    public function safeDown()
    {
        foreach (EdmUserExt::find()->all() as $record) {
            $permissions = $record->permissions;
            if (!is_array($permissions) || empty($permissions)) {
                $permissions = [];
            }
            $permissions = array_values($permissions); // Sometimes it is saved as associative array
            if ($record->hideZeroTurnoverStatements) {
                $permissions[] = 'hideStatementsNullTurnovers';
                $record->permissions = $permissions;
                // Сохранить модель в БД
                $record->save();
            }
        }

        $this->dropColumn($this->tableName, 'hideZeroTurnoverStatements');
    }
}
