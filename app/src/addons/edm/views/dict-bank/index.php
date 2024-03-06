<?php

use addons\edm\models\DictBankSearch;
use kartik\form\ActiveForm;
use kartik\widgets\FileInput;
use yii\data\ActiveDataProvider;
use common\widgets\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use common\widgets\InlineHelp\InlineHelp;

/* @var $this View */
/* @var $searchModel DictBankSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app/menu', 'Banks Directory');
$this->params['breadcrumbs'][] = ['label' => Yii::t('edm', 'Banking'), 'url' => Url::toRoute(['/edm'])];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pull-right">
    <?=InlineHelp::widget(['widgetId' => 'edm-dict-bank-journal', 'setClassList' => ['edm-journal-wiki-widget']]);?>
</div>


<?php if (Yii::$app->user->can('admin')) : ?>

<?php $form = ActiveForm::begin([
    'enableClientValidation' => false,
    'enableAjaxValidation'   => false,
    'method' => 'post',
    'action' => Url::to(['sync-file']),
    'options' => [
        'enctype'=>'multipart/form-data'
    ],
    'formConfig' => [
        'labelSpan'  => 3,
        'deviceSize' => ActiveForm::SIZE_TINY,
        'showErrors' => true,
    ]
]) ?>
<table class="table" style="margin: 0">
    <thead>
        <td width="20%"><?=Html::label(Yii::t('app', 'Choose file for upload'), 'file')?></td>
        <td width="40%"<?=isset($errors['file']) ? ' class="has-error"' : null ?>>
            <?= FileInput::widget([
                'name' => 'file',
                'pluginOptions' => [
                    'showPreview' => false,
                    'showCaption' => true,
                    'showRemove' => true,
                    'showUpload' => false,
                    'browseLabel' =>  Yii::t('app', 'Browse'),
                ],
            ])?>
            <?php if (!empty($errors)) : ?>
                <div class="help-block">
                    <?=implode('<br/>', (array)$errors['file']) ?>
                </div>
            <?php endif ?>
        </td>
        <td width="10%"><?=Html::submitButton(Yii::t('app', 'Upload'), ['name' => 'send', 'class' => 'btn btn-success'])?></td>
        <td></td>
    </thead>
</table>
<?php $form->end() ?>

<?php endif ?>

<?php

$columns = [
    [
        'class' => 'yii\grid\SerialColumn',
        'headerOptions' => [
            'class' => 'text-right',
        ],
        'contentOptions' => [
            'class' => 'text-right',
        ],
    ],
    [
        'attribute' => 'bik',
        'headerOptions' => [
            'class' => 'text-right',
        ],
        'filterInputOptions' => [
            'maxLength' => 9,
            'style'     => 'float:right;'
        ],
        'contentOptions' => [
            'class' => 'text-right',
        ],
    ],
    [
        'attribute' => 'account',
        'filterInputOptions' => [
            'maxLength' => 20,
            'style' => 'width: 100%'
        ],
    ],
    [
        'attribute' => 'name',
        'filterInputOptions' => [
            'maxLength' => 255,
            'style' => 'width: 100%'
        ],
    ],
    [
        'attribute' => 'city',
        'filterInputOptions' => [
            'maxLength' => 20,
            'style' => 'width: 100%'
        ],
    ],
    'terminalId',
];

$columns[] = [
    'class' => 'yii\grid\ActionColumn',
    'template' => '{view}',
];

if (Yii::$app->user->can('admin')) {
    $columns[] = [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{update}',
    ];
}
// Создать таблицу для вывода
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'rowOptions' => function ($model){
        $options['ondblclick'] = "window.location='".
            Url::toRoute(['view', 'id' => $model->bik]) ."'";

        return $options;
    },
    'columns' => $columns,
]);

?>
