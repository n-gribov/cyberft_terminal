<?php
namespace addons\swiftfin\config\mt2xx;
include_once(__DIR__. '/choiceScheme.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '210',
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
			'name'   => '25',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Номер счета',
			'mask'   => '35x',
			'number' => '2',
			'scheme' => [
				[
					'label' => 'Счет'
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
					'label' => 'Дата',
                    'name' => 'date'
				],
			],
		],	
		[
			'type' => 'collection',
			'name' => '21-56a',
			'disableLabel' => true,
			'scheme' => [
				[
					'name'   => '21',
					'status' => Entity::STATUS_MANDATORY,
					'label'  => 'Связанный референс',
					'mask'   => '16x',
					'number' => '4',
				],
				[
					'name'   => '32B',
					'status' => Entity::STATUS_MANDATORY,
					'label'  => 'Код валюты, сумма',
					'mask'   => '3!a~15d',
					'number' => '5',
                    'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper32B',
					'scheme' => [
						[
							'label' => 'Валюта',
                            'name' => 'currency',
                            'strict' => \common\helpers\Currencies::getCodeLabels()
						],
						[
							'label' => 'Сумма',
                            'name' => 'sum'
						],
					],
				],
				[
					'name'   => '50a',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Клиент-заказчик',
					'type'   => 'choice',
					'number' => '6',
					'scheme' => getChoiceScheme('50a', ['', 'C', 'F']),
				],
				[
					'name'   => '52a',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Финансовая организация-заказчик',
					'type'   => 'choice',
					'number' => '7',
					'scheme' => getChoiceScheme('52a', ['A', 'D']),
				],
				[
					'name'   => '56a',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Посредник',
					'type'   => 'choice',
					'number' => '8',
					'scheme' => getChoiceScheme('56a', ['A', 'D']),
				],	
			],
		],
	], 
];