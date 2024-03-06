<?php

use common\helpers\DateHelper;
use common\helpers\Html;
use common\models\User;
use common\widgets\GridView;
use yii\helpers\Url;

$searchModel = $data['searchModel'];
$dataProvider = $data['dataProvider'];
$terminalId = $data['terminalId'];


// Очистить кэш обратной ссылки для настроек пользователя
if (Yii::$app->cache->exists('user/settings-' . Yii::$app->session->id)) {
    Yii::$app->cache->delete('user/settings-' . Yii::$app->session->id);
}
?>
<p>
    <?= Html::a(Yii::t('app', 'Create'), ['/user/create?addTerminalId=' . $terminalId], ['class' => 'btn btn-success']) ?>
</p>
<?php
// Создать таблицу для вывода
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'rowOptions'	=> function($model, $key, $index, $grid) {
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
            'urlCreator' => function ($action, $model, $key, $index) {
                if ($action === 'view') {
                    return Url::to(['/user/view', 'id' => $model->id]);
                } else if ($action === 'update') {
                    return Url::to(['/user/update', 'id' => $model->id]);
                } else if ($action === 'delete') {
                    return Url::to(['/user/delete', 'id' => $model->id]);
                }
            },
            'contentOptions' => [
                'style' => 'min-width: 125px;'
            ]
        ]
    ],
]);

