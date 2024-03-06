<?php
namespace addons\swiftfin\config\mt9xx;
include_once(__DIR__ . '/choiceScheme.php');
include(__DIR__ . '/../base/currency.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '940',
	'formable' => true,
	'scheme'   => [
		[
			'name'   => '20',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Референс операции',
			'mask'   => '16x',
			'scheme' => [
				[
					'label' => '',
				]
			]
		],
		[
			'name'   => '21',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Related Reference',
			'mask'   => '16x',
			'scheme' => [
				[
					'label' => '',
				]
			]
		],
		[
			'name'   => '25',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Номер счета',
			'mask'   => '35x',
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
			'mask'   => '5n[/5n]',
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
		],
		[
			'name'   => '61-86',
			'type'   => 'collection',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Стороны при расчетах',
			'scheme' => [
				[
					'name'   => '61',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Строка движения по счету',
					'mask'   => '6!n~[4!n]~2a~[1!a]~15d~1!a3!c~16x~[//16x]~' . Entity::INLINE_BREAK . '[34x]',
					'scheme' => [
						[
							'label' => 'Дата валютирования',
                            'name' => 'date1'
						],
						[
							'label' => 'Дата проводки',
                            'name' => 'date2'
						],
						[
							'label' => 'Знак дебета/кредита',
						],
						[
							'label'  => 'Код средств',
							'strict' => ['D', 'C', 'RC', 'RD'],
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
				[
					'name'   => '86',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Информация для владельца счета',
					'mask'   => '6*65x',
					'scheme' => [
						[
							'label' => 'Свободный текст',
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
		],
		[
			'name'   => '64',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Исходящий доступный остаток',
			'mask'   => '1!a~6!n~3!a~15d',
			'scheme' => [
				[
					'label' => 'Знак дебета/кредита',
				],
				[
					'label' => 'Дата',
                    'name' => 'date'
				],
				[
					'label'  => 'Валюта',
                    'name' => 'currency',
					'strict' => $currency
				],
				[
					'label' => 'Сумма',
                    'name' => 'sum'
				],
			]
		],
		[
			'name'   => '65',
			'type'   => 'collection',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Стороны при расчетах',
			'scheme' => [
				[
					'name'   => '65',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Будущий доступный остаток',
					'mask'   => '1!a~6!n~3!a~15d',
					'scheme' => [
						[
							'label' => 'Знак дебета/кредита',
						],
						[
							'label' => 'Дата',
                            'name' => 'date'
						],
						[
							'label'  => 'Валюта',
                            'name' => 'currency',
							'strict' => $currency
						],
						[
							'label' => 'Сумма',
                            'name' => 'sum'
						],
					]
				],
			],
		],
		[
			// @todo костыльнем, чет одноименные тэги на одном уровне поломались
			'name'         => 'spike',
			'type'         => 'sequence',
			'status'       => Entity::STATUS_MANDATORY,
			'disableLabel' => true,
			'scheme'       => [
				[
					'name'   => '86',
					'status' => Entity::STATUS_MANDATORY,
					'label'  => 'Информация для владельца счета',
					'mask'   => '6*65x',
					'scheme' => [
						[
							'label' => 'Свободный текст',
						],
					]
				],
			],
		],
	],
];		
