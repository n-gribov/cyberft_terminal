<?php

use backend\models\search\UserSearch;
use common\helpers\DateHelper;
use common\helpers\Html;
use common\helpers\UserHelper;
use common\models\User;
use common\widgets\GridView;
use yii\data\ActiveDataProvider;
use yii\web\View;

/* @var $this View */
/* @var $searchModel UserSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;

$permissionsCache = [];
$canEditUser = function (User $user) use ($permissionsCache) {
    if (!isset($permissionsCache[$user->id])) {
        // Получить роль пользователя из активной сессии
        $isAdmin = in_array(\Yii::$app->user->identity->role, [User::ROLE_ADMIN, User::ROLE_ADDITIONAL_ADMIN]);
        $permissionsCache[$user->id] = $isAdmin && UserHelper::userProfileAccess($user, true);
    }
    return $permissionsCache[$user->id];
};
$canDeleteUser = function (User $user) use ($canEditUser) {
    return \Yii::$app->user->id != $user->id && $canEditUser($user);
};

echo Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success', 'style' => 'margin-bottom: 15px']);
// Создать таблицу для вывода
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'rowOptions' => function($model, $key, $index, $grid) {
        if (User::STATUS_DELETED === $model->status) {
            return ['class'=>'danger'];
        }
        return[];
    },
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'email:email',
        [
            'attribute' => 'name',
            'label' => Yii::t('app/user', 'Name'),
        ],
        [
            'attribute' => 'role',
            'value'	=> function ($model, $key, $index, $column) {
                return $model->roleLabel;
            }
        ],
        [
            'attribute' => 'signatureNumber',
            'value'	=> function ($model, $key, $index, $column) {
                return $model->signatureNumberLabel;
            }
        ],
        'statusLabel',
        [
            'attribute' => 'created_at',
            'filter' => false,
            'value' => function($model) {
                return DateHelper::convertFromTimestamp($model->created_at, 'datetime');
            }
        ],
        [
            'attribute' => 'updated_at',
            'filter' => false,
            'value' => function($model) {
                return DateHelper::convertFromTimestamp($model->updated_at, 'datetime');
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {update} {delete}',
            'visibleButtons' => [
                'view' => true,
                'update' => $canEditUser,
                'delete' => $canDeleteUser,
            ],
            'contentOptions' => [
                'style' => 'min-width: 125px;'
            ]
        ]
    ],
]);
