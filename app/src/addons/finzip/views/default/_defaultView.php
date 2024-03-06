<?php
$attributes = [
	'uuid',
	'dateCreate',
	'sender',
	'receiver',
	'statusLabel',
	'signaturesRequired',
	'signaturesCount'
];


echo \yii\widgets\DetailView::widget([
	'model' => $model,
	'attributes' => $attributes
]);
