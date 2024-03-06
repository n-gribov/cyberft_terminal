<?php
namespace addons\swiftfin\config\mt1xx;
include_once(__DIR__.'/choiceScheme.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '199',
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
			'name'   => '75',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Запросы',
			'mask'   => '6*35x',
			'number' => '3',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],
		[
			'name'   => '77A',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Свободный текст',
			'mask'   => '20*35x',
			'number' => '4',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],	
		// [
			// 'name'   => '11a',
			// 'status' => Entity::STATUS_OPTIONAL,
			// 'label'  => 'Тип и дата исходного сообщения',
			// 'mask'   => '20*35x',
			// 'number' => '5',
			// 'scheme' => getChoiceScheme('11a', ['R', 'S'])
		// ],
		[
			'name'   => '79',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Свободный текст с описанием сообщения, к которому относится запрос',
			'mask'   => '35*50x',
			'number' => '6',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],
	]	
];
