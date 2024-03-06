<?php
use common\widgets\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

$request      = Yii::$app->request;

?>

<h3><?=Yii::t('app', 'Upload new file')?></h3>

<div class="row row-margin-bottom">
    <div class="col-md-6">

        <?php
            $form = ActiveForm::begin([
                'method'	=> 'post',
                'action'	=> 'step4',
                'options'	=> ['name' => 'step3Form',],
            ]);
            echo Html::hiddenInput('wizardComplete', 1);
            ActiveForm::end();
        ?>
        <?php
            $formFile = ActiveForm::begin([
                'method'	=> 'post',
                'action'	=> 'step3',
                'options'	=> ['enctype' => 'multipart/form-data', 'name' => 'step3Upload',],
            ]);

            echo kartik\file\FileInput::widget([
                'name'  => 'file[]',
                'pluginOptions' => [
                    'showPreview' => false,
                    'showUpload' => false,
                    'showDelete' => false
                ],
                'options' => ['multiple' => true],
                'pluginEvents' => [
                    'change' => 'function() {step3Upload.submit();}'
                ]
            ]);

            ActiveForm::end();
        ?>

        <?php if ($model->hasErrors('file')) :?>
            <div class="alert alert-danger" style="margin-top:10px;">
                <p><?=nl2br($model->getFirstError('file'))?></p>
            </div>
        <?php endif ?>
    </div>
</div>
<?php if (isset($dataProvider) && $dataProvider->totalCount) : ?>
    <div class="row">
        <div class="col-md-6">
            <?=GridView::widget([
                'dataProvider' => $dataProvider,
                'columns'      => [
                    [
                        'class'    => 'yii\grid\SerialColumn',
                        'headerOptions' => [
                            'style' => 'width: 100px'
                        ]
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
        </div>
    </div>
<?php endif ?>

<div class="row">
	<div class="row col-md-6">
		<div class="col-md-offset-4 col-md-8">
			<?=Html::a(Yii::t('app', 'Back'),	['/finzip/wizard/step2'], ['class' => 'btn btn-default'])?>
			<?=Html::button(Yii::t('app', 'Next'), ['class' => 'btn btn-primary', 'onClick' => 'step3Form.submit();'])?>
		</div>
	</div>
</div>
