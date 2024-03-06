<?php
namespace addons\swiftfin\config\mt2xx;
include_once(__DIR__ . '/choiceScheme.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;
use \Yii;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '298',
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
			'name'   => '12',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Тип подсообщения',
			'mask'   => '3!n',
			'number' => '2',
		],
		[
			'name'   => '77E',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Собственное сообщение',
			'mask'   => "73x~".Entity::INLINE_BREAK."[n*78x]",
			'number' => '3',
			'scheme' => [
				[
					'label' => 'Текст1'
				],
				[
					'label' => 'Текст2'
				],
			],
		]
	],
];