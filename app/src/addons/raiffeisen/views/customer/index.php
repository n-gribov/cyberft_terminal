<?php

use addons\raiffeisen\models\RaiffeisenCustomer;
use common\widgets\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var $this \yii\web\View */
/** @var $dataProvider \yii\data\ActiveDataProvider */

$this->title = Yii::t('app/raiffeisen', 'Customers');

echo Html::a(
    Yii::t('app', 'Create'),
    Url::toRoute('create'),
    ['class' => 'btn btn-success']
);
?>

<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'rowOptions' => function (RaiffeisenCustomer $model, $key, $index, $grid) {
        return ['ondblclick' => "window.location='" . Url::toRoute(['view', 'id' => $model->id]) . "'"];
    },
    'columns' => [
        'id',
        'fullName',
        'inn',
        'isHoldingHead:boolean',
        'terminalAddress',
        [
            'class' => \yii\grid\ActionColumn::class,
            'template' => '{sendSettings} {update} {view} {delete}',
            'buttons' => [
                'sendSettings' => function ($url, RaiffeisenCustomer $model, $key) {
                    if (empty($model->terminalAddress)) {
                        return '';
                    }

                    return Html::a(
                        '<span class="glyphicon glyphicon-share" title="' . Yii::t('app/raiffeisen', 'Send configuration') . '"></span>',
                        Url::to(['send-client-terminal-settings', 'id' => $model->id])
                    );
                },
            ],
            'contentOptions' => ['class' => 'text-right'],
        ],
    ],
]);
