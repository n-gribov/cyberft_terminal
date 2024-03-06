<?php

use common\helpers\DocumentHelper;
use common\widgets\GridView;
use common\modules\monitor\models\MonitorLogAR;
use common\modules\monitor\models\MonitorLogSearch;
use common\helpers\DateHelper;

$currentEvents = DocumentHelper::getCurrentDocumentEventNames($model->id);

$searchModel  = new MonitorLogSearch();
$eventsDataProvider = $searchModel->searchDocument($model->id, Yii::$app->request->queryParams);

$formatter              = Yii::$app->formatter;
$formatter->nullDisplay = '';

echo GridView::widget([
    'formatter'    => $formatter,
    'emptyText'    => Yii::t('app/message', 'No events matched your query'),
    'summary'      => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
    'dataProvider' => $eventsDataProvider,
    'filterModel' => $searchModel,
    'columns'      => [
        [
            'attribute' => 'dateCreated',
            'label'     => Yii::t('app/message', 'Date'),
            'options'   => [
                'width' => 160
            ],
            'value' => function ($model) {
                return DateHelper::convertFromTimestamp($model->dateCreated, 'datetime');
            },
        ],
        [
            'attribute'      => 'eventCode',
            'headerOptions' => ['style' => 'width:200px;'],
            'filter' => $currentEvents,
            'value'          => function ($item) {
                return $item->getEventCodeLabel();
            },
        ],
        [
            'attribute' => 'initiatorType',
            'headerOptions' => ['style' => 'width:280px;'],
            'filter' => MonitorLogAR::getInitiatorTypelLabels(),
            'value' => function ($item) {

                $initiatorId = $item->event->initiatorType;

                if (!empty($initiatorId)) {
                    return MonitorLogAR::getInitiatorTypelLabels()[$item->event->initiatorType];
                } else {
                    return null;
                }

            },
        ],
        [
            'format' => 'html',
            'value'  => function ($model) {
                return $model->event->label;
            },
        ],
    ],
]);
