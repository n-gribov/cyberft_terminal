<?php

use addons\edm\models\ContractUnregistrationRequest\ContractUnregistrationRequestForm;
use common\models\listitem\AttachedFile;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/** @var View $this */
/** @var ContractUnregistrationRequestForm\AttachedFile[] $models */
// Создать таблицу для вывода
echo GridView::widget([
    'id' => 'attached-files-grid-view',
    'dataProvider' => new ArrayDataProvider([
        'allModels' => $models,
        'modelClass' => AttachedFile::class,
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
