<?php

use common\document\Document;
use common\widgets\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\field\FieldRange;
use kartik\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use common\models\User;
use yii\web\View;

$this->title = Yii::t('app/menu', 'Documents for sending');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Documents'), 'url' => Url::toRoute(['/document/controller-verification'])];
$this->params['breadcrumbs'][] = $this->title;
if (Yii::$app->user->identity->role === User::ROLE_CONTROLLER) {
    $checkboxJS = '
    $("#controllerVerificationDocuments").on("change.yiiGridView", function() {
        var page = $(".pagination li.active a").attr("data-page");
        if (page === undefined) {
            page = 0;
        }
        var getSelectedRows = $(this).yiiGridView("getSelectedRows");
        var request = {
            checkedDocuments: getSelectedRows,
            pageId: parseInt(page) + parseInt(1),
        }
        $.post("' . Url::toRoute(['save-checked-documents']) . '",
            request,
            function(data) {
                console.log(data)
            }
        );
    });
    ';

    $this->registerJs($checkboxJS, View::POS_READY);
}
if (!isset($autobot)) {
    $autobot = NULL;
}
?>

<a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseSearch" aria-expanded="false" aria-controls="collapseSearch">
  <?= Yii::t('app', 'Search'); ?>
</a>&nbsp;
<?php if(!is_null($autobot)):?>
    <?php
            ActiveForm::begin([
                'action' => ['verify'],
                'method' => 'post',
                'options' => [
                    'style' => 'display: inline;'
                ]
            ]);

            echo Html::hiddenInput('action', 'accept');  
            echo Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-success']) . '&nbsp;';

            ActiveForm::end();
    ?>
    <?php
            ActiveForm::begin([
                'action' => ['verify'],
                'method' => 'post',
                'options' => [
                    'style' => 'display: inline;'
                ]
            ]);
            echo Html::hiddenInput('action', 'reject');
            echo Html::submitButton(Yii::t('app', 'Do not send'), ['class' => 'btn btn-danger']);

            ActiveForm::end();
    ?>
<?php endif;?>
<div class="<?= (empty($filterStatus)) ? 'collapse' : 'collapse.in'; ?>" id="collapseSearch">
    <?php
        $formParams = ['method' => 'get'];
        $form = ActiveForm::begin($formParams);
        if (!isset($aliases)) {
            $aliases = [];
        }
    ?>
    <div class="row log-search">
        <div class="col-md-6">
            <?= FieldRange::widget([
                'form' => $form,
                'model' => $searchModel,
                'label' => Yii::t('other', 'Document registration date'),
                'attribute1' => 'dateCreateFrom',
                'attribute2' => 'dateCreateBefore',
                'type' => FieldRange::INPUT_WIDGET,
                'widgetClass' => DateControl::classname(),
                'widgetOptions1' => [
                    'saveFormat' => 'php:Y-m-d',
                ],
                'widgetOptions2' => [
                    'saveFormat' => 'php:Y-m-d'
                ],
                'separator' => Yii::t('doc', '&larr; to &rarr;'),
            ]); ?>
        </div>
        <div class="col-xs-4">
        <?= $form->field($searchModel, 'searchBody')->label(Yii::t('other', 'Text search'))->textInput(['maxlength' => 200])?>
        </div>
        <div class="col-sm-2">
            <p style="margin-top:29px;"><?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?></p>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
$myGridWidget = GridView::begin([
    'id' => 'controllerVerificationDocuments',
    'emptyText'    => Yii::t('other', 'No documents matched your query'),
    'summary'      => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'rowOptions' => function ($model, $key, $index, $grid) {
        $options['ondblclick'] = "window.location='". Url::toRoute(['document/view', 'id'=>$model->id, 'from' => 'controller-verification']) ."'";

        if (in_array($model->status, array_merge(Document::getErrorStatus(),['']))) {
            $options['class'] = 'bg-alert-danger';
        } elseif (in_array($model->status, Document::getProcessingStatus())) {
            $options['class'] = 'bg-alert-warning';
        }

        return $options;
    },
    'columns'      => [
        [
            'class' => 'yii\grid\CheckboxColumn',
            'checkboxOptions' => function($model, $key, $index, $column) use($checkedDocuments, $page) {
                $checked = false;
                $hidden = false;
                if (isset($checkedDocuments[$page]) && in_array($key, $checkedDocuments[$page])) {
                    $checked = true;
                }
                return ['style' => "display: " . ($hidden ? 'none': 'block'), 'checked' => $checked];
            }
        ],
        [
            'attribute' => 'id',
            'headerOptions' => [
                'class' => 'text-right',
                'width' => '5%'
            ],
            'filterInputOptions' => [
                'style' => 'width:100%;float:right'
            ],
            'contentOptions' => [
                'class' => 'text-right'
            ],
        ],
        [
            'attribute' => 'type',
            'filter' => false,
            'headerOptions' => [
                'width' => '10%'
            ],
        ],
        [
            'attribute' => 'origin',
            'filter' => false,
            'headerOptions' => [
                'width' => '10%'
            ],
        ],
        [
            'attribute' => 'uuid',
            'headerOptions' => [
                'width' => '15%'
            ],
            'contentOptions' => [
                'nowrap' => 'nowrap'
            ],
        ],
        [
            'attribute' => 'sender',
            'headerOptions' => [
                'class' => 'text-right',
                'width' => '10%'
            ],
            'filterInputOptions' => [
                'style' => 'width:100%;float:right'
            ],
            'contentOptions' => [
                'class' => 'text-right'
            ],
        ],
        [
            'attribute' => 'receiver',
            'headerOptions' => [
                'class' => 'text-right',
                'width' => '10%'
            ],
            'filterInputOptions' => [
                'style' => 'width:100%;float:right'
            ],
            'contentOptions' => [
                'class' => 'text-right'
            ],
        ],
        [
            'attribute' => 'signaturesRequired',
            'headerOptions' => [
                'class' => 'text-right',
                'width' => '10%'
            ],
            'filterInputOptions' => [
                'style' => 'width:100%;float:right'
            ],
            'contentOptions' => [
                'class' => 'text-right'
            ],
        ],
        [
            'attribute' => 'signaturesCount',
             'headerOptions' => [
                'class' => 'text-right',
                'width' => '10%'
            ],
            'filterInputOptions' => [
                'style' => 'width:100%;float:right'
            ],
            'contentOptions' => [
                'class' => 'text-right'
            ],
        ],
        [
            'attribute' => 'dateCreate',
            'filter' => false,
            'headerOptions' => [
                'width' => '15%'
            ],
        ],
        [
            'attribute' => '',
            'format' => 'html',
            'headerOptions' => [
                'width' => '3%'
            ],
            'value'	=> function ($item, $params) {
                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::toRoute(['view', 'id' => $item->id, 'from' => 'controller-verification']));
            }
        ],

    ],
]);

$myGridWidget->formatter->nullDisplay = '';
$myGridWidget->end();
?>
