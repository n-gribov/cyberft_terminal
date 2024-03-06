<?php

use common\modules\participant\models\BICDirParticipant;
use yii\data\ActiveDataProvider;
use common\widgets\GridView;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app/participant', 'CyberFT Network Members List');
$this->params['breadcrumbs'][] = $this->title;

if (!empty($event)) {
    echo $event->getLabel() . '<br><br>';
}

if (Yii::$app->user->can('admin')) {
    // Вывести форму отправки запроса
    echo $this->render('_sendRequestForm');
}

$configGV = [
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'rowOptions' => function ($model) use($urlParams) {
        $options['ondblclick'] = "window.location='".
            Url::toRoute(array_merge(['view', 'participantBIC' => $model->participantBIC], $urlParams)) . "'";

        if ($model->status == BICDirParticipant::STATUS_BLOCKED) {
            $options['class'] = 'danger';
        }

        return $options;
    },
    'formatter' => [
        'class' => 'yii\i18n\Formatter',
        'nullDisplay' => '',
    ],

    'columns' => [
        'participantBIC',
        'providerBIC',
        'name',
        'institutionName',
        [
            'attribute' => 'status',
            'filter' => BICDirParticipant::statusLabels(),
            'value' => function($rowModel) {
                return $rowModel->statusLabel;
            },
            'contentOptions' => [
                'style' => 'width: 120px',
            ],
            'filterInputOptions' => [
                'class' => 'form-control selectpicker',
                'data-width' => '104px',
                'data-none-selected-text' => ''
            ],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update} {view}',
            'urlCreator' => function ($action, $model) {
                return Url::toRoute([$action, 'participantBIC' => $model->participantBIC]);
            }
        ],
    ]
];
// Создать таблицу для вывода
echo GridView::widget($configGV);
