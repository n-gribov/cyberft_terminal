<?php
namespace addons\swiftfin\config\mt2xx;
include_once(__DIR__. '/choiceScheme.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '203',
	'formable' => true,
	'scheme'   => [
		[
			'name'   => '19',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Общая сумма',
			'mask'   => '17d',
			'number' => '1',
			'scheme' => [
				[
					'label' => 'Сумма'
				],
			],
		],
		[
			'name'   => '30',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Дата валютирования',
			'mask'   => '6!n',
			'number' => '2',
			'scheme' => [
				[
					'label' => 'Дата'
				],
			],
		],
		[
			'name'   => '52a',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Финансовая организация-заказчик',
			'type'   => 'choice',
			'number' => '3',
			'scheme' => getChoiceScheme('52a', ['A', 'D']),	
		],
		[
			'name'   => '53a',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Корреспондент Отправителя',
			'type'   => 'choice',
			'number' => '4',
			'scheme' => getChoiceScheme('53a', ['A', 'B', 'D']),
		],
		[
			'name'   => '54a',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Корреспондент Отправителя',
			'type'   => 'choice',
			'number' => '5',
			'scheme' => getChoiceScheme('54a', ['A', 'B', 'D']),
		],
		[
			'name'   => '72',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Корреспондент Отправителя',
			'mask'   => '6*35x',
			'scheme' => [
				[
					'label' => 'Свободный текст - Структурированный формат'
				],
			],
		],
	[
		'type' => 'collection',
		'name' => '20-72',
		'disableLabel' => true,
		'scheme' => [
				[
					'name'   => '20',
					'status' => Entity::STATUS_MANDATORY,
					'label'  => 'Референс операции',
					'mask'   => '16x',
					'number' => '7',
				],
				[
					'name'   => '21',
					'status' => Entity::STATUS_MANDATORY,
					'label'  => 'Связанный референс',
					'mask'   => '16x',
					'number' => '8',
				],
				[
					'name'   => '32B',
					'status' => Entity::STATUS_MANDATORY,
					'label'  => 'Код валюты, сумма',
					'mask'   => '3!a~15d',
					'number' => '9',
					'scheme' => [
						[
							'label' => 'Валюта'
						],
						[
							'label' => 'Сумма'
						],
					],
				],
				[
					'name'   => '56a',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Посредник',
					'type'   => 'choice',
					'number' => '10',
					'scheme' => getChoiceScheme('56a', ['A', 'D']),
				],
				[
					'name'   => '57a',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Банк бенефициара',
					'type'   => 'choice',
					'number' => '11',
					'scheme' => getChoiceScheme('57a', ['A', 'B', 'D']),
				],
				[
					'name'   => '58a',
					'status' => Entity::STATUS_MANDATORY,
					'label'  => 'Финансовая организация-бенефициар',
					'type'   => 'choice',
					'number' => '12',
					'scheme' => getChoiceScheme('58a', ['A', 'D']),
				],
				[
					'name'   => '72',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Информация Отправителя Получателю',
					'mask'   => '6*35x',
					'number' => '13',
					'scheme' => [
						[
							'label' => 'Свободный текст - Структурированный формат'
						],
					],
				],
			],
		],
	],
];
