<?php

use common\helpers\DateHelper;
use common\modules\monitor\models\MonitorLogAR;
use common\widgets\InfiniteGridView;
use kartik\widgets\Select2;

$this->title = Yii::t('monitor', 'Events log');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">

<?php
// Создать таблицу для вывода
echo InfiniteGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'rowOptions' => function ($model, $key, $index, $grid) {
        $options = [
        //    'ondblclick' => "window.location='" . Url::toRoute(['view', 'id'=>$model->id]) . "'"
        ];

        if (in_array($model->logLevel, MonitorLogAR::getErrorStatus())) {
            $options['class'] = 'bg-alert-danger';
        } else if ($model->logLevel == MonitorLogAR::getWarningStatus()) {
            $options['class'] = 'bg-alert-warning';
        }

        return $options;
    },
    'columns'   => [
        [
            'attribute' => 'id',
            'headerOptions' => ['style' => 'width:50px;'],
        ],
        [
            'attribute' => 'dateCreated',
            'filter' => kartik\widgets\DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'dateCreated',
                'type' => \kartik\widgets\DatePicker::TYPE_INPUT,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true,
                    'orientation' => 'bottom'
                ],
                'options' => [
                    'class' => 'form-control',
                    'style' => 'width: 130px; text-align: right;',
                ],
            ]),
            'headerOptions' => ['style' => 'width:200px;'],
            'value' => function ($model) {
                return DateHelper::convertFromTimestamp($model->dateCreated, 'datetime');
            }
        ],
        [
            'attribute' => 'componentId',
            'headerOptions' => ['style' => 'width:200px;'],
            'value' => function ($model) {
                return $model->componentName;
            },
            'filter' => MonitorLogAR::getComponentLabels(),
            'filterInputOptions' => [
                'class' => 'form-control selectpicker',
                'data-width' => '180px',
                'data-none-selected-text' => ''
            ],
        ],
        [
            'attribute' => 'logLevel',
            'headerOptions' => ['style' => 'width:200px;'],
            'value' => function ($model) {
                return $model->event->logLevelLabel;
            },
            'filter' => MonitorLogAR::getLogLevelLabels(),
            'filterInputOptions' => [
                'class' => 'form-control selectpicker',
                'data-width' => "150px",
                'data-none-selected-text' => ''
            ],
        ],
        [
            'attribute' => 'eventCode',
            'headerOptions' => ['style' => 'width:200px;'],
            'format' => 'html',
            'value' => function ($item, $params) {
                return "<span title=\"{$item->eventCode}\">{$item->event->codeLabel}</span>";
            },
            'filter'    => $eventCodes,
            'filterInputOptions' => [
                'class' => 'form-control selectpicker',
                'data-width' => '150px',
                'data-none-selected-text' => ''
            ],
        ],
        [
            'attribute' => 'initiatorType',
            'headerOptions' => ['style' => 'width:200px;'],
            'value' => function ($model) {

                $initiatorId = $model->event->initiatorType;

                if (!empty($initiatorId)) {
                    return MonitorLogAR::getInitiatorTypelLabels()[$model->event->initiatorType];
                } else {
                    return null;
                }

            },
            'filter' => MonitorLogAR::getInitiatorTypelLabels(),
            'filterInputOptions' => [
                'class' => 'form-control selectpicker',
                'data-width' => '150px',
                'data-none-selected-text' => ''
            ],
        ],
        [
            'label' => Yii::t('monitor', 'Description'),
            'contentOptions' => ['style' => 'width: 100%; word-break:break-all;'],
            'value' => function ($model) {
                return $model->event->label;
            },
            'format'    => 'html'
        ],
        [
            'attribute' => 'terminalId',
            'value' => function($model) {
                $terminal = $model->terminal;
                return $model->terminal ? $terminal->terminalId : "";
            },
            'filter' => Select2::widget([
                'model' => $searchModel,
                'attribute' => 'terminalId',
                'data' => $terminals,
                'theme' => Select2::THEME_BOOTSTRAP,
                'options' => [
                    'prompt' => '',
            ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'containerCssClass' => 'select2-cyberft',
                    'width' => '180px'
                ],
            ]),
        ],
        [
            'attribute' => 'ip',
            'filterInputOptions' => ['style' => 'width:140px;'],
            'value' => function($model) {
                return $model->ip ?: '';
            }
        ],
    ]
]);
?>
    </div>
</div>
