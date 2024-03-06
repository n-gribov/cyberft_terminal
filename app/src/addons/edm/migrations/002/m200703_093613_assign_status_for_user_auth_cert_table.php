<?php

use yii\db\Migration;

/**
 * Class m200703_093613_assign_status_for_user_auth_cert_table
 */
class m200703_093613_assign_status_for_user_auth_cert_table extends Migration
{
    public function safeUp()
    {
        $uacList = \common\models\UserAuthCert::find()->orderBy(['id' => SORT_ASC])->all();
        $users = [];
        foreach($uacList as $uac) {
            $userId = $uac->userId;
            if (!isset($users[$userId])) {
                $uac->status = 'active';
                $users[$userId] = true;
            } else {
                $uac->status = 'inactive';
            }
            $uac->update(false, ['status']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200703_093613_assign_status_for_user_auth_cert_table cannot be reverted.\n";

        return false;
    }
}
