<?php
use common\widgets\AdvancedTabs;

$data = [
    'action' => 'tabMode',
    'defaultTab' => 'tabZipDownload',
    'tabs' => [
        'tabZipDownload' => [
                'label' => Yii::t('doc', 'Content'),
                'content' => '@addons/finzip/views/default/_tabZipDownload',
            ],
        ],
    ];

echo AdvancedTabs::widget([
		'data' => $data,
		'params' => [
			'model' => $model,
		],
	]);