<?php

use kartik\widgets\ActiveForm;
use yii\helpers\Html;

$request = Yii::$app->request;
?>

<h3><?=Yii::t('app', 'Upload files')?></h3>

<?php
    $form = ActiveForm::begin([
        'method' => 'post',
        'options' => [
            'name' => 'step2Form',
            'enctype' => 'multipart/form-data'
        ],
    ]);

    $pduButtonTitle = Yii::t('app/fileact', 'Upload PDU file') . ' ...';
    $pduButton = <<<BTN
<div class="fileupload-buttonbar">
    <label class="btn btn-primary col-lg-6" style="overflow: hidden; position:relative;">
        <span class="glyphicon glyphicon-folder-open" style="margin-right:10px; color:white"></span><span>
            {$pduButtonTitle}
        </span>
        <input id="file" type="file" name="{$model->formName()}[fileXml]" onchange = "step2Form.submit();"/>
    </label>
</div>
BTN
;

    $binButtonTitle = Yii::t('app/fileact', 'Upload BIN file') . ' ...';
    $binButton = <<<BTN
<div class="fileupload-buttonbar">
    <label class="btn btn-primary col-lg-6" style="overflow: hidden; position:relative;">
        <span class="glyphicon glyphicon-folder-open" style="margin-right:10px; color:white"></span><span>
            {$binButtonTitle}
        </span>
        <input id="file" type="file" name="{$model->formName()}[fileBin]" onchange = "step2Form.submit();"/>
    </label>
</div>
BTN
;

// Создать детализированное представление
echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'label' => 'PDU',
            'format' => 'raw',
            'value' => '<span class="col-lg-4">'
                . ($model->getFile('xml') ? $model->getFile('xml')['fileName'] : 'Файл не загружен')
                . '</span>'
                . '<span class="col-lg-6">'
                . $pduButton
                . '</span>'
        ],
        [
            'label' => Yii::t('app/fileact', 'BIN file'),
            'format' => 'raw',
            'value' => '<span class="col-lg-4">'
                . ($model->getFile('bin') ? $model->getFile('bin')['fileName'] : 'Файл не загружен')
                . '</span>'
                . '<span class="col-lg-6">'
                . $binButton
                . '</span>'

        ]
    ]
])
?>

<?php if (!empty($error)) : ?>
    <div class="alert alert-danger">
        <p><?= nl2br($error) ?></p>
    </div>
<?php endif ?>

<?php ActiveForm::end(); ?>

<div class="row">
    <div class="row col-md-8">
        <div class="col-md-offset-4 col-md-8">
            <?= Html::a(
                Yii::t('app', 'Back'),
                ['index'],
                ['class' => 'btn btn-default']
            ) ?>
            <?= Html::a(
                Yii::t('app', 'Send'),
                ['step3'],
                ['class' => 'btn btn-success']
            ) ?>
        </div>
    </div>
</div>
