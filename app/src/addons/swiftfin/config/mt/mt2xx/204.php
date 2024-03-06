<?php
namespace addons\swiftfin\config\mt2xx;
include_once(__DIR__. '/choiceScheme.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '204',
	'formable' => true,
	'scheme'   => [
		[ // Последовательность A  Общие элементы – детали возмещения  
            'name' => 'A',
            'type' => 'sequence',
            'status' => Entity::STATUS_OPTIONAL,
            'label' => 'Общие элементы',
            'scheme' => [
				[
					'name'   => '20',
					'status' => Entity::STATUS_MANDATORY,
					'label'  => 'Референс операции',
					'mask'   => '16x',
					'number' => '1',
				],
				[
					'name'   => '19',
					'status' => Entity::STATUS_MANDATORY,
					'label'  => 'Общая сумма',
					'mask'   => '17d',
					'number' => '2',
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
					'number' => '3',
					'scheme' => [
						[
							'label' => 'Дата'
						],
					],
				],
				[
					'name'   => '57a',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Банк бенефициара',
					'type'   => 'choice',
					'number' => '4',
					'scheme' => getChoiceScheme('57a', ['A', 'B', 'D']),
				],
				[
					'name'   => '58a',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Финансовая организация-бенефициар',
					'type'   => 'choice',
					'number' => '5',
					'scheme' => getChoiceScheme('58a', ['A', 'D']),
				],
				[
					'name'   => '72',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Информация Отправителя Получателю',
					'mask'   => '6*35x',
					'number' => '6',
					'scheme' => [
						[
							'label' => 'Свободный текст - Структурированный формат'
						],
					],
				],
			],	
		], // Окончание  последовательностьи A  Общие элементы – детали возмещения  
		[ // Повторяющаяся последовательность B  Детали операции
			'name' => 'B',
            'type' => 'sequence',
            'status' => Entity::STATUS_OPTIONAL,
            'label' => 'Детали операции',
            'scheme' => [
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
					'name'   => '53a',
					'status' => Entity::STATUS_MANDATORY,
					'label'  => 'Корреспондент Отправителя',
					'type'   => 'choice',
					'number' => '4',
					'scheme' => getChoiceScheme('53a', ['A', 'B', 'D']),
				],
				[
					'name'   => '72',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Информация Отправителя Получателю',
					'mask'   => '6*35x',
					'number' => '6',
					'scheme' => [
						[
							'label' => 'Свободный текст - Структурированный формат'
						],
					],
				],
			],	
		], // Окончание повторяющейся последовательность B  Детали операции	
	],			
];