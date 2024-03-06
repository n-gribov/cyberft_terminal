<?php

use addons\edm\models\DictBank;
use addons\edm\models\DictContractor;
use common\helpers\Currencies;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DictContractor */
/* @var $form ActiveForm */
if ($model->bankBik) {
	$bank = DictBank::findOne(['bik' => $model->bankBik]);
}

if (Yii::$app->request->get('emptyLayout')) {
    $disableCancel = true;
}

$js = "
    var contractorRole = $('#edmdictcontractor-role');
    var initRole = function () {
        var role = contractorRole.val();
        if (!role || role == '" . $model::ROLE_BENEFICIARY . "') {
            $('#edmdictcontractor-terminalid').attr('disabled', 'disabled');
        } else {
            $('#edmdictcontractor-terminalid').removeAttr('disabled');
        }
    };
    contractorRole.change(initRole);
    initRole();
";

$this->registerJs($js);
?>

<?php $form = ActiveForm::begin([
	'action' => Url::to([
		'dict-contractor/' . ($model->isNewRecord ? 'create' : 'update'),
		'emptyLayout' => Yii::$app->request->get('emptyLayout'),
		'id' => ($model->id?$model->id:null)
	]),
	'id' => '_edmDictContractor',
]); ?>

<?= $form->field($model, 'role')->dropDownList(
	[null => '']
	+ DictContractor::roleValues()
)?>

<?= $form->field($model, 'bankBik')->widget(Select2::classname(), [
	'id'            => 'edmdictcontractor-bankBik',
	'initValueText' => isset($bank)
		? 'БИК: ' . $bank->bik . ' Банк: ' . $bank->name
		: null,
	'options'       => [
		'placeholder' => Yii::t('app', 'Search for a {label}', ['label' => Yii::t('edm', 'bank by name or BIK')]),
		'style'       => 'width:100%',
	],
	'pluginOptions' => [
		'allowClear'         => true,
		'minimumInputLength' => 0,
		'ajax'               => [
			'url'      => Url::to(['dict-bank/list']),
			'dataType' => 'json',
			'data'     => new JsExpression('function(params) { return {q:params.term}; }')
		],
		'templateResult'     => new JsExpression('function(item) { if (!item.bik) return item.text; return "БИК: " + item.bik + " Банк:" + item.name; }'),
		'templateSelection'  => new JsExpression('function(item) { if (!item.bik) return item.text; return "БИК: " + item.bik + " Банк: " + item.name; }'),
	],
	'pluginEvents'  => [
		'select2:select' => 'function(e) {
			$("#edmdictcontractor-terminalid").val(e.params.data.terminalId);
		}',
	],
]) ?>

<?= $form->field($model, 'type')->dropDownList(DictContractor::typeValues()) ?>
<?= $form->field($model, 'kpp')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'inn')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'account')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'currency')->dropDownList(Currencies::getCodeLabels()) ?>
<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<div class="form-group">
	<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	<?= !isset($disableCancel) || !$disableCancel
		? Html::a(Yii::t('app', 'Cancel'), Url::to(['dict-contractor/index']),
                ['class' => 'btn btn-warning', 'id'    => 'cancelForm'])
		: null
	?>
</div>

<?php ActiveForm::end(); ?>
