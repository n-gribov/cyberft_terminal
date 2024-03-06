<?php

use common\widgets\GridView;

/** @var $this \yii\web\View */
/** @var $dataProvider \yii\data\ActiveDataProvider */

$this->title = Yii::t('app/menu', 'Logs');

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'dateCreate',
        'name',
        [
            'attribute' => 'body',
            'value' => function($model) {
                return str_replace(['&amp;gt;', '&amp;lt;'], ['&gt;', '&lt'], $model->body);
            }
        ],
        [
            'attribute' => 'responseBody',
            'value' => function($model) {
                return str_replace(['&amp;gt;', '&amp;lt;'], ['&gt;', '&lt'], $model->responseBody);
            }
        ],
    ],
]);

