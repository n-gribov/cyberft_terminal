<?php
// формируем meta-tag со всеми данными карточки
$options = [
	'id'    => 'dictContractorView',
	'class' => 'table table-striped table-bordered detail-view'
];
foreach ($model->attributes as $k => $v) {
	$options['data-' . $k] = $v;
}
$options['data-fullname'] = (string)$model;
foreach ($model->bank->attributes as $k => $v) {
	$options['data-bank.' . $k] = $v;
}
$options['data-bank.name'] = $model->bank->fullname;
$options['data-bank.fullname'] = $model->bank->fullname;
?>

<?=\yii\widgets\DetailView::widget([
	'model'      => $model,
	'options'    => $options,
	'attributes' => [
		'id',
        'typeLabel',
		[
			'attribute' => 'bankBik',
			'value'     => (string)$model->bank
		],
		'terminalId',
		[
			'attribute' => 'role',
			'value'     => $model->getRoleLabel()
		],
		'kpp',
		'inn',
		'account',
		'currency',
		'name',
	],
])?>