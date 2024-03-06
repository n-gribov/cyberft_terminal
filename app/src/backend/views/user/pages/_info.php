<?php

use yii\widgets\DetailView;
use common\helpers\UserHelper;
use common\models\User;
use common\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */
?>
<div class="info-buttons">
<?php
    // Получить роль пользователя из активной сессии
    if (in_array(Yii::$app->user->identity->role, [User::ROLE_ADMIN, User::ROLE_ADDITIONAL_ADMIN, User::ROLE_LSO, User::ROLE_RSO])) {
        if (UserHelper::canUpdateProfile($model->id) && UserHelper::userProfileAccess($model, true)) {
            echo Html::a(Yii::t('app', 'Update'), ['user/update', 'id' => $model->id], ['class' => 'btn btn-primary']);
        }
    }
?>
</div>
<?php
// Создать детализированное представление
echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'email:email',
        [
            'attribute' => 'name',
            'label' => Yii::t('app/user', 'Name'),
        ],
        'roleLabel',
        'signatureNumberLabel',
        'signatureLevelLabel',
        'statusLabel',
        'created_at:datetime',
        'updated_at:datetime',
        'activationUserKey',
        [
            'attribute' => 'ownerId',
            'value' => ($model && $model->owner) ? $model->owner->name : ""
        ],
        [
            'attribute' => 'authType',
            'value' => $model->getAuthTypeLabel()
        ]
    ],
]);
