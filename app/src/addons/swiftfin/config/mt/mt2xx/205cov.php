<?php
namespace addons\swiftfin\config\mt2xx;
include_once(__DIR__. '/choiceScheme.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '205COV',
	'formable' => true,
    'aliases'  => [
        'currency' => ['32A', 'currency'],
        'sum'      => ['32A', 'sum'],
        'date'     => ['32A', 'date'],
        'currency33' => ['33B', 'currency'],
        'sum33' => ['33B', 'sum'],
    ],
	'scheme'   => [
		[ // Обязательная последовательность A  Общая информация
			'name' => 'A',
			'type' => 'sequence',
			'status' => Entity::STATUS_MANDATORY,
			'label' => 'Общая информация',
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
					'status' => Entity::STATUS_MANDATORY,
					'label'  => 'Связанный референс',
					'mask'   => '16x',
					'number' => '2',
				],
				[
					'type' => 'collection',
					'name' => '13C',
					'disableLabel' => true,
					'scheme' => [
						[
							'name'   => '13C',
							'status' => Entity::STATUS_OPTIONAL,
							'label'  => 'Указание времени',
							'mask'   => '/8c/~4!n~1!x~4!n',
							'number' => '3',
							'scheme' => [
								[
									'label' => 'Код'
								],
								[
									'label' => 'Указание времени'
								],
								[
									'label' => 'Знак'
								],
								[
									'label' => 'Разница во времени'
								],
							],
						],
					],	
				],	
				[
					'name'   => '32A',
					'status' => Entity::STATUS_MANDATORY,
					'label'  => 'Дата валютирования, код валюты, сумма',
					'mask'   => '6!n~3!a~15d',
					'number' => '4',
					'scheme' => [
						[
							'label' => 'Дата',
                            'name' => 'date'
						],
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
					'name'   => '52a',
					'status' => Entity::STATUS_MANDATORY,
					'label'  => 'Финансовая организация-заказчик',
					'type'   => 'choice',
					'number' => '5',
					'scheme' => getChoiceScheme('52a', ['A', 'D']),
				],
				[
					'name'   => '53a',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Корреспондент Отправителя',
					'type'   => 'choice',
					'number' => '6',
					'scheme' => getChoiceScheme('53a', ['A', 'B', 'D']),
				],
				[
					'name'   => '56a',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Посредник',
					'type'   => 'choice',
					'number' => '7',
					'scheme' => getChoiceScheme('56a', ['A', 'D']),
				],
				[
					'name'   => '57a',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Банк бенефициара',
					'type'   => 'choice',
					'number' => '8',
					'scheme' => getChoiceScheme('57a', ['A', 'B', 'D']),
				],
				[
					'name'   => '58a',
					'status' => Entity::STATUS_MANDATORY,
					'label'  => 'Финансовая организация-бенефициар',
					'type'   => 'choice',
					'number' => '9',
					'scheme' => getChoiceScheme('58a', ['A', 'D']),
				],	
				[
					'name'   => '72',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Информация Отправителя Получателю',
					'mask'   => '6*35x',
					'number' => '10',
					'scheme' => [
						[
							'label' => 'Свободный текст - Структурированный формат'
						],
					],
				],
			],
		], // Окончание последовательность A  Общая информация
		[ // Обязательная последовательность В Детали связанного клиентского платежа 
			'name' => 'B',
			'type' => 'sequence',
			'status' => Entity::STATUS_MANDATORY,
			'label' => 'Детали связанного клиентского платежа',
			'scheme' => [
				[
					'name'   => '50a',
					'status' => Entity::STATUS_MANDATORY,
					'label'  => 'Клиент-заказчик',
					'type'   => 'choice',
					'number' => '11',
					'scheme' => getChoiceScheme('50a', ['A', 'F', 'K']),
				],
				[
					'name'   => '52a',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Финансовая организация-заказчик',
					'type'   => 'choice',
					'number' => '12',
					'scheme' => getChoiceScheme('52a', ['A', 'D']),
				],
				[
					'name'   => '56a',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Посредник',
					'type'   => 'choice',
					'number' => '13',
					'scheme' => getChoiceScheme('56a', ['A', 'C', 'D']),
				],
				[
					'name'   => '57a',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Банк бенефициара',
					'type'   => 'choice',
					'number' => '14',
					'scheme' => getChoiceScheme('57a', ['A', 'B', 'C', 'D']),
				],
				[
					'name'   => '59a',
					'status' => Entity::STATUS_MANDATORY,
					'label'  => 'Клиент-бенефициар',
					'type'   => 'choice',
					'number' => '15',
					'scheme' => getChoiceScheme('59a', ['', 'A']),
				],
				[
					'name'   => '70',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Информация о платеже',
					'mask'   => '4*35x',
					'number' => '16',
					'scheme' => [
						[
							'label' => 'Свободный текст'
						],
					],
				],
				[
					'name'   => '72',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Информация Отправителя Получателю',
					'mask'   => '6*35x',
					'number' => '17',
					'scheme' => [
						[
							'label' => 'Свободный текст - Структурированный формат'
						],
					],
				],
				[
					'name'   => '33B',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Валюта/Сумма платежного поручения',
					'mask'   => '3!a~15d',
					'number' => '18',
					'scheme' => [
						[
							'label' => 'Валюта',
                            'name' => 'currency',
                            'strict' => \common\helpers\Currencies::getCodeLabels()
						],
						[
							'label' => 'Сумма',
                            'name' => 'sum',
						],
					],
				],			
			],
		], // Окончание последовательность B Детали связанного клиентского платежа 
	],
];