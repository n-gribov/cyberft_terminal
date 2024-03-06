<?php

use common\base\interfaces\ServiceUserExtInterface;
use common\widgets\AdvancedTabs;

/** @var ServiceUserExtInterface $extModel */

$this->title = Yii::t('app', 'Additional settings for {modelClass}: ', [
    'modelClass' => Yii::t('app/user', 'user'),
]) . ' ' . $extModel->user->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'),  'url' => ['/user']];
$this->params['breadcrumbs'][] = ['label' => $extModel->user->name,   'url' => ['/user/view', 'id' => $extModel->user->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Update'), 'url' => ['/user/update', 'id' => $extModel->user->id]];
$this->params['breadcrumbs'][] = Yii::t('app/menu', $serviceName);


$data = [
    'action'     => 'tabMode',
    'defaultTab' => 'tabPermissions',
	'tabs' => [
		'tabPermissions' => [
			'label'   => Yii::t('app', 'Permissions'),
			'content' => '@common/views/user-ext/_tabPermissions',
		]
	],
];

echo AdvancedTabs::widget([
	'data' => $data,
	'params' => [
		'extModel' => $extModel,
	]
]);
