<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\helpers\UserHelper;

?>

<p class="tab-buttons">
    <?php

    echo Html::a(Yii::t('app', 'Back'), Yii::$app->cache->get('user/settings-' . Yii::$app->session->id), ['class' => 'btn btn-default']);

    if (in_array(Yii::$app->user->identity->role, [User::ROLE_ADMIN, User::ROLE_ADDITIONAL_ADMIN, User::ROLE_LSO, User::ROLE_RSO])) {
        // Администратор не может удалить сам себя
        if ($model->id != Yii::$app->user->id && UserHelper::userProfileAccess($model, true)) {
            if ($model->status != User::STATUS_DELETED) {
                echo Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id],
                    [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app/user', 'Are you sure you want to delete this user?'),
                            'method' => 'post',
                        ],
                    ]);
            } else {
                echo Html::a(Yii::t('app', 'Restore'), ['restore', 'id' => $model->id],
                    [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app/user', 'Are you sure you want to restore this user?'),
                            'method' => 'post',
                        ],
                    ]);
            }
        }
    }

    if (( User::ROLE_ADMIN === Yii::$app->user->identity->role ||
            User::ROLE_ADDITIONAL_ADMIN === Yii::$app->user->identity->role ||
            Yii::$app->user->identity->isSecurityOfficer())
    && User::STATUS_INACTIVE === $model->status) {

        $data['method'] ='post';

        if (!User::canUseSecurityOfficers()) {
            $data = ArrayHelper::merge($data, ['confirm' => Yii::t('app/user', 'The system has no security officers! Are you sure you want to automatically activate user?')]);
        }

        if (UserHelper::userProfileAccess($model, true)) {
            echo Html::a(Yii::t('app/user', 'Activate'), ['activate', 'id' => $model->id],
                [
                    'class' => 'btn btn-warning',
                    'data' => $data
                ]);
        }
    }

    if (( User::ROLE_ADMIN === Yii::$app->user->identity->role ||
        User::ROLE_ADDITIONAL_ADMIN === Yii::$app->user->identity->role ||
        Yii::$app->user->identity->isSecurityOfficer())
    && User::STATUS_ACTIVE === $model->status) {
        if (UserHelper::userProfileAccess($model, true)) {
            echo Html::a(Yii::t('app/user', 'Block'), ['block', 'id' => $model->id],
                [
                    'class' => 'btn btn-danger',
                ]);
        }
    }

    if (User::canUseSecurityOfficers() && Yii::$app->user->identity->isSecurityOfficer()) {
        if (Yii::$app->user->identity->canActivateApprove($model->id) && User::STATUS_ACTIVATING === $model->status) {
        echo Html::a(Yii::t('app/user', 'Approve'), ['secure-officer-approve', 'id' => $model->id],
            [
                'class' => 'btn btn-success',
                'data' => [
                    'method' => 'post',
                ],
            ]);
        }

        if (Yii::$app->user->identity->canSettingApprove($model->id) && User::STATUS_APPROVE === $model->status) {
            echo Html::a(Yii::t('app/user', 'Approve new settings'), ['secure-officer-settings-approve', 'id' => $model->id],
                [
                    'class' => 'btn btn-success',
                    'data' => [
                        'method' => 'post',
                    ],
                ]);
        }
    }
    ?>
</p>
