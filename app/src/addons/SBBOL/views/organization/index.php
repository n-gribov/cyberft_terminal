<?php

use addons\SBBOL\models\SBBOLOrganization;
use common\helpers\Html;
use common\widgets\GridView;
use yii\helpers\Url;

/** @var $this \yii\web\View */
/** @var $dataProvider \yii\data\ActiveDataProvider */

$this->title = Yii::t('app/sbbol', 'Organizations');

echo Html::button(
    Yii::t('app/sbbol', 'Register holding'),
    [
        'class' => 'btn btn-success',
        'style' => 'margin-bottom: 15px',
        'data'  => ['toggle' => 'modal', 'target' => '#register-holding-modal']
    ]
);
// Создать таблицу для вывода
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'rowOptions' => function ($model, $key, $index, $grid) {
        return ['ondblclick' => "window.location='" . Url::toRoute(['view', 'inn' => $model->inn]) . "'"];
    },
    'columns' => [
        ['class' => \yii\grid\SerialColumn::class],
        'fullName',
        'inn',
        'terminalAddress',
        [
            'class' => \yii\grid\ActionColumn::class,
            'template' => '{sendSettings} {update} {view}',
            'contentOptions' => ['class' => 'text-right'],
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-eye-open" title="' . Yii::t('app', 'View') . '"></span>',
                        Url::toRoute(['view', 'inn' => $model->inn])
                    );
                },
                'update' => function ($url, $model, $key) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-pencil" title="' . Yii::t('app', 'Update') . '"></span>',
                        Url::toRoute(['update', 'inn' => $model->inn])
                    );
                },
                'sendSettings' => function ($url, $model, $key) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-share" title="' . Yii::t('app/sbbol', 'Send configuration') . '"></span>',
                        Url::to(['send-client-terminal-settings', 'inn' => $model->inn])
                    );
                },
            ],
            'visibleButtons' => [
                'view' => function (SBBOLOrganization $model, $key, $index) {
                    return true;
                },
                'sendSettings' => function (SBBOLOrganization $model, $key, $index) {
                    return !empty($model->terminalAddress);
                },
            ]
        ],
    ],
]);

// Вывести модальное окно
echo $this->render('_registerHoldingModal');
