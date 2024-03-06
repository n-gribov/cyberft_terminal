<?php
namespace addons\swiftfin\config\mt2xx;
include_once(__DIR__ . '/choiceScheme.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;
use \Yii;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '299',
	'formable' => true,
	'scheme'   => [
		[
			'name'   => '20',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Референс операции',
			'mask'   => '16x',
			'number' => '1',
		],
		[
			'name'   => '21',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Связанный референс',
			'mask'   => '16x',
			'number' => '2',
		],
		[
			'name'   => '79',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Свободный текст',
			'mask'   => '35*50x',
			'number' => '3',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],	
		],
	]
];
