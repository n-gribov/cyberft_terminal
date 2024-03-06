<?php

use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\certManager\models\Cert */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'id'       => 'manage-cert',
	'method'	=> 'post',
	'options'	=> ['enctype' => 'multipart/form-data'],
]); ?>
<div class="row">
    <div class="col-md-6"><?= $form->field($model, 'terminalId')->textInput(['maxlength' => 12]) ?></div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'useBefore')->widget(
		DatePicker::className(),
		[
			'language' => 'ru',
			'dateFormat' => 'yyyy-MM-dd 23:59:59',
			'options' => ['class' => 'form-control']
		])
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'role')->dropDownList($model->roleLabels())?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'lastName')->textInput(['maxlength' => 64]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'firstName')->textInput(['maxlength' => 64]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'middleName')->textInput(['maxlength' => 64]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'post')->textInput(['maxlength' => 64]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'email')->input('email'); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'phone')->textInput(['maxlength' => 64]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
	<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Add') : Yii::t('app', 'Update'), [
        'id' => 'btn-manage-cert', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'
    ]) ?>
	<?= Html::a(Yii::t('app', 'Back'), ['cert/index', 'role' => $model->role], ['class' => 'btn btn-success']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>
