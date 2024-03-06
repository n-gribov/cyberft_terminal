<?php
namespace addons\swiftfin\config\mt2xx;
include_once(__DIR__. '/choiceScheme.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '256',
	'formable' => true,
	'scheme'   => [
			[ // Обязательная последовательность A  Общие элементы 
				'name' => 'A',
				'type' => 'sequence',
				'status' => Entity::STATUS_MANDATORY,
				'label' => 'Общие элементы',
				'scheme' => [
					[
						'name'   => '20',
						'status' => Entity::STATUS_MANDATORY,
						'label'  => 'Референс Отправителя',
						'mask'   => '16x',
						'number' => '1',
					],
					[
						'name'   => '21',
						'status' => Entity::STATUS_OPTIONAL,
						'label'  => 'Референс связанного сообщения',
						'mask'   => '16x',
						'number' => '2',
					],
				],	
			], // Окончание последовательность A  Общие элементы 
			[  // Обязательная повторяющаяся последовательность В  Детали чека
				'name' => 'B',
				'type' => 'sequence',
				'status' => Entity::STATUS_MANDATORY,
				'label' => 'Детали чека',
				'scheme' => [ 
						[
							'type' => 'collection',
							'name' => '44A-71H',
							'disableLabel' => true,
							'scheme' => [
							[
								'name'   => '44A',
								'status' => Entity::STATUS_MANDATORY,
								'label'  => 'Референс операции',
								'mask'   => '65x',
								'number' => '3',
							],
							[
								'name'   => '21',
								'status' => Entity::STATUS_OPTIONAL,
								'label'  => 'Референс связанного сообщения',
								'mask'   => '16x',
								'number' => '4',
							],
							[
								'name'   => '21D',
								'status' => Entity::STATUS_OPTIONAL,
								'label'  => 'Номер чека',
								'mask'   => '35x',
								'number' => '5',
							],
							[
								'name'   => '21E',
								'status' => Entity::STATUS_OPTIONAL,
								'label'  => 'Референс чека',
								'mask'   => '35x',
								'number' => '6',
							],
							[
								'name'   => '23E',
								'status' => Entity::STATUS_MANDATORY,
								'label'  => 'Причина неплатежа/отказа от оплаты',
								'mask'   => '4!c~[/30x]',
								'number' => '7',
								'scheme' => [
									[
										'label' => 'Код'
									],
									[
										'label' => 'Свободный текст'
									],
								],
							],
							[
								'name'   => '32J',
								'status' => Entity::STATUS_MANDATORY,
								'label'  => 'Сумма чека',
								'mask'   => '15d',
								'number' => '8',
								'scheme' => [
									[
										'label' => 'Сумма',
                                        'name' => 'sum'
									],
								],
							],
							[
								'name'   => '37J',
								'status' => Entity::STATUS_OPTIONAL,
								'label'  => 'Сумма чека',
								'mask'   => '12d',
								'number' => '9',
								'scheme' => [
									[
										'label' => 'Ставка'
									],
								],
							],
							[
								'name'   => '71G',
								'status' => Entity::STATUS_OPTIONAL,
								'label'  => 'Расходы по процентам',
								'mask'   => '3!a~15d',
								'number' => '10',
                                'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper71G',
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
								'name'   => '71F',
								'status' => Entity::STATUS_OPTIONAL,
								'label'  => 'Расходы Отправителя',
								'mask'   => '3!a~15d',
								'number' => '11',
                                'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper71F',
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
								'name'   => '71H',
								'status' => Entity::STATUS_OPTIONAL,
								'label'  => 'Расходы финансовой организации эмитента',
								'mask'   => '3!a~15d',
								'number' => '12',
                                'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper71H',
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
						],	
					],
				]
			], // Окончание обязательной повторяющейся последовательности В Детали чека
		[  // Обязательная последовательность С  Детали расчетов
			'name' => 'C',
			'type' => 'sequence',
			'status' => Entity::STATUS_MANDATORY,
			'label' => 'Детали чека',
			'scheme' => [ 
				[
					'name'   => '32A',
					'status' => Entity::STATUS_MANDATORY,
					'label'  => 'Дата валютирования, код валюты и общая сумма требований',
					'mask'   => '6!n~3!a~15d',
					'number' => '13',
                    'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper32A',
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
					'name'   => '30',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Первоначальная дата валютирования',
					'mask'   => '6!n',
					'number' => '14',
					'scheme' => [
						[
							'label' => 'Дата',
                            'name' => 'date'
						],
					],
				],
				[
					'name' => '19',
					'status' => Entity::STATUS_OPTIONAL,
					'label' => 'Общая сумма чеков',
					'mask' => '17d',
					'number' => '15',
					'scheme' => [
						[
							'label' => 'Сумма',
                            'name' => 'sum'
						],
					],
				],
				[
					'name'   => '71J',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Сумма расходов по процентам',
					'mask'   => '3!a~15d',
					'number' => '16',
                    'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper71J',
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
					'name'   => '71L',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Сумма расходов Отправителя',
					'mask'   => '3!a~15d',
					'number' => '17',
                    'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper71J',
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
					'name'   => '71K',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Сумма расходов финансовой организации эмитента',
					'mask'   => '3!a~15d',
					'number' => '18',
                    'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper71K',
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
					'name'   => '57a',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Банк бенефициара',
					'type'   => 'choice',
					'number' => '19',
					'scheme' => getChoiceScheme('57a', ['A', 'C', 'D']),
				],
				[
					'name'   => '58B',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Валюта/Сумма операции',
					'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."[35x]",
					'number' => '20',
					'scheme' => [
						[
							'label' => 'Идентификация стороны'
						],
						[
							'label' => 'Местонахождение'
						],
					],
				],
			], 					
		], // Окончание последовательности C Детали расчетов
	],	
];