<?php

use common\modules\autobot\models\Autobot;
use common\modules\certManager\models\UserKeyForm;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\MaskedInput;
use yii\jui\DatePicker;

/* @var $this View */
/* @var $model Autobot */
/* @var $form ActiveForm */
?>
<div class="autobot-create">
	<div class="autobot-form">
		<div class="row">
			<div class="col-sm-6">

		<?php $form = ActiveForm::begin([
                'action' => isset($action) ? $action : null,
				'method'	=> 'post',
				'type' 		=> ActiveForm::TYPE_HORIZONTAL,
				'formConfig' => [
					'labelSpan' => 4,
				]
		]); ?>

        <?php if (!($model instanceof UserKeyForm)) : ?>

        <?php
            $options = [];
            // Если в get-параметрах есть id терминала, то выбираем его
            $terminalId = Yii::$app->request->get('terminalId');
            if ($terminalId) {
                $options['options'] = [
                    $terminalId => [
                        'selected ' => true
                    ]
                ];
            }
        ?>

		<?= $form->field($model, 'terminalId')->dropDownList($terminalIds, $options); ?>
		<?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>
		<?= $form->field($model, 'primary')->dropDownList($model->primaryLabels(), []) ?>

        <?php endif ?>

		<?= $form->field($model, 'countryName')->widget(MaskedInput::className(), ['mask' => 'aa']) ?>
		<?= $form->field($model, 'stateOrProvinceName')->textInput(['maxlength' => 64]) ?>
		<?= $form->field($model, 'localityName')->textInput(['maxlength' => 64]) ?>
		<?= $form->field($model, 'organizationName')->textInput(['maxlength' => 64]) ?>
		<?= $form->field($model, 'commonName')->textInput(['maxlength' => 64]) ?>

		<div class="form-group">
			<label class="col-md-4 control-label"><?=Yii::t('other', 'Private key password')?></label>
			<div class="col-md-8">
				<input type="password" class="form-control" name="password" value="" required="true"/>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 control-label"><?=Yii::t('other', 'Confirm password')?></label>
			<div class="col-md-8">
				<input type="password" class="form-control" name="password_repeat" value="" required="true"/>
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-offset-4 col-md-8">
				<?= Html::submitButton(Yii::t('app/autobot', 'Create key'), ['class' => 'btn btn-success']) ?>
			</div>
		</div>

		<?php ActiveForm::end(); ?>

			</div>
		</div>
	</div>
</div>
