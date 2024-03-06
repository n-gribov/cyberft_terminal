<?php

use common\widgets\AdvancedTabs;

$data = [
	'query' => 'swiftfin/settings',
	'action' => 'tabMode',
	'defaultTab' => 'tabGeneral',
	'tabs' => [
		'tabGeneral' => [
			'label' => Yii::t('app/terminal', 'General'),
			'content' => '@addons/swiftfin/views/settings/_general',
		],
		'tabPrint' => [
			'label' => Yii::t('app/menu', 'Print setup'),
			'content' => '@addons/swiftfin/views/settings/_print',
		],
        'tabUserVerification' => [
            'label' => Yii::t('app/menu', 'User verification settings'),
            'content' => '@addons/swiftfin/views/settings/_userVerification',
        ],
		'tabAccess' => [
			'label' => Yii::t('app', 'User access'),
			'content' => '@addons/swiftfin/views/settings/_userAccess',
		],
        'tabRouting' => [
			'label' => Yii::t('app', 'Routing'),
			'content' => '@addons/swiftfin/views/settings/_routing',
		]
	],
];

$this->title = Yii::t('app', 'Settings');
$this->params['breadcrumbs'][] = Yii::t('app', 'Settings');

?>
<?= AdvancedTabs::widget([
    'data' => $data,
    'notFoundTabContent' => '<div class="alert alert-danger" style="margin-top:20px">'.Yii::t('app/error', 'The requested page could not be found.').'</div>',
    'params' => [
        'settings' => $settings,
        'dataProvider' => $dataProvider
    ]
]) ?>
