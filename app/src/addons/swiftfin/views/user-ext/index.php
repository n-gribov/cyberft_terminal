<?php

use addons\swiftfin\models\SwiftFinUserExt;
use common\widgets\AdvancedTabs;

$this->title = Yii::t('app', 'Additional settings for {modelClass}: ', [
    'modelClass' => Yii::t('app/user', 'user'),
]) . ' ' . $extModel->user->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['/user']];
$this->params['breadcrumbs'][] = ['label' => $extModel->user->name, 'url' => ['/user/view', 'id' => $extModel->user->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Update'), 'url' => ['/user/update', 'id' => $extModel->user->id]];
$this->params['breadcrumbs'][] = Yii::t('app/menu', 'SwiftFin');

/** @var SwiftFinUserExt $model */


$data = [
    'action' => 'tabMode',
    'defaultTab' => 'tabRole',
	'tabs' => [
		'tabRole' => [
			'label' => Yii::t('app', 'Role'),
			'content' => '@addons/swiftfin/views/user-ext/_tabRole',
		]
	],
];

if ($extModel->role == SwiftFinUserExt::ROLE_AUTHORIZER) {
	$data['tabs']['tabRoleSettings'] = [
		'label' => Yii::t('app', 'Role settings'),
		'content' => '@addons/swiftfin/views/user-ext/_tabRoleSettings',
	];
}

echo AdvancedTabs::widget([
	'data' => $data,
	'params' => [
		'model' => $model,
		'extModel' => $extModel,
		'dataProvider' => $dataProvider,
		'currencySelect' => $currencySelect,
		'docTypeSelect' => $docTypeSelect
	]
]);