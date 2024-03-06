<?php

use common\document\Document;
use common\helpers\SigningHelper;
use common\widgets\GridView;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin([
    'method' => 'post',
    'action' => 'step2',
    'options' => [
        'id' => 'step2Form',
        'enctype' => 'multipart/form-data'
    ],
]);

?>

<div class="row">
    <div class="col-md-8">
        <?php
            echo $form->field($model, 'subject')->textInput();
            echo $form->field($model, 'descr')->textarea(['rows' => 15]);
        ?>
        <?php if ($model->hasErrors('file')) :?>
            <div class="alert alert-danger" style="margin-top:10px;">
                <p><?=nl2br($model->getFirstError('file'))?></p>
            </div>
        <?php endif ?>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <?php if (isset($dataProvider) && $dataProvider->totalCount) : ?>
            <?php
            // Создать таблицу для вывода
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns'      => [
                    [
                        'class'    => 'yii\grid\SerialColumn',
                    ],
                    [
                        'attribute' => 'fileName',
                        'label' => Yii::t('app', 'File name')
                    ],
                    [
                        'class'    => 'yii\grid\ActionColumn',
                        'template' => '{delete}',
                    ],
                ],
            ])?>
        <?php endif ?>
    </div>
</div>

<div class="fileupload-buttonbar">
    <label id="upload-file-button" class="btn btn-primary" style="overflow:hidden; position:relative;">
        <span class="glyphicon glyphicon-folder-open" style="margin-right:10px"></span><span><?=
            Yii::t('app', 'Add Files') . ' ...'
        ?></span>
        <input id="file" type="file" multiple="true" name="file[]" onchange = "showFileUpload()"/>
    </label>
    <button id="upload-file-button-disabled" type="button" class="btn btn-outline-primary" style="display: none;" disabled><img src="/img/spinner.gif" style="margin-right:5px;"> Загрузка файла ...</button>
</div>

<?php ActiveForm::end(); ?>

<?php
    if ($signNum == 0) {
        $btnTextForSignAndSend = Yii::t('app', 'Send');
    } else if (($signNum == 1) && ($userCanSignDocuments === true)) {
        $btnTextForSignAndSend = Yii::t('doc', 'Sign and send');
    } else {
        $btnTextForSignAndSend = Yii::t('doc', 'Create document');
    }

    $btnTitle = SigningHelper::isSignatureRequired(
            Document::ORIGIN_WEB,
            'FINZIP',
            Yii::$app->exchange->defaultTerminal->terminalId
        )
            ? $btnTextForSignAndSend
            : Yii::t('app', 'Send');
?>
<div class="row">
    <div class="row col-md-8">
        <div class="col-md-offset-4 col-md-8">
            <?=Html::a(Yii::t('app', 'Back'),	['index'], ['class' => 'btn btn-default'])?>
            <?=Html::button($btnTitle, ['class' => 'btn btn-success', 'id' => 'send-message-button', 'onClick' => 'step2Form.submit();'])?>
        </div>
    </div>
</div>

<?php
$jsCode = <<<JS
function showFileUpload() {
  document.querySelector('#step2Form').submit();
  document.querySelector('#upload-file-button-disabled').style.display = 'block';
  document.querySelector('#upload-file-button').style.display = 'none';
  document.querySelector('#send-message-button').className = 'btn btn-secondary disabled';
  document.querySelector('#send-message-button').setAttribute('disabled', 'disabled');
};
JS;

$this->registerJs($jsCode, yii\web\View::POS_HEAD);

$this->registerCss('
    .finzip-files-block {
        margin-bottom: 20px;
    }
');

?>
