<?php

use addons\swiftfin\models\SwiftFinUserExt;
use kartik\widgets\Select2;
use yii\bootstrap\ActiveForm;
use common\widgets\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var SwiftFinUserExt $model */

$form = ActiveForm::begin([
	'action' => Url::toRoute('update-role-settings', ['method' => 'post'])
])
?>
<div class="panel-body">
	<?=Html::activeHiddenInput($model, 'userExtId')?>
	<?=Html::activeHiddenInput($model, 'id')?>
	<?=Html::hiddenInput('tabMode', Yii::$app->request->get('tabMode'))?>
	<div class="row">
		<div class="col-lg-3">
		<?=$form->field($model, 'docType')
			->widget(Select2::classname(), [
				'data' => $docTypeSelect,
				'pluginLoading' => false,
			]);
		?>
		</div>
		<div class="col-lg-1">
		<?=$form->field($model, 'currency')
			->widget(Select2::classname(), [
				'data' => $currencySelect,
				'pluginLoading' => false,
			]);
		?>
		</div>
	</div>
	<div class="row" style="clear:both">
		<div class="col-lg-2">
			<?=$form->field($model, 'minSum')->textInput()?>
		</div>
		<div class="col-lg-2">
			<?=$form->field($model, 'maxSum')->textInput()?>
		</div>
	</div>
	<div class="row" style="clear:both">
		<input type="submit" value="<?=Yii::t('app', 'Add')?>" class="btn btn-success" style="margin-left:10px"/>
	</div>
</div>
<?php
ActiveForm::end();
?>
<div class="panel-body">
<?php
echo GridView::widget([
	'dataProvider' => $dataProvider,
    'emptyText' => Yii::t('app/user', 'No conditions are defined - this user will be able to authorize any document'),
    'emptyTextOptions' => ['class' => 'alert alert-info'],
	'summary' => false,
	'columns' => [
		'docType',
		'currency',
		[
            'attribute' => 'minSum',
            'value' => function($item) {
                return $item->minSum == 0 ? null : $item->minSum;
            }
        ],
		[
            'attribute' => 'maxSum',
            'value' => function($item) {
                return $item->maxSum == 0 ? null : $item->maxSum;
            }
        ],

		[
			'class' => '\yii\grid\ActionColumn',
			'options' => ['style' => 'width: 30px'],
			'template' => '{delete-role-setting}',
			'buttons' => [
				'delete-role-setting' => function ($url, $item, $key) use($extModel) {
					return Html::a('<span class="glyphicon glyphicon-trash"></span>',
							Url::toRoute(['delete-role-setting',
                                'userId' => $extModel->userId,
                                'id' => $item->id,
								'tabMode' => Yii::$app->request->get('tabMode')]));
				}
			]
		]
	]
]);
?>
</div>
