<?php

use common\widgets\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<p class="tab-buttons">
    <?= Html::a(Yii::t('app', 'Create'), ['/swift-route/create'], ['class' => 'btn btn-success']) ?>
</p>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        'id',
        'address',
        'info:ntext',

        [
            'class' => 'yii\grid\ActionColumn',
            'urlCreator' => function($action, $model, $key, $index ) {
                return Url::toRoute(['/swift-route/' . $action, 'id' => $model->id]);
            },
        ],
    ],
]); ?>
