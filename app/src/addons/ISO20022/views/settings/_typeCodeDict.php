<?php

use yii\data\ArrayDataProvider;
use common\widgets\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="panel panel-body">
<?php
    echo Html::a(Yii::t('app/iso20022', 'Add new code'), Url::toRoute('update-code'), ['class' => 'btn btn-success']);
    // Создать таблицу для вывода
    echo GridView::widget([
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $settings->typeCodes,
        ]),
        'summary' => false,
        'columns' => [
            [
                'label' => 'Code',
                'value' => function($model, $key) {
                    return $key;
                }
            ],
            'ru',
            'en',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                                        Url::toRoute(['update-code', 'code' => $key]));
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash"></span>',
                            Url::toRoute(['delete-code', 'code' => $key]),
                            ['onClick' => 'return confirm("Are you sure you want to delete this entry?")']
                        );
                    }
                ],
            ]
        ]
    ])
?>
</div>
