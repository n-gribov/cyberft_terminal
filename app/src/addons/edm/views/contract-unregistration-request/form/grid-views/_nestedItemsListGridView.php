<?php

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var string $id */
/** @var \common\models\listitem\NestedListItem[] $models */
/** @var string $modelClass */
/** @var array $dataColumns */

$createButton = function ($title, $class, $iconName) {
    return function ($url, $model, $key) use ($class, $iconName, $title) {
        return Html::a(
            Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]),
            '#',
            [
                'class' => $class,
                'title' => $title,
                'data' => ['id' => $model->id],
            ]
        );
    };
};

echo GridView::widget([
    'id' => $id,
    'dataProvider' => new ArrayDataProvider([
        'allModels' => $models,
        'modelClass' => $modelClass,
        'pagination' => false,
    ]),
    'showOnEmpty' => false,
    'emptyText' => '',
    'layout' => '{items}',
    'columns' => array_merge(
        $dataColumns,
        [
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => $createButton(Yii::t('yii', 'Update'), 'update-button', 'pencil'),
                    'delete' => $createButton(Yii::t('yii', 'Delete'), 'delete-button', 'trash'),
                ],
                'contentOptions' => ['class' => 'text-right text-nowrap', 'width' => '70px']
            ]
        ]
    ),
    'formatter' => [
        'class' => 'yii\i18n\Formatter',
        'decimalSeparator' => '.'
    ],
]);
