<?php
namespace addons\swiftfin\config\mt1xx;
include_once(__DIR__. '/choiceScheme.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;
use yii\helpers\Url;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '102STP',
	'formable' => true,
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
					'label'  => 'Референс файла',
					'mask'   => '16x',
					'number' => '1',
				],
				[
					'name'   => '23',
					'status' => Entity::STATUS_MANDATORY,
					'label'  => 'Код банковской операции',
					'mask'   => '16x',
					'number' => '2',
				],
				[
					'name'   => '50a',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Клиент-заказчик',
					'type'   => 'choice',
					'number' => '3',
					'scheme' => getChoiceScheme('50a', ['A', 'F', 'K']),
				],
				[
					'name'   => '52A',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Финансовая организация-заказчик',
					'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4!a2!a2!c[3!c]",
					'number' => '4',
                    'scheme' => [
                        [
                            'label' => 'Опция идентификации стороны',
                        ],
                        [
                            'label' => 'Идентификация стороны',
                            'name' => 'identityPart'
                        ],
                        [
                            'label' => 'Идентификационный код',
                            'type' => 'select2',
                            'dataUrl' => Url::toRoute(['/swiftfin/dict-bank/list'])
                        ],
                    ]
				],	
				[
					'name'   => '26T',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Код типа операции',
					'mask'   => '3!c',
					'number' => '5',
					'scheme' => [
						[
							'label' => 'Тип'
						],
					],	
				],
				[
					'name'   => '77B',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Обязательная отчетность',
					'mask'   => '3*35x',
					'number' => '6',
					'scheme' => [
						[
							'label' => 'Свободный текст'
						],
					],
				],
				[
					'name'   => '71A',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Детали расходов',
					'mask'   => '3!a',
					'number' => '7',
					'scheme' => [
						[
							'label' => 'Код'
						],
					],
				],	
				[
					'name'   => '36',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Курс конвертации',
					'mask'   => '12d',
					'number' => '8',
					'scheme' => [
						[
							'label' => 'Курс'
						],
					],
				],	
			],	
		],	// Окончание последовательности А Общая информация
		[  // Обязательная повторяющаяся последовательность В Детали операции
			'name' => 'B',
			'type' => 'sequence',
			'status' => Entity::STATUS_MANDATORY,
			'label' => 'Детали операции',
			'scheme' => [
					[
						'type' => 'collection',
						'name' => '21-36',
						'disableLabel' => true,
						'scheme' => [		
						[
							'name' => '21',
							'status' => Entity::STATUS_MANDATORY,
							'label' => 'Референс операции',
							'mask' => '16x',
							'number' => '9',
						],
						[
							'name'   => '32B',
							'status' => Entity::STATUS_MANDATORY,
							'label'  => 'Сумма операции',
							'mask'   => '3!a~15d',
							'number' => '10',
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
							'name'   => '56a',
							'status' => Entity::STATUS_OPTIONAL,
							'label'  => 'Клиент-заказчик',
							'type'   => 'choice',
							'number' => '11',
							'scheme' => getChoiceScheme('56a', ['A', 'F', 'K']),
						],
						[
							'name'   => '52A',
							'status' => Entity::STATUS_OPTIONAL,
							'label'  => 'Финансовая организация-заказчик',
							'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4!a2!a2!c[3!c]",
							'number' => '12',
                            'scheme' => [
                                [
                                    'label' => 'Опция идентификации стороны',
                                ],
                                [
                                    'label' => 'Идентификация стороны',
                                    'name' => 'identityPart'
                                ],
                                [
                                    'label' => 'Идентификационный код',
                                    'type' => 'select2',
                                    'dataUrl' => Url::toRoute(['/swiftfin/dict-bank/list'])
                                ],
                            ]
						],
						[
							'name'   => '57A',
							'status' => Entity::STATUS_OPTIONAL,
							'label'  => 'Банк бенефициара',
							'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4!a2!a2!c[3!c]",
							'number' => '13',
                            'scheme' => [
                                [
                                    'label' => 'Опция идентификации стороны',
                                ],
                                [
                                    'label' => 'Идентификация стороны',
                                    'name' => 'identityPart'
                                ],
                                [
                                    'label' => 'Идентификационный код',
                                    'type' => 'select2',
                                    'dataUrl' => Url::toRoute(['/swiftfin/dict-bank/list'])
                                ],
                            ]
						],
						[
							'name'   => '59a',
							'status' => Entity::STATUS_MANDATORY,
							'label'  => 'Клиент-бенефициар',
							'type'   => 'choice',
							'number' => '14',
							'scheme' => getChoiceScheme('59a', ['', 'A']),
						],
						[
							'name'   => '70',
							'status' => Entity::STATUS_OPTIONAL,
							'label'  => 'Информация о платеже',
							'mask'   => '4*35x',
							'number' => '15',
							'scheme' => [
								[
									'label' => 'Свободный текст'
								],
							],
						],
						[
							'name'   => '26T',
							'status' => Entity::STATUS_OPTIONAL,
							'label'  => 'Код типа операции',
							'mask'   => '3!c',
							'number' => '16',
							'scheme' => [
								[
									'label' => 'Тип'
								],
							],	
						],
						[
							'name'   => '77B',
							'status' => Entity::STATUS_OPTIONAL,
							'label'  => 'Обязательная отчетность',
							'mask'   => '3*35x',
							'number' => '17',
							'scheme' => [
								[
									'label' => 'Свободный текст'
								],
							],
						],
						[
							'name'   => '33B',
							'status' => Entity::STATUS_OPTIONAL,
							'label'  => 'Валюта/Сумма платежного поручения',
							'mask'   => '3!a~15d',
							'number' => '18',
                            'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper33B',
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
							'name'   => '71A',
							'status' => Entity::STATUS_OPTIONAL,
							'label'  => 'Детали расходов',
							'mask'   => '3!a',
							'number' => '19',
							'scheme' => [
								[
									'label' => 'Код'
								],
							],
						],
						[
							'type' => 'collection',
							'name' => '71F',
							'disableLabel' => true,
							'scheme' => [	
								[
									'name'   => '71F',
									'status' => Entity::STATUS_OPTIONAL,
									'label'  => 'Расходы Отправителя',
									'mask'   => '3!a~15d',
									'number' => '20',
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
							],	
						],
						[
							'name'   => '71G',
							'status' => Entity::STATUS_OPTIONAL,
							'label'  => 'Расходы Получателя',
							'mask'   => '3!a~15d',
							'number' => '21',
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
							'name'   => '36',
							'status' => Entity::STATUS_OPTIONAL,
							'label'  => 'Курс конвертации',
							'mask'   => '12d',
							'number' => '22',
							'scheme' => [
								[
									'label' => 'Курс'
								],
							],
						],
					],
				],
			],	
		],	// Окончание последовательности B Детали операции
		[  // Окончание последовательности C Детали расчетов
			'name' => 'C',
			'type' => 'sequence',
			'status' => Entity::STATUS_MANDATORY,
			'label' => 'Детали расчетов',
			'scheme' => [
				[
					'name'   => '32A',
					'status' => Entity::STATUS_MANDATORY,
					'label'  => 'Дата валютирования, Код валюты, Сумма',
					'mask'   => '6!n~3!a~15d',
					'number' => '23',
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
					'name'   => '19',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Общая сумма',
					'mask'   => '17d',
					'number' => '24',
					'scheme' => [
						[
							'label' => 'Сумма'
						],
					],
				],
				[
					'name'   => '71G',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Общая величина расходов Получателя',
					'mask'   => '3!a~15d',
					'number' => '25',
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
					'type' => 'collection',
					'name' => '13C',
					'disableLabel' => true,
					'scheme' => [	
						[
							'name'   => '13C',
							'status' => Entity::STATUS_OPTIONAL,
							'label'  => 'Указание времени',
							'mask'   => "/8c~/4!n~1!x~4!n",
							'number' => '26',
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
					'name'   => '53a',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Корреспондент Отправителя',
					'type'   => 'choice',
					'number' => '27',
					'scheme' => getChoiceScheme('56a', ['A', 'C']),
				],
				[
					'name'   => '54A',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Корреспондент Получателя',
					'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4!a2!a2!c[3!c]",
					'number' => '28',
					'scheme' => [
						[
							'label' => 'Идентификация стороны'
						],
						[
							'label' => 'Идентификационный код'
						],
					],
				],
				[
					'name'   => '72',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Информация Отправителя Получателю',
					'mask'   => '6*35x',
					'number' => '29',
					'scheme' => [
						[
							'label' => 'Свободный текст – Структурированный формат'
						],
					],
				],
			],
		], // Окончание последовательности C Детали расчетов
	],
];