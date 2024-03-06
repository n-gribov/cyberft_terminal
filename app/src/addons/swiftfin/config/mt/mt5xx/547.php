<?php
namespace addons\swiftfin\config\mt5xx;
include_once(__DIR__.'/choiceScheme.php');
include(__DIR__.'/../base/currency.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '547',
	'formable' => true,
	'scheme'   => [
		// Обязательная последовательность А
		[
			'name'   => 'A',
			'type'   => 'sequence',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Общая информация',
			'scheme' => [
				[
					'name'     => '16R',
					'status'   => Entity::STATUS_MANDATORY,
					'label'    => 'Начало блока',
					'service'  => true,
					'constant' => true,
					'value'    => 'GENL',
					'number'   => '1',
				],
				[
					'type'      => 'tag',
					'status'    => Entity::STATUS_MANDATORY,
					'name'      => '20C',
					'label'     => 'Reference',
					'fullLabel' => 'Sender\'s reference',
					'mask'      => ':4!c//16x',
					'number'    => '2',
					'scheme'    => [
						[
							'label'  => 'Определитель',
							'strict' => ['SEME',]
						],
						[
							'label' => 'Референс',
						],
					],
				],
				[
					'type'   => 'tag',
					'status' => Entity::STATUS_MANDATORY,
					'name'   => '23G',
					'label'  => 'Функция сообщения',
					'mask'   => '4!c[/4!c]',
					'number' => '3',
					'scheme' => [
						[
							'label'  => 'Функция',
							'strict' => ['CANC', 'NEWM', 'RVSL'],
						],
						[
							'label'  => 'Подфункция',
							'strict' => ['CODU', 'COPY', 'DUPL'],
						],
					],
				],
				[
					'type'      => 'choice',
					'status'    => Entity::STATUS_OPTIONAL,
					'name'      => '98a',
					'label'     => 'Дата/Время',
					'fullLabel' => 'Дата/Время подготовки',
					'scheme'    => getChoiceScheme('98a', ['A', 'C', 'E']),
					'number'    => '4',
				],
				[
					'type'   => 'choice',
					'status' => Entity::STATUS_OPTIONAL,
					'name'   => '22a',
					'label'  => 'Признак',
					'scheme' => getChoiceScheme('22a', ['F', 'H']),
					'number' => '5',
				],

				// −−→  Необязательная повторяющаяся подпоследовательность А1  Связки
				[
					'name'   => 'A1',
					'type'   => 'collection',
					'label'  => 'Связки',
					'status' => Entity::STATUS_OPTIONAL,
					'scheme' => [
						[
							'name'     => '16R',
							'status'   => Entity::STATUS_MANDATORY,
							'label'    => 'Начало блока',
							'service'  => true,
							'constant' => true,
							'value'    => 'LINK',
							'number'   => '6',
						],

						[
							'type'      => 'tag',
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '22F',
							'label'     => 'Признак',
							'fullLabel' => 'Признак типа связок',
							'mask'      => ':4!c/[8c]/4!c',
							'number'    => '7',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['LINK'],
								],
								[
									'label' => 'Система кодировки',
								],
								[
									'label' => 'Признак',
									// см. доку
									//									'strict' => ['AFTE', 'BEFO', 'INFO', 'WITH',],
								],

							],
						],
						[
							'type'      => 'choice',
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '13a',
							'label'     => 'Определение номера',
							'fullLabel' => 'Связанное сообщение',
							'scheme'    => getChoiceScheme('13a', ['A', 'B']),
							'number'    => '8',
						],

						[
							'name'   => '20C',
							'status' => Entity::STATUS_MANDATORY,
							'label'  => 'Референс',
							'mask'   => ':4!c//16x',
							'number' => '9',
							'scheme' => [
								[
									'label'  => 'Определитель',
									'strict' => [
										'POOL', 'PREA', 'PREV', 'RELA', 'TRRF', 'COMM', 'COLR', 'CERT', 'CLCI',
										'CLTR', 'PCTI', 'TRCI', 'TCTR'
									],
								],
								[
									'label' => 'Референс',
								],
							],
						],

						[
							'name'     => '16S',
							'status'   => Entity::STATUS_MANDATORY,
							'label'    => 'Конец блока',
							'service'  => true,
							'constant' => true,
							'value'    => 'LINK',
							'number'   => '10',
						],
					],
				], // Необязательная повторяющаяся подпоследовательность А1  Связки
				[
					'name'     => '16S',
					'status'   => Entity::STATUS_MANDATORY,
					'label'    => 'Конец блока',
					'service'  => true,
					'constant' => true,
					'value'    => 'GENL',
					'number'   => '11',
				]
			],
		], // Конец последовательности А  Общая информация 
		
		// Обязательная последовательность В  Детали сделки
		[
			'name'   => 'B',
			'type'   => 'sequence',
			'label'  => 'Детали Сделки',
			'status' => Entity::STATUS_MANDATORY,
			'scheme' => [
				[
					'name'     => '16R',
					'status'   => Entity::STATUS_MANDATORY,
					'label'    => 'Начало блока',
					'service'  => true,
					'constant' => true,
					'value'    => 'TRADDET',
					'number'   => '12',
				],
				
				[
					'name'   => '94a',
					'type'   => 'choice',
					'status' => Entity::STATUS_MANDATORY,
					'label'  => 'Место',
					'scheme' => getChoiceScheme('94a', ['B', 'H']),
					'number' => '13',
				],
				
				[
					'type'         => 'collection',
					'name'         => '98a',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'      => 'choice',
							'status'    => Entity::STATUS_MANDATORY,
							'name'      => '98a',
							'label'     => 'Дата/Время',
							'fullLabel' => '(См. описание определителей)',
							'scheme'    => getChoiceScheme('98a', ['A', 'B', 'C', 'E']),
							'number'    => '14',
						],
					],
				],
				
				[
					'type'      => 'choice',
					'status'    => Entity::STATUS_OPTIONAL,
					'name'      => '90a',
					'label'     => 'Цена',
					'fullLabel' => 'Цена сделки',
					'scheme'    => getChoiceScheme('90a', ['A', 'B',]),
					'number'    => '15',
				],
				
				[
					'name'   => '99A',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Количество',
					'mask'   => ':4!c//[N]3!n',
					'number' => '16',
					'scheme' => [
						[
							'label'  => 'Определитель',
							'strict' => ['DAAC'],
						],
						[
							'label' => 'Знак',
						],
						[
							'label' => 'Количество',
						],
					],
				],
				
				[
					'type'   => 'tag',
					'name'   => '35B',
					'status' => Entity::STATUS_MANDATORY,
					'label'  => 'Определение финансового инструмента',
					'mask'   => "[ISIN1!e12!c]~".Entity::INLINE_BREAK."[4*35x]",
					'number' => '17',
				],

				// Необязательная подпоследовательность В1
				[
					'name'   => 'B1',
					'type'   => 'sequence',
					'label'  => 'Детали Сделки',
					'status' => Entity::STATUS_MANDATORY,
					'scheme' => [

						[
							'name'     => '16R',
							'status'   => Entity::STATUS_MANDATORY,
							'label'    => 'Начало блока',
							'service'  => true,
							'constant' => true,
							'value'    => 'FIA',
							'number'   => '18',
						],
						
						[
							'name'   => '94B',
							'status' => Entity::STATUS_OPTIONAL,
							'label'  => 'Место',
							'mask'   => ':4!c/[8c]/4!c[/30x]',
							'number' => '19',
							'scheme' => [
								[
									'label'  => 'Определитель',
									'strict' => ['PLIS'],
								],
								[
									'label' => 'Система кодировки',
								],
								[
									'label' => 'Код места',
									//									'strict' => ['EXCH', 'OTCO'], // см. доку
								],
								[
									'label' => 'Свободный текст',
								],
							]
						],
						
						[
							'type'         => 'collection',
							'name'         => '22F',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'      => 'tag',
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '22F',
									'label'     => 'Признак',
									'fullLabel' => '(См. описание определителей)',
									'mask'      => ':4!c/[8c]/4!c',
									'number'    => '20',
									'scheme'    => [

										[
											'label'  => 'Определитель',
											'strict' => ['MICO', 'FORM', 'PFRE', 'PAYS', 'CFRE'],
										],
										[
											'label' => 'Система кодировки',
										],
										[
											'label' => 'Признак',
											// см. доку
											//											'strict' => [
											//												'A001', 'A002', 'A003', 'A004', 'A005', 'A006', 'A007', 'A008',
											//												'A009', 'A010', 'A011', 'A012', 'A013', 'A014', 'OTHR', 'BEAR',
											//												'REGD', 'ANNU', 'MNTH', 'QUTR', 'SEMI', 'WEEK', 'FULL', 'NILL',
											//												'PART', 'ANNU', 'MNTH', 'QUTR', 'SEMI', 'WEEK',
											//											],
										],

									]

								],
							],
						],
						
						[
							'type'         => 'collection',
							'name'         => '12a',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'      => 'choice',
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '12a',
									'label'     => 'Тип финансового инструмента',
									'fullLabel' => '(См. описание определителей)',
									'scheme'    => getChoiceScheme('12a', ['A', 'B', 'C',]),
									'number'    => '21',
								]
							]
						],
						
						[
							'type'      => 'tag',
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '11A',
							'label'     => 'Валюта',
							'fullLabel' => '(Валюта номинала)',
							'mask'      => ':4!c//3!a',
							'number'    => '22',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['DENO',],
								],
								[
									'label'  => 'Код валюты',
									'strict' => $currency,
								],
							]
						],
						
						[
							'type'         => 'collection',
							'name'         => '98A',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'      => 'tag',
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '98A',
									'label'     => 'Дата',
									'fullLabel' => '(См. описание определителей)',
									'mask'      => ':4!c//8!n',
									'number'    => '23',
									'scheme'    => [
										[
											'label'  => 'Определитель',
											'strict' => [
												'CALD', 'COUP', 'DDTE', 'EXPI', 'FCOU', 'FRNR', 'ISSU', 'MATU', 'PUTT',
											],
										],
										[
											'label' => 'Дата',
										],
									]
								]
							]
						
						],
						[
							'type'         => 'collection',
							'name'         => '92A',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'      => 'tag',
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '92A',
									'label'     => 'Ставка',
									'fullLabel' => '(См. описание определителей)',
									'mask'      => ':4!c//[N]15d',
									'number'    => '24',
									'scheme'    => [
										[
											'label'  => 'Определитель',
											'strict' => ['CUFC', 'INDX', 'INTR', 'NWFC', 'NXRT', 'PRFC', 'YTMR',],
										],
										[
											'label' => 'Знак',
										],
										[
											'label' => 'Ставка',
										],
									]
								]
							]
						
						],
						
						[
							'type'         => 'collection',
							'name'         => '13a',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'      => 'choice',
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '13a',
									'label'     => 'Определение номера',
									'fullLabel' => '(См. описание определителей)',
									'scheme'    => getChoiceScheme('13a', ['A', 'B']),
									'number'    => '25',
								]

							]
						],
						
						[
							'type'         => 'collection',
							'name'         => '17B',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'      => 'tag',
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '17B',
									'label'     => 'Флаг',
									'fullLabel' => '(См. описание определителей)',
									'mask'      => ':4!c//1!a',
									'number'    => '26',
									'scheme'    => [
										[
											'label'  => 'Определитель',
											'strict' => ['FRNF', 'CALL', 'PUTT'],
										],
										[
											'label'  => 'Флаг',
											'strict' => ['N', 'Y',],
										],
									]
								]
							]
						],
						
						[
							'type'         => 'collection',
							'name'         => '90a',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'      => 'choice',
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '90a',
									'label'     => 'Цена',
									'fullLabel' => '(См. описание определителей)',
									'scheme'    => getChoiceScheme('90a', ['A', 'B']),
									'number'    => '27',
								]
							]
						],
						
						[
							'type'         => 'collection',
							'name'         => '36B',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'      => 'tag',
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '36B',
									'label'     => 'Количество финансового инструмента ',
									'fullLabel' => '(См. описание определителей)',
									'mask'      => ':4!c//4!c/15d',
									'number'    => '28',
									'scheme'    => [
										[
											'label'  => 'Определитель',
											'strict' => ['MINO', 'SIZE',],
										],
										[
											'label'  => 'Код типа количества',
											'strict' => ['AMOR', 'FAMT', 'UNIT'],
										],
										[
											'label' => 'Количество',
										],
									]
								]
							]
						
						],
						
						[
							'type'         => 'collection',
							'name'         => '35B',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'      => 'tag',
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '35B',
									'label'     => 'Определение финансового инструмента',
									'fullLabel' => '(Определение финансового инструмента)',
									'mask'      => "[ISIN1!e12!c]~".Entity::INLINE_BREAK."[4*35x]",
									'number'    => '29',
									'scheme'    => [
										[
											'label'  => 'Определение финансового инструмента',
											'strict' => ['MINO', 'SIZE',],
										],
										[
											'label' => 'Описание финансового инструмента',
										],
									]
								]
							]
						
						],
						
						[
							'type'      => 'tag',
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '70E',
							'label'     => 'Свободный текст',
							'fullLabel' => 'Атрибуты (характеристики) финансового инструмента в свободном тексте',
							'mask'      => ':4!c//10*35x',
							'number'    => '30',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['FIAN',],
								],
								[
									'label' => 'Свободный текст',
								],
							]
						
						],
						[
							'name'     => '16S',
							'status'   => Entity::STATUS_MANDATORY,
							'label'    => 'Конец блока',
							'service'  => true,
							'constant' => true,
							'value'    => 'FIA',
							'number'   => '31',
						],

					],
				], // Конец подпоследовательности В1  Атрибуты (характеристики) финансового 
				
				[
					'type'         => 'collection',
					'name'         => '22F',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'      => 'tag',
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '22F',
							'label'     => 'Признак',
							'fullLabel' => '(См. описание определителей)',
							'mask'      => ':4!c/[8c]/4!c',
							'number'    => '32',
							'scheme'    => [

								[
									'label'  => 'Определитель',
									'strict' => ['PROC', 'RPOR', 'PRIR', 'BORR', 'TTCO', 'INCA', 'TRCA', 'PRIC'],
								],
								[
									'label' => 'Система кодировки',
								],
								[
									'label' => 'Признак',
									// @todo Если определитель имеет значение «PRIR», а подполе «Система кодировки» не используется, то подполе «Признак» должно содержать числовое значение в диапазоне от 0001 до 9999, где 0001 означает наивысший приоритет (Код ошибки К22)
									//									'strict' => [
									//										'AVER', 'BCBL', 'BCBN', 'BCFD', 'BCPD', 'BCRO', 'BCRP', 'CBNS', 'CCPN',
									//										'CDIV', 'CLOP', 'CRTS', 'CWAR', 'DEFR', 'ELIG', 'EXCH', 'GTDL', 'INFI',
									//										'LAMI', 'MAPR', 'MKTM', 'MLTF', 'NBOR', 'NEGO', 'NMPR', 'OPEP', 'PROF',
									//										'RETL', 'RMKT', 'SINT', 'SPCU', 'SPEX', 'TAGT', 'TRRE', 'XBNS', 'XCPN',
									//										'XDIV', 'XRTS', 'XWAR', 'YBOR',
									//									],
								],
							]

						],
					],
				],
				
				[
					'type'      => 'tag',
					'status'    => Entity::STATUS_OPTIONAL,
					'name'      => '70E',
					'label'     => 'Свободный текст',
					'fullLabel' => 'Атрибуты (характеристики) финансового инструмента в свободном тексте',
					'mask'      => ':4!c//10*35x',
					'number'    => '33',
					'scheme'    => [
						[
							'label'  => 'Определитель',
							'strict' => ['FXIN', 'SPRO'],
						],
						[
							'label' => 'Свободный текст',
						],
					]
				
				],
				
				[
					'type'     => 'tag',
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16S',
					'service'  => true,
					'constant' => true,
					'label'    => 'Конец блока',
					'value'    => 'TRADDET',
					'number'   => '34',
				],

			],
		], // Конец последовательности В  Детали сделки
		[  // Обязательная последовательность С  Финансовый инструмент/Счет 
		   'name'   => 'C',
		   'type'   => 'sequence',
		   'status' => Entity::STATUS_MANDATORY,
		   'label'  => 'Финансовый инструмент/Счет',
		   'scheme' => [
			   [
				   'name'     => '16R',
				   'status'   => Entity::STATUS_MANDATORY,
				   'label'    => 'Начало блока',
				   'service'  => true,
				   'constant' => true,
				   'value'    => 'FIAC',
				   'number'   => '35',
			   ],

			   [
				   'type'         => 'collection',
				   'name'         => '36B',
				   'disableLabel' => true,
				   'scheme'       => [
					   [
						   'type'      => 'tag',
						   'status'    => Entity::STATUS_MANDATORY,
						   'name'      => '36B',
						   'label'     => 'Количество финансового инструмента',
						   'fullLabel' => 'Количество финансового инструмента, подлежащее расчетам',
						   'mask'      => ':4!c//4!c/15d',
						   'number'    => '36',
						   'scheme'    => [
							   [
								   'label'  => 'Определитель',
								   'strict' => ['ESTT', 'PSTT', 'RSTT'],
							   ],
							   [
								   'label'  => 'Код типа количества',
								   'strict' => ['AMOR', 'FAMT', 'UNIT',],
							   ],
							   [
								   'label' => 'Количество',
							   ],
						   ]
					   ],
				   ],
			   ],

			   [
				   'type'         => 'collection',
				   'name'         => '19A',
				   'disableLabel' => true,
				   'scheme'       => [
					   [
						   'type'      => 'tag',
						   'status'    => Entity::STATUS_OPTIONAL,
						   'name'      => '19A',
						   'label'     => 'Сумма',
						   'fullLabel' => '(См. описание определителей)',
						   'mask'      => ':4!c//~[N]~3!a~15d',
						   'number'    => '37',
						   'scheme'    => [

							   [
								   'label'  => 'Определитель',
								   'strict' => ['PSTT', 'RSTT'],
							   ],
							   [
								   'label' => 'Знак',
							   ],
							   [
								   'label' => 'Код валюты',
								   'strict' => $currency,
							   ],
							   [
								   'label' => 'Сумма',
							   ],
						   ]
					   ],
				   ],
			   ],

			   [
				   'type'   => 'tag',
				   'status' => Entity::STATUS_OPTIONAL,
				   'name'   => '70D',
				   'label'  => 'Свободный текст: Выбор номинала',
				   'mask'   => ':4!c//6*35x',
				   'number' => '38',
				   'scheme' => [
					   [
						   'label'  => 'Определитель',
						   'strict' => ['DENC',],
					   ],
					   [
						   'label' => 'Свободный текст',
					   ],
				   ]
			   ],

			   [
				   'type'         => 'collection',
				   'name'         => '13B',
				   'disableLabel' => true,
				   'scheme'       => [
					   [
						   'type'      => 'tag',
						   'status'    => Entity::STATUS_OPTIONAL,
						   'name'      => '13B',
						   'label'     => 'Определение номера',
						   'fullLabel' => 'Номер сертификата',
						   'mask'      => ':4!c/[8c]/30x',
						   'number'    => '39',
						   'scheme'    => [
							   [
								   'label'  => 'Определитель',
								   'strict' => ['CERT',],
							   ],
							   [
								   'label' => 'Система кодировки',
							   ],
							   [
								   'label' => 'Номер',
							   ],
						   ]
					   ],
				   ],
			   ],

			   [
				   'type'   => 'choice',
				   'status' => Entity::STATUS_OPTIONAL,
				   'name'   => '95a',
				   'label'  => 'Сторона/ Владелец счета',
				   'scheme' => getChoiceScheme('95a', ['P', 'R']),
				   'number' => '40',
			   ],

			   [
				   'type'         => 'collection',
				   'name'         => '97a',
				   'disableLabel' => true,
				   'scheme'       => [
					   [
						   'type'      => 'choice',
						   'status'    => Entity::STATUS_MANDATORY,
						   'name'      => '97a',
						   'label'     => 'Счет',
						   'fullLabel' => 'См. описание определителей',
						   'scheme'    => getChoiceScheme('97a', ['A', 'B', 'E']),
						   'number'    => '41',
					   ]
				   ]
			   ],

			   [
				   'type'   => 'choice',
				   'status' => Entity::STATUS_OPTIONAL,
				   'name'   => '94a',
				   'label'  => 'Сторона/ Владелец счета',
				   'scheme' => getChoiceScheme('95a', ['P', 'R']),
				   'number' => '42',
			   ],

			   //  Необязательная повторяющаяся подпоследовательность С1  Распределение количества
			   [
				   'name'   => 'C1',
				   'type'   => 'collection',
				   'label'  => 'Распределение количества',
				   'status' => Entity::STATUS_OPTIONAL,
				   'scheme' => [
					   [
						   'name'     => '16R',
						   'status'   => Entity::STATUS_MANDATORY,
						   'label'    => 'Начало блока',
						   'service'  => true,
						   'constant' => true,
						   'value'    => 'BREAK',
						   'number'   => '43',
					   ],
					   [
						   'type'      => 'tag',
						   'status'    => Entity::STATUS_OPTIONAL,
						   'name'      => '13B',
						   'label'     => 'Определение номера',
						   'fullLabel' => 'Номер лота',
						   'mask'      => ':4!c/[8c]/30x',
						   'number'    => '44',
						   'scheme'    => [
							   [
								   'label'  => 'Определитель',
								   'strict' => ['LOTS',],
							   ],
							   [
								   'label' => 'Система кодировки',
							   ],
							   [
								   'label' => 'Номер',
							   ],
						   ]
					   ],
					   [
						   'type'      => 'tag',
						   'status'    => Entity::STATUS_OPTIONAL,
						   'name'      => '36B',
						   'label'     => 'Количество финансового инструмента',
						   'fullLabel' => 'Количество финансового инструмента в лоте',
						   'mask'      => ':4!c//4!c/15d',
						   'number'    => '45',
						   'scheme'    => [
							   [
								   'label'  => 'Определитель',
								   'strict' => ['LOTS',],
							   ],
							   [
								   'label'  => 'Код типа количества',
								   'strict' => ['AMOR', 'FAMT', 'UNIT',],
							   ],
							   [
								   'label' => 'Количество',
							   ],
						   ]
					   ],

					   [
						   'type'      => 'choice',
						   'status'    => Entity::STATUS_OPTIONAL,
						   'name'      => '98a',
						   'label'     => 'Дата/Время',
						   'fullLabel' => 'Дата/Время лота',
						   'scheme'    => getChoiceScheme('95a', ['A', 'C', 'E']),
						   'number'    => '46',
					   ],
					   [
						   'type'      => 'choice',
						   'status'    => Entity::STATUS_OPTIONAL,
						   'name'      => '90a',
						   'label'     => 'Цена',
						   'fullLabel' => 'Учетная цена лота',
						   'scheme'    => getChoiceScheme('95a', ['A', 'B']),
						   'number'    => '47',
					   ],
					   [
						   'type'      => 'tag',
						   'status'    => Entity::STATUS_OPTIONAL,
						   'name'      => '22F',
						   'label'     => 'Признак',
						   'fullLabel' => 'Признак типа цены',
						   'mask'      => ':4!c/[8c]/4!c',
						   'number'    => '48',
						   'scheme'    => [
							   [
								   'label'  => 'Определитель',
								   'strict' => ['PRIC', 'SSBT'],
							   ],
							   [
								   'label' => 'Система кодировки',
							   ],
							   [
								   'label' => 'Признак',
								   //								   'strict' => ['AVER',], // см. доку
							   ],
						   ]
					   ],
					   [
						   'name'     => '16S',
						   'status'   => Entity::STATUS_MANDATORY,
						   'label'    => 'Конец блока',
						   'service'  => true,
						   'constant' => true,
						   'value'    => 'BREAK',
						   'number'   => '49',
					   ],
				   ]
			   ], // Конец подпоследовательности С1  Распределение количества
			   [
				   'name'     => '16S',
				   'status'   => Entity::STATUS_MANDATORY,
				   'label'    => 'Конец блока',
				   'service'  => true,
				   'constant' => true,
				   'value'    => 'FIAC',
				   'number'   => '50',
			   ],
		   ],
		],    // Конец последовательности С  Финансовый инструмент/Счет

		[    // Необязательная последовательность D  Детали операции, состоящей из двух взаимосвязанных сторон

			 'name'   => 'D',
			 'type'   => 'sequence',
			 'status' => Entity::STATUS_OPTIONAL,
			 'label'  => 'Детали операции, состоящей из двух взаимосвязанных сторон',
			 'scheme' => [

				 [
					 'name'     => '16S',
					 'status'   => Entity::STATUS_MANDATORY,
					 'label'    => 'Начало блока',
					 'service'  => true,
					 'constant' => true,
					 'value'    => 'REPO',
					 'number'   => '51',
				 ],
				 [
					 'type'         => 'collection',
					 'name'         => '98a',
					 'disableLabel' => true,
					 'scheme'       => [
						 [
							 'type'      => 'choice',
							 'status'    => Entity::STATUS_OPTIONAL,
							 'name'      => '98a',
							 'label'     => 'Дата/Время',
							 'fullLabel' => 'См. описание определителей',
							 'scheme'    => getChoiceScheme('98a', ['A', 'B', 'C']),
							 'number'    => '52',
						 ]
					 ]
				 ],

				 [
					 'type'         => 'collection',
					 'name'         => '22F',
					 'disableLabel' => true,
					 'scheme'       => [
						 [
							 'type'      => 'tag',
							 'status'    => Entity::STATUS_OPTIONAL,
							 'name'      => '22F',
							 'label'     => 'Признак',
							 'fullLabel' => '(См. описание определителей)',
							 'mask'      => ':4!c/[8c]/4!c',
							 'number'    => '53',
							 'scheme'    => [
								 [
									 'label'  => 'Определитель',
									 'strict' => ['RERT', 'MICO', 'REVA', 'LEGA', 'INTR',],
								 ],
								 [
									 'label' => 'Система кодировки',
								 ],
								 [
									 'label' => 'Признак',
									 // см. доку
 //									 'strict' => [
 //										 'A001', 'A002', 'A003', 'A004', 'A005', 'A006', 'A007', 'A008',
 //										 'A009', 'A010', 'A011', 'A012', 'A013', 'A014', 'OTHR', 'FIXE',
 //										 'FORF', 'VARI', 'REVY', 'REVN', 'FRAN', 'MATA', 'MATN', 'GIVE',
 //										 'TAKE',
 //									 ],
								 ],

							 ]

						 ],
					 ],
				 ],

				 [
					 'type'         => 'collection',
					 'name'         => '20C',
					 'disableLabel' => true,
					 'scheme'       => [
						 [
							 'type'      => 'tag',
							 'status'    => Entity::STATUS_OPTIONAL,
							 'name'      => '20C',
							 'label'     => 'Референс',
							 'fullLabel' => '(См. описание определителей)',
							 'mask'      => ':4!c//16x',
							 'number'    => '54',
							 'scheme'    => [
								 [
									 'label'  => 'Определитель',
									 'strict' => ['SECO', 'REPO',],
								 ],
								 [
									 'label' => 'Референс',
								 ],
							 ]
						 ],
					 ],
				 ],

				 [
					 'type'         => 'collection',
					 'name'         => '92a',
					 'disableLabel' => true,
					 'scheme'       => [
						 [
							 'type'      => 'choice',
							 'status'    => Entity::STATUS_OPTIONAL,
							 'name'      => '92a',
							 'label'     => 'Ставка',
							 'fullLabel' => 'См. описание определителей',
							 'scheme'    => getChoiceScheme('92a', ['A', 'C',]),
							 'number'    => '55',

						 ],
					 ],
				 ],

				 [
					 'type'         => 'collection',
					 'name'         => '99B',
					 'disableLabel' => true,
					 'scheme'       => [
						 [
							 'type'      => 'tag',
							 'status'    => Entity::STATUS_OPTIONAL,
							 'name'      => '99B',
							 'label'     => 'Количество',
							 'fullLabel' => '(См. описание определителей)',
							 'mask'      => ':4!c//3!n',
							 'number'    => '56',
							 'scheme'    => [
								 [
									 'label'  => 'Определитель',
									 'strict' => ['CADE', 'TOCO',],
								 ],
								 [
									 'label' => 'Количество',
								 ],
							 ]
						 ],
					 ],
				 ],

				 [
					 'type'         => 'collection',
					 'name'         => '19A',
					 'disableLabel' => true,
					 'scheme'       => [
						 [
							 'type'      => 'tag',
							 'status'    => Entity::STATUS_OPTIONAL,
							 'name'      => '19A',
							 'label'     => 'Сумма',
							 'fullLabel' => '(См. описание определителей)',
							 'mask'      => ':4!c//~[N]~3!a~15d',
							 'number'    => '57',
							 'scheme'    => [
								 [
									 'label'  => 'Определитель',
									 'strict' => ['FORF', 'TRTE', 'REPP', 'ACRU', 'DEAL', 'TAPC'],
								 ],
								 [
									 'label' => 'Знак',
								 ],
								 [
									 'label' => 'Код валюты',
									 'strict' => $currency,
								 ],
								 [
									 'label' => 'Сумма',
								 ],
							 ]
						 ],
					 ],
				 ],

				 [
					 'type'      => 'tag',
					 'status'    => Entity::STATUS_OPTIONAL,
					 'name'      => '70C',
					 'label'     => 'Информация о второй стороне операции',
//					 'fullLabel' => 'Информация о второй стороне операции',
					 'mask'      => ':4!c//4*35x',
					 'number'    => '58',
					 'scheme'    => [
						 [
							 'label'  => 'Определитель',
							 'strict' => ['SECO',],
						 ],
						 [
							 'label' => 'Свободный текст',
						 ],
					 ]
				 ],

				 [
					 'type'     => 'tag',
					 'name'     => '16S',
					 'status'   => Entity::STATUS_MANDATORY,
					 'label'    => 'Конец блока',
					 'service'  => true,
					 'constant' => true,
					 'value'    => 'REPO',
					 'number'   => '59',
				 ],
			 ]
		], // Конец последовательности D  Детали операции, состоящей из двух взаимосвязанных сторон

		[ // Обязательная последовательность Е  Детали расчетов
		  'name'   => 'E',
		  'type'   => 'sequence',
		  'status' => Entity::STATUS_MANDATORY,
		  'label'  => 'Детали расчетов',
		  'scheme' => [
			  [
				  'type'     => 'tag',
				  'name'     => '16R',
				  'status'   => Entity::STATUS_MANDATORY,
				  'label'    => 'Начало блока',
				  'service'  => true,
				  'constant' => true,
				  'value'    => 'SETDET',
				  'number'   => '60',
			  ],

			  [
				  'type'         => 'collection',
				  'name'         => '22F',
				  'disableLabel' => true,
				  'scheme'       => [
					  [
						  'type'      => 'tag',
						  'status'    => Entity::STATUS_MANDATORY,
						  'name'      => '22F',
						  'label'     => 'Признак',
						  'fullLabel' => '(См. описание определителей)',
						  'mask'      => ':4!c/[8c]/4!c',
						  'number'    => '61',
						  'scheme'    => [

							  [
								  'label'  => 'Определитель',
								  'strict' => [
									  'BENE', 'BLOC', 'CASY', 'CCPT', 'COLA', 'COLE', 'CSBT', 'DBNM', 'FXCX',
									  'LEOG', 'MACL', 'NETT', 'REGT', 'REPT', 'REST', 'RTGS', 'RTRN', 'SETR',
									  'SETS', 'SSBT', 'STAM', 'STCO', 'TCPI', 'TRAK', 'TRCA',
								  ],
							  ],
							  [
								  'label' => 'Система кодировки',
							  ],
							  [
								  'label'  => 'Признак',
								  // см. доку
//								  'strict' => [
//									  '144A', 'AGEN', 'ASGN', 'BFWD', 'BLCH', 'BLPA', 'BRKR', 'BSBK', 'CADJ',
//									  'CALL', 'CCIR', 'CLEN', 'CLNT', 'CNCB', 'COLI', 'COLO', 'COMM', 'CONV',
//									  'CRDS', 'CRPR', 'CRSP', 'CRTL', 'CUST', 'DIRT', 'DLWM', 'DMON', 'DQUA',
//									  'DRAW', 'DUEB', 'EQPT', 'EQUS', 'ETFT', 'EXER', 'EXPT', 'EXTD', 'FCTA',
//									  'FIXI', 'FORW', 'FORX', 'FRCL', 'FUTR', 'FXNO', 'FXYE', 'GROS', 'INSP',
//									  'INTE', 'ISSU', 'KNOC', 'LIQU', 'MAKT', 'MKDW', 'MKUP', 'NBEN', 'NCCP',
//									  'NETS', 'NETT', 'NLEG', 'NNET', 'NOMC', 'NPAR', 'NREG', 'NRST', 'NRTG',
//									  'NSET', 'NSYN', 'OPTN', 'OTCD', 'OWNE', 'OWNI', 'PAIR', 'PARD', 'PART',
//									  'PAYM', 'PENS', 'PHYS', 'PLAC', 'PORT', 'PRIN', 'RATE', 'REAL', 'REDI',
//									  'REDM', 'RELE', 'REPO', 'REPU', 'RESI', 'RODE', 'ROLP', 'RPTO', 'RSTR',
//									  'RTGS', 'RVPO', 'SAFE', 'SAGE', 'SBBK', 'SBRE', 'SBSB', 'SCIE', 'SCIR',
//									  'SCRP', 'SECB', 'SECL', 'SHOR', 'SHSL', 'SINO', 'SLEB', 'SLOA', 'SLRE',
//									  'SPDL', 'SPRI', 'SUBS', 'SWPT', 'SYND', 'TBAC', 'TBAS', 'TCRP', 'TOPU',
//									  'TRAC', 'TRAD', 'TRAN', 'TRIP', 'TRPO', 'TRVO', 'TURN', 'UNEX', 'UNRE',
//									  'UNTR', 'VEND', 'WTHD', 'YBEN', 'YCCP', 'YLEG', 'YNET', 'YREG', 'YRTG',
//									  'YSET',
//								  ],
							  ],

						  ]

					  ],
				  ],
			  ],

			  [
				  'name'   => 'E1',
				  'type'   => 'collection',
				  'status' => Entity::STATUS_MANDATORY,
				  'label'  => 'Стороны при расчетах',
				  'scheme' => [
					  [
						  'type'     => 'tag',
						  'name'     => '16R',
						  'status'   => Entity::STATUS_MANDATORY,
						  'label'    => 'Начало блока',
						  'service'  => true,
						  'constant' => true,
						  'value'    => 'SETPRTY',
						  'number'   => '62',
					  ],

					  [
						  'type'         => 'collection',
						  'name'         => '95a',
						  'disableLabel' => true,
						  'scheme'       => [
							  [
								  'type'      => 'choice',
								  'status'    => Entity::STATUS_MANDATORY,
								  'name'      => '95a',
								  'label'     => 'Сторона',
								  'fullLabel' => 'См. описание определителей',
								  'scheme'    => getChoiceScheme('95a', ['C', 'P', 'Q', 'R', 'S']),
								  'number'    => '63',
							  ]
						  ]
					  ],
					  [
						  'type'      => 'choice',
						  'status'    => Entity::STATUS_OPTIONAL,
						  'name'      => '97a',
						  'label'     => 'Счет',
						  'fullLabel' => 'Счет депо',
						  'scheme'    => getChoiceScheme('97a', ['A', 'B',]),
						  'number'    => '64',
					  ],
					  [
						  'type'      => 'choice',
						  'status'    => Entity::STATUS_OPTIONAL,
						  'name'      => '98a',
						  'label'     => 'Дата/Время',
						  'fullLabel' => 'Дата/Время обработки',
						  'scheme'    => getChoiceScheme('98a', ['A', 'C',]),
						  'number'    => '65',
					  ],

					  [
						  'type'      => 'tag',
						  'status'    => Entity::STATUS_OPTIONAL,
						  'name'      => '20C',
						  'label'     => 'Референс',
						  'fullLabel' => 'Референс обработки',
						  'mask'      => ':4!c//16x',
						  'number'    => '66',
						  'scheme'    => [

							  [
								  'label'  => 'Определитель',
								  'strict' => ['PROC',],
							  ],
							  [
								  'label' => 'Референс',
							  ],
						  ]
					  ],

					  [
						  'type'         => 'collection',
						  'name'         => '70a',
						  'disableLabel' => true,
						  'scheme'       => [
							  [
								  'type'      => 'choice',
								  'status'    => Entity::STATUS_MANDATORY,
								  'name'      => '70a',
								  'label'     => 'Свободный текст',
								  'fullLabel' => 'См. описание определителей',
								  'scheme'    => getChoiceScheme('70a', ['C', 'D', 'E']),
								  'number'    => '67',
							  ]
						  ]
					  ],

					  [
						  'type'     => 'tag',
						  'name'     => '16S',
						  'status'   => Entity::STATUS_MANDATORY,
						  'label'    => 'Конец блока',
						  'service'  => true,
						  'constant' => true,
						  'value'    => 'SETPRTY',
						  'number'   => '68',
					  ],

				  ]
			  ], // −−−| Конец подпоследовательности Е1  Стороны при расчетах

			  [    // −−→ Необязательная повторяющаяся подпоследовательность Е2  Стороны при денежных расчетах

				   'name'   => 'E2',
				   'type'   => 'collection',
				   'status' => Entity::STATUS_OPTIONAL,
				   'label'  => 'Стороны при денежных расчетах',
				   'scheme' => [
					   [
						   'type'     => 'tag',
						   'name'     => '16R',
						   'status'   => Entity::STATUS_MANDATORY,
						   'label'    => 'Начало блока',
						   'service'  => true,
						   'constant' => true,
						   'value'    => 'CSHPRTY',
						   'number'   => '69',
					   ],
					   [
						   'type'         => 'collection',
						   'name'         => '95a',
						   'disableLabel' => true,
						   'scheme'       => [
							   [
								   'type'      => 'choice',
								   'status'    => Entity::STATUS_MANDATORY,
								   'name'      => '95a',
								   'label'     => 'Сторона',
								   'fullLabel' => 'См. описание определителей',
								   'scheme'    => getChoiceScheme('95a', ['P', 'Q', 'R', 'S']),
								   'number'    => '70',
							   ]
						   ]
					   ],
					   [
						   'type'         => 'collection',
						   'name'         => '97a',
						   'disableLabel' => true,
						   'scheme'       => [
							   [
								   'type'      => 'choice',
								   'status'    => Entity::STATUS_OPTIONAL,
								   'name'      => '97a',
								   'label'     => 'Счет',
								   'fullLabel' => 'См. описание определителей',
								   'scheme'    => getChoiceScheme('97a', ['A', 'E',]),
								   'number'    => '71',
							   ]
						   ]
					   ],
					   [
						   'type'      => 'tag',
						   'status'    => Entity::STATUS_OPTIONAL,
						   'name'      => '20C',
						   'label'     => 'Референс',
						   'fullLabel' => 'Референс обработки',
						   'mask'      => ':4!c//16x',
						   'number'    => '72',
						   'scheme'    => [

							   [
								   'label'  => 'Определитель',
								   'strict' => ['PROC',],
							   ],
							   [
								   'label' => 'Референс',
							   ],
						   ]

					   ],

					   [
						   'type'         => 'collection',
						   'name'         => '70a',
						   'disableLabel' => true,
						   'scheme'       => [
							   [
								   'type'      => 'choice',
								   'status'    => Entity::STATUS_OPTIONAL,
								   'name'      => '70a',
								   'label'     => 'Свободный текст',
								   'fullLabel' => 'См. описание определителей',
								   'scheme'    => getChoiceScheme('70a', ['C', 'E',]),
								   'number'    => '73',
							   ]
						   ]
					   ],
					   [
						   'type'     => 'tag',
						   'name'     => '16S',
						   'status'   => Entity::STATUS_MANDATORY,
						   'label'    => 'Конец блока',
						   'service'  => true,
						   'constant' => true,
						   'value'    => 'CSHPRTY',
						   'number'   => '74',
					   ],
				   ]
			  ], // −−−| Конец подпоследовательности Е2 Стороны при денежных расчетах
			  [ // −−→ Необязательная повторяющаяся подпоследовательность Е3  Суммы
				'name'   => 'E3',
				'type'   => 'collection',
				'status' => Entity::STATUS_OPTIONAL,
				'label'  => 'Суммы',
				'scheme' => [
					[
						'type'     => 'tag',
						'name'     => '16R',
						'status'   => Entity::STATUS_MANDATORY,
						'label'    => 'Начало блока',
						'service'  => true,
						'constant' => true,
						'value'    => 'AMT',
						'number'   => '75',
					],

					[
						'type'         => 'collection',
						'name'         => '17B',
						'disableLabel' => true,
						'scheme'       => [
							[
								'type'      => 'tag',
								'status'    => Entity::STATUS_OPTIONAL,
								'name'      => '17B',
								'label'     => 'Флаг',
								'fullLabel' => '(См. описание определителей)',
								'mask'      => ':4!c//1!a',
								'number'    => '76',
								'scheme'    => [
									[
										'label'  => 'Определитель',
										'strict' => [
											'BENE', 'BLOC', 'CASY', 'CCPT', 'COLA', 'COLE', 'CSBT', 'DBNM', 'FXCX',
											'LEOG', 'MACL', 'NETT', 'REGT', 'REPT', 'REST', 'RTGS', 'RTRN', 'SETR',
											'SETS', 'SSBT', 'STAM', 'STCO', 'TCPI', 'TRAK', 'TRCA',
										],
									],
									[
										'label'  => 'Флаг',
										'strict' => ['Y', 'N']
									],
								]
							],
						],
					],

					[
						'type'         => 'collection',
						'name'         => '19A',
						'disableLabel' => true,
						'scheme'       => [
							[
								'type'      => 'tag',
								'status'    => Entity::STATUS_MANDATORY,
								'name'      => '19A',
								'label'     => 'Сумма',
								'fullLabel' => '(См. описание определителей)',
								'mask'      => ':4!c//~[N]~3!a~15d',
								'number'    => '77',
								'scheme'    => [

									[
										'label'  => 'Определитель',
										'strict' => [
											'ACCA', 'ACRU', 'ANTO', 'CHAR', 'COAX', 'COMT', 'COUN', 'DEAL', 'ESTT',
											'EXEC', 'ISDI', 'LADT', 'LEVY', 'LOCL', 'LOCO', 'MARG', 'OCMT',
										],
									],
									[
										'label' => 'Знак',
									],
									[
										'label'  => 'Код валюты',
										'strict' => $currency,
									],
									[
										'label' => 'Сумма',
									],
								]
							],
						],
					],

					[
						'type'      => 'choice',
						'status'    => Entity::STATUS_OPTIONAL,
						'name'      => '98a',
						'label'     => 'Дата/Время',
						'fullLabel' => 'Дата/Время подготовки',
						'scheme'    => getChoiceScheme('98a', ['A', 'C', 'E']),
						'number'    => '78',
					],

					[
						'type'      => 'tag',
						'status'    => Entity::STATUS_OPTIONAL,
						'name'      => '92B',
						'label'     => 'Курс',
						'fullLabel' => 'Курс конвертации',
						'mask'      => ':4!c//3!a/3!a/15d',
						'number'    => '79',
						'scheme'    => [

							[
								'label'  => 'Определитель',
								'strict' => ['EXCH',],
							],
							[
								'label' => 'Код первой валюты',
								'strict' => $currency,
							],
							[
								'label' => 'Код второй валюты',
								'strict' => $currency,
							],
							[
								'label' => 'Курс',
							],
						]

					],

					[
						'name'     => '16S',
						'status'   => Entity::STATUS_MANDATORY,
						'label'    => 'Конец блока',
						'service'  => true,
						'constant' => true,
						'value'    => 'AMT',
						'number'   => '80',
					],

				]
			  ], // −−−| Конец подпоследовательности Е3  Суммы

			  [
				  'name'     => '16S',
				  'status'   => Entity::STATUS_MANDATORY,
				  'label'    => 'Конец блока',
				  'service'  => true,
				  'constant' => true,
				  'value'    => 'SETDET',
				  'number'   => '81',
			  ],
		  ]
		], // Конец последовательности Е  Детали расчетов
		[  // −−→ Необязательная повторяющаяся последовательность F  Прочие стороны
		   'name'   => 'F',
		   'type'   => 'collection',
		   'status' => Entity::STATUS_OPTIONAL,
		   'label'  => 'Суммы',
		   'scheme' => [
			   [
				   'name'     => '16R',
				   'status'   => Entity::STATUS_MANDATORY,
				   'label'    => 'Начало блока',
				   'service'  => true,
				   'constant' => true,
				   'value'    => 'OTHRPRTY',
				   'number'   => '82',
			   ],

			   [
				   'type'         => 'collection',
				   'name'         => '95a',
				   'disableLabel' => true,
				   'scheme'       => [
					   [
						   'type'      => 'choice',
						   'status'    => Entity::STATUS_MANDATORY,
						   'name'      => '95a',
						   'label'     => 'Сторона',
						   'fullLabel' => 'См. описание определителей',
						   'scheme'    => getChoiceScheme('70a', ['C', 'P', 'Q', 'R', 'S']),
						   'number'    => '83',
					   ]
				   ]
			   ],
			   [
				   'type'      => 'tag',
				   'status'    => Entity::STATUS_OPTIONAL,
				   'name'      => '97A',
				   'label'     => 'Счет',
				   'fullLabel' => 'См. описание определителей',
				   'mask'      => ':4!c//35x',
				   'number'    => '84',
				   'scheme'    => [
					   [
						   'label'  => 'Определитель',
						   'strict' => ['SAFE',],
					   ],
					   [
						   'label' => 'Номер счета',
					   ],
				   ]
			   ],
			   [
				   'type'         => 'collection',
				   'name'         => '70a',
				   'disableLabel' => true,
				   'scheme'       => [
					   [
						   'type'      => 'choice',
						   'status'    => Entity::STATUS_OPTIONAL,
						   'name'      => '70a',
						   'label'     => 'Свободный текст',
						   'fullLabel' => 'См. описание определителей',
						   'scheme'    => getChoiceScheme('70a', ['C', 'D', 'E']),
						   'number'    => '85',
					   ]
				   ]
			   ],
			   [
				   'type'      => 'tag',
				   'status'    => Entity::STATUS_OPTIONAL,
				   'name'      => '20C',
				   'label'     => 'Референс',
				   'fullLabel' => 'Референс обработки',
				   'mask'      => ':4!c//16x',
				   'number'    => '86',
				   'scheme'    => [
					   [
						   'label'  => 'Определитель',
						   'strict' => ['PROC',],
					   ],
					   [
						   'label' => 'Референс',
					   ],
				   ]
			   ],

			   [
				   'name'     => '16S',
				   'status'   => Entity::STATUS_MANDATORY,
				   'label'    => 'Конец блока',
				   'service'  => true,
				   'constant' => true,
				   'value'    => 'OTHRPRTY',
				   'number'   => '87',
			   ],
		   ],
		]
	]
];
