<?php

use addons\ISO20022\models\form\WizardForm;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\web\View;
use common\widgets\Participants\ParticipantsWidget;

/* @var $model WizardForm */
/* @var $form ActiveForm */
/* @var $this View */
?>
	<div class="row">
		<div class="col-sm-6">
			<?php $form = ActiveForm::begin([
				'type'       => ActiveForm::TYPE_HORIZONTAL,
				'fullSpan'   => 12,
				'formConfig' => [
					'labelSpan' => 3
				],
                'options' => ['class' => 'iso-form']
			]) ?>
            <?=ParticipantsWidget::widget([
                'form' => $form,
                'model' => $model
            ])?>
			<?=$form->field($model, 'terminalCode')->dropDownList([], [
				'id'    => 'wizardform-terminal-code',
				'class' => 'form-control',
			]);?>
			<div class="form-group">
				<div class="col-md-offset-3 col-sm-3">
					<?=Html::submitButton(Yii::t('app', 'Next'),
                        ['name'  => 'send', 'class' => 'btn btn-primary btn-block'])?>
				</div>
			</div>
		</div>
	</div>
<?php ActiveForm::end() ?>