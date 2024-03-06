<?php
namespace addons\swiftfin\config\mt9xx;
include_once(__DIR__.'/choiceScheme.php');
include(__DIR__.'/../base/currency.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversal950Document',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '950',
	'formable' => true,
	// Дополнения к основному view для данного типа документа
	'dataViews' => [
		'refs' => [
			'label' => 'Referenced',
			'view' => '@addons/swiftfin/views/documents/_mt950',
		],
	],
	// Алиас для данных, касающихся списка связанных референсов документа
	'aliases'  => [
		'operationReferences' => ['61'],
	],
	'scheme'   => [
		[
			'name'   => '20',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Референс операции',
			'mask'   => "16x",
			'number' => '1',
			'scheme' => [
				[
					'label' => 'Референс',
				]
			]
		],
		[
			'name'   => '25',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Номер счета',
			'mask'   => "35x",
			'number' => '2',
			'scheme' => [
				[
					'label' => 'Счет',
                    'name' => 'account'
				]
			]
		],
		[
			'name'   => '28C',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Номер выписки/Порядковый номер',
			'mask'   => "5n[/5n]",
			'number' => '3',
			'scheme' => [
				[
					'label' => 'Номер выписки',
				],
				[
					'label' => 'Порядковый номер',
				],
			]
		],
		[
			'name'   => '60a',
			'type'   => 'choice',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Входящий остаток',
			'scheme' => getChoiceScheme('60a', ['F', 'M']),
			'number' => '4',
		],
		[
			'type'         => 'collection',
			'name'         => '61',
			'disableLabel' => true,
			'scheme'       => [
				[
					'name'   => '61',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Строка движения по счету',
					'mask'   => "6!n~[4!n]~2a~[1!a]~15d~1!a3!c~16x~[//16x]~".Entity::INLINE_BREAK."[34x]",
					'number' => '5',
					'scheme'    => [
						[
							'label'  => 'Дата валютирования (YYMMDD)',
                            'name' => 'date1'
						],
						[
							'label' => 'Дата проводки (MMDD)',
                            'name' => 'date2'
						],
						[
							'label'  => 'Знак дебета/кредита',
							'strict' => ['D', 'C', 'RC', 'RD'],
						],
						[
							'label' => 'Код средств',
						],
						[
							'label' => 'Сумма',
                            'name' => 'sum'
						],
						[
							'label' => 'Код типа операции',
						],
						[
							'label' => 'Референс для владельца счета',
						],
						[
							'label' => 'Референс обслуживающей счет организации',
						],
						[
							'label' => 'Дополнительная информация',
						],
					]
				],
			],
		],
		[
			'name'   => '62a',
			'type'   => 'choice',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Исходящий остаток (учтенные средства)',
			'scheme' => getChoiceScheme('62a', ['F', 'M']),
			'number' => '6',
		],
		[
			'name'   => '64',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Исходящий доступный остаток',
			'mask'   => '1!a~6!n~3!a~15d',
			'number' => '7',
			'scheme' => [
				[
					'label'  => 'Знак дебета/кредита',
					'strict' => ['C', 'D'],
				],
				[
					'label' => 'Дата',
                    'name' => 'date'
				],
				[
					'label' => 'Валюта',
                    'name' => 'currency',
					'strict' => $currency
				],
				[
					'label' => 'Сумма',
                    'name' => 'sum'
				],
			]
		],
	]
];		
