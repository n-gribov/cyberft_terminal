<?php
use addons\swiftfin\models\SwiftFinUserExt;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var SwiftFinUserExt $model */

$form = ActiveForm::begin([
	'action' => Url::toRoute('update-role', ['method' => 'post'])
])
?>
<div class="panel-body">
	<?=Html::hiddenInput('id', $extModel->id)?>
	<div class="row">
		<div class="col-lg-5">
		<?=$form->field($extModel, 'role')
			->dropDownList($extModel::roleLabels());
		?>
		</div>
	</div>
	<div class="row">
		<input type="submit" value="<?=Yii::t('app', 'Save')?>" class="btn btn-success" style="margin-left:10px"/>
	</div>

</div>
<?php
ActiveForm::end();
?>
