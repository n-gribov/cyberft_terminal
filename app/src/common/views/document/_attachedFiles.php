<?php
use yii\data\ArrayDataProvider;
use common\widgets\GridView;
use yii\helpers\Html;

/** @var \yii\web\View $this */
// Создать таблицу для вывода
echo GridView::widget([
    'id' => 'attached-files-grid-view',
    'dataProvider' => new ArrayDataProvider([
        'allModels' => $models,
        'modelClass' => $modelClass,
        'pagination' => false,
    ]),
    'showOnEmpty' => false,
    'emptyText' => '',
    'layout' => '{items}',
    'columns' => [
        'name',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url, $model, $key) {
                    return Html::a(
                        Html::tag('span', '', ['class' => 'glyphicon glyphicon-trash']),
                        '#',
                        [
                            'class' => 'delete-button',
                            'title' => Yii::t('yii', 'Delete'),
                            'data' => ['id' => $model->id],
                        ]
                    );
                }
            ],
            'contentOptions' => ['class' => 'text-right text-nowrap', 'width' => '35px']
        ]
    ],
]);
