<?php
namespace addons\swiftfin\config\mt5xx;
include_once(__DIR__.'/choiceScheme.php');
include(__DIR__.'/../base/currency.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '545',
	'formable' => true,
	'scheme'   => [
		// Mandatory Sequence A General Information
		[
			'name'   => 'A',
			'type'   => 'sequence',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Общая информация',
			'scheme' => [
				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16R',
					'service'  => true,
					'constant' => true,
					'value'    => 'GENL',
					'number'   => '1',
					'label'    => 'Начало блока',
				],
				[
					'status'    => Entity::STATUS_MANDATORY,
					'name'      => '20C',
					'scheme'    => [
						[
							'label'  => 'Определитель',
							'strict' => ['SEME',]
						],
						[
							'label' => 'Референс',
						],
					],
					'label'     => 'Reference',
					'fullLabel' => 'Sender\'s reference',
					'mask'      => ':4!c//16x',
					'number'    => '2',
				],
				[
					'status' => Entity::STATUS_MANDATORY,
					'name'   => '23G',
					'mask'   => '4!c[/4!c]',
					'number' => '3',
					'label'  => 'Функция сообщения',
					'scheme' => [
						[
							'label'  => 'Функция',
							'strict' => ['CANC', 'NEWM', 'RVSL']
						],
						[
							'label'  => 'Подфункция',
							'strict' => ['CODU', 'COPY', 'DUPL']
						],
					],
				],
				[
					'type'       => 'choice',
					'status'     => Entity::STATUS_OPTIONAL,
					'name'       => '98a',
					'label'      => 'Дата/Время',
					'fullLabel'  => 'Дата/Время подготовки',
					'scheme'     => getChoiceScheme('98a', ['A', 'C', 'E']),
					'number'     => '4',
				],
				[
					'type'         => 'collection',
					'name'         => 'AU1',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'       => 'choice',
							'status'     => Entity::STATUS_OPTIONAL,
							'name'       => '22a',
							'label'      => 'Признак',
							'fullLabel'  => '(see qualifier description)',
							'scheme'     => getChoiceScheme('94a', ['F', 'H']),
							'number'     => '5',
						],
					],
				],
				[
					'name'   => 'A1',
					'type'   => 'collection',
					'label'  => 'Связки',
					'status' => Entity::STATUS_MANDATORY,
					'scheme' => [
						[
							'service'  => true,
							'constant' => true,
							'value'    => 'LINK',
							'name'     => '16R',
							'status'   => Entity::STATUS_MANDATORY,
							'label'    => 'Начало блока',
							'number'   => '6',
						],
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '22F',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['LINK',]
								],
								[
									'label' => 'Система кодировки',
								],
								[
									'label'  => 'Признак',
									'strict' => ['AFTE', 'BEFO', 'INFO', 'WITH']
								],
							],
							'label'     => 'Признак',
							'fullLabel' => 'Признак типа связок',
							'mask'      => ':4!c/[8c]/4!c',
							'number'    => '7',
						],
						[
							'type'       => 'choice',
							'status'     => Entity::STATUS_OPTIONAL,
							'name'       => '13a',
							'label'      => 'Определение номера ',
							'fullLabel'  => 'Связанная операция',
							'scheme'     => getChoiceScheme('98a', ['A', 'B']),
							'number'     => '8',
						],
						[
							'name'      => '20C',
							'status'    => Entity::STATUS_MANDATORY,
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['POOL', 'PREV', 'RELA', 'TRRF', 'COMM', 'COLR', 'CORP', 'TCTR', 'CLTR',
												 'CLCI', 'TRCI', 'MITI', 'PCTI']
								],
								[
									'label' => 'Референс',
								],
							],
							'label'     => 'Референс',
							'fullLabel' => '(См. описание определителей)',
							'mask'      => ':4!c//16x',
							'number'    => '9',
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
					]
				],
				[
					'name'     => '16S',
					'status'   => Entity::STATUS_MANDATORY,
					'label'    => 'Конец блока',
					'service'  => true,
					'constant' => true,
					'value'    => 'GENL',
					'number'   => '11',
				],
			],
		],
		[
			'name'   => 'B',
			'type'   => 'sequence',
			'label'  => 'Детали расчетной операции',
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
					'type'         => 'collection',
					'name'         => 'BU1',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'       => 'choice',
							'status'     => Entity::STATUS_OPTIONAL,
							'name'       => '94a',
							'label'      => 'Место',
							'fullLabel'  => '(see qualifier description)',
							'scheme'     => getChoiceScheme('94a', ['B', 'H']),
							'number'     => '13',
						],
					],
				],
				[
					'type'         => 'collection',
					'name'         => 'BU2',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'       => 'choice',
							'status'     => Entity::STATUS_MANDATORY,
							'name'       => '98a',
							'label'      => 'Дата/Время',
							'fullLabel'  => '(see qualifier description)',
							'scheme'     => getChoiceScheme('98a', ['A', 'B', 'C', 'E']),
							'number'     => '14',
						],
					],
				],
				[
					'type'       => 'choice',
					'status'     => Entity::STATUS_OPTIONAL,
					'name'       => '90a',
					'label'      => 'Цена',
					'fullLabel'  => 'Цена сделки',
					'scheme'     => getChoiceScheme('90a', ['A', 'B']),
					'number'     => '15',
				],
				[
					'status'    => Entity::STATUS_OPTIONAL,
					'name'      => '99A',
					'scheme'    => [
						[
							'label'  => 'Определитель',
							'strict' => ['DAAC',]
						],
						[
							'label' => 'Знак',
						],
						[
							'label' => 'Количество',
						],
					],
					'label'     => 'Количество',
					'fullLabel' => 'Количество дней, за которые начисляются проценты',
					'mask'      => ':4!c//[N]3!n',
					'number'    => '16',
				],

				[
					'status'    => Entity::STATUS_MANDATORY,
					'name'      => '35B',
					'label'     => 'Определение финансового инструмента',
					'fullLabel' => 'Определение финансового инструмента',
					'mask'      => "[ISIN1!e12!c]~".Entity::INLINE_BREAK."[4*35x]",
					'number'    => '17',
					'scheme'    => [
						[
							'label' => 'Определение финансового инструмента',
						],
						[
							'label' => 'Описание финансового инструмента',
						],
					],
				],
				[
					'name'   => 'B1',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => ' Атрибуты (характеристики) финансового инструмента',
					'type'   => 'sequence',
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
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '94B',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['PLIS',]
								],
								[
									'label' => 'Система кодировки',
								],
								[
									'label'  => 'Код места ',
									'strict' => ['EXCH', 'OTCO']
								],
								[
									'label' => 'Свободный текст',
								],
							],
							'label'     => 'Место',
							'fullLabel' => 'Место котировки',
							'mask'      => ':4!c/[8c]/4!c[/30x]',
							'number'    => '19',
						],
						[
							'type'         => 'collection',
							'name'         => 'B1U1',
							'disableLabel' => true,
							'scheme'       => [
								[
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '22F',
									'scheme'    => [
										[
											'label'  => 'Определитель',
											'strict' => ['MICO', 'FORM', 'PFRE', 'PAYS', 'CFRE']
										],
										[
											'label' => 'Система кодировки',
										],
										[
											'label'  => 'Признак',
											'strict' => ['A001', 'A002', 'A003', 'A004', 'A005', 'A006', 'A007', 'A008',
														 'A009', 'A010', 'A011', 'A012', 'A013', 'A014', 'OTHR', 'BEAR',
														 'REGD', 'ANNU', 'MNTH', 'QUTR', 'SEMI', 'WEEK', 'FULL', 'NILL',
														 'PART', 'ANNU', 'MNTH', 'QUTR', 'SEMI', 'WEEK']
										],
									],
									'label'     => 'Признак',
									'fullLabel' => '(see qualifier description)',
									'mask'      => ':4!c/[8c]/4!c',
									'number'    => '20',
								],
							],
						],
						[
							'type'         => 'collection',
							'name'         => 'B1U2',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'       => 'choice',
									'status'     => Entity::STATUS_OPTIONAL,
									'name'       => '12a',
									'label'      => 'Тип финансового инструмента ',
									'fullLabel'  => '(see qualifier description)',
									'scheme'     => getChoiceScheme('12a', ['A', 'B', 'C']),
									'number'     => '21',
								],
							],
						],
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '11A',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['DENO',]
								],
								[
									'label' => 'Код валюты',
									'strict' => $currency,
								],
							],
							'label'     => 'Валюта',
							'fullLabel' => 'Валюта номинала',
							'mask'      => ':4!c//3!a',
							'number'    => '22',
						],
						[
							'type'         => 'collection',
							'name'         => 'B1U3',
							'disableLabel' => true,
							'scheme'       => [
								[
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '98A',
									'scheme'    => [
										[
											'label'  => 'Определитель',
											'strict' => [
												'COUP', 'EXPI', 'FRNR', 'MATU', 'ISSU', 'CALD', 'PUTT', 'DDTE', 'FCOU'
											]
										],
										[
											'label' => 'Дата',
										],
									],
									'label'     => 'Дата',
									'fullLabel' => '(see qualifier description)',
									'mask'      => ':4!c//8!n',
									'number'    => '23',
								],
							],
						],
						[
							'type'         => 'collection',
							'name'         => 'B1U4',
							'disableLabel' => true,
							'scheme'       => [
								[
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '92A',
									'scheme'    => [
										[
											'label'  => 'Определитель',
											'strict' => ['PRFC', 'CUFC', 'NWFC', 'INTR', 'NXRT', 'INDX', 'YTMR']
										],
										[
											'label' => 'Знак',
										],
										[
											'label' => 'Ставка',
										],
									],
									'label'     => 'Ставка',
									'fullLabel' => '(see qualifier description)',
									'mask'      => ':4!c//[N]15d',
									'number'    => '24',
								],
							],
						],
						[
							'type'         => 'collection',
							'name'         => 'B1U5',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'       => 'choice',
									'status'     => Entity::STATUS_OPTIONAL,
									'name'       => '13a',
									'label'      => 'Определение номера',
									'fullLabel'  => '(see qualifier description)',
									'scheme'     => getChoiceScheme('13a', ['A', 'B']),
									'number'     => '25',
								],
							],
						],
						//-----|

						//----->
						[
							'type'         => 'collection',
							'name'         => 'B1U6',
							'disableLabel' => true,
							'scheme'       => [
								[
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '17B',
									'scheme'    => [
										[
											'label'  => 'Определитель',
											'strict' => ['FRNF', 'CALL', 'PUTT']
										],
										[
											'label'  => 'Флаг',
											'strict' => ['N', 'Y']
										],
									],
									'label'     => 'Флаг',
									'fullLabel' => '(see qualifier description)',
									'mask'      => ':4!c//1!a',
									'number'    => '26',
								],
							],
						],
						//-----|

						//----->
						[
							'type'         => 'collection',
							'name'         => 'B1U7',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'      => 'choice',
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '90a',
									'label'     => 'Цена',
									'fullLabel' => '(see qualifier description)',
									'scheme'    => getChoiceScheme('90a', ['A', 'B']),
									'number'    => '27',
								],
							],
						],
						//-----|

						//----->
						[
							'type'         => 'collection',
							'name'         => 'B1U8',
							'disableLabel' => true,
							'scheme'       => [
								[
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '36B',
									'scheme'    => [
										[
											'label'  => 'Определитель',
											'strict' => ['MINO', 'SIZE']
										],
										[
											'label'  => 'Код типа количества',
											'strict' => ['AMOR', 'FAMT', 'UNIT']

										],
										[
											'label' => 'Количество',
										],
									],
									'label'     => 'Количество финансового инструмента',
									'fullLabel' => '(see qualifier description)',
									'mask'      => ':4!c//4!c/15d',
									'number'    => '28',
								],
							],
						],
						//-----|

						//----->
						[
							'type'         => 'collection',
							'name'         => 'B1U9',
							'disableLabel' => true,
							'scheme'       => [
								[
									'status' => Entity::STATUS_OPTIONAL,
									'name'   => '35B',
									'label'  => 'Определение финансового инструмента',
									// Важно: здесь перевод строки для многострочного поля
									'mask'   => "[ISIN1!e12!c]~".Entity::INLINE_BREAK."[4*35x]",
									'number' => '29',
									'scheme' => [
										[
											'label' => 'Определение финансового инструмента',
										],
										[
											'label' => 'Описание финансового инструмента',
										],
									],
								],
							],
						],
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '70E',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['FIAN',]
								],
								[
									'label' => 'Свободный текст',
								],
							],
							'label'     => 'Свободный текст',
							'fullLabel' => 'Атрибуты (характеристики) финансового инструмента в свободном тексте',
							'mask'      => ':4!c//10*35x',
							'number'    => '30',
							'field'     => 'textarea',
						],

						[
							'status'   => Entity::STATUS_MANDATORY,
							'name'     => '16S',
							'service'  => true,
							'constant' => true,
							'label'    => 'Конец блока',
							'value'    => 'FIA',
							'number'   => '31',
						],
					],
				],
				[
					'type'         => 'collection',
					'name'         => 'BU3',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '22F',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['PROC', 'RPOR', 'PRIR', 'BORR', 'TTCO', 'INCA', 'TRCA', 'PRIC']
								],
								[
									'label' => 'Система кодировки',
								],
								[
									'label'  => 'Признак',
									'strict' => ['CLOP', 'OPEP', 'DEFR', 'EXCH', 'TRRE', 'LAMI', 'NBOR', 'YBOR', 'BCBL',
												 'BCBN', 'BCFD', 'BCPD', 'BCRO', 'BCRP', 'CBNS', 'CCPN', 'CDIV', 'CRTS',
												 'CWAR', 'GTDL', 'MAPR', 'NEGO', 'NMPR', 'SPCU', 'SPEX', 'XBNS', 'XCPN',
												 'XDIV', 'XRTS', 'XWAR', 'ELIG', 'PROF', 'RETL', 'INFI', 'MKTM', 'MLTF',
												 'RMKT', 'SINT', 'TAGT', 'AVER']
								],
								// @todo и плюс Если определитель имеет значение «PRIR», а подполе «Система кодировки» не используется, то подполе «Признак» должно содержать числовое значение в диапазоне от 0001 до 9999, где 0001 означает наивысший приоритет
							],
							'label'     => 'Признак',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c/[8c]/4!c',
							'number'    => '32',
						],
					],
				],
				[
					'type'         => 'collection',
					'name'         => 'B1U6',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '70E',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['FXIN', 'SPRO']
								],
								[
									'label' => 'Свободный текст',
								],
							],
							'label'     => 'Свободный текст',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c//1!a',
							'number'    => '33',
						],
					],
				],
				[
					'name'     => '16S',
					'status'   => Entity::STATUS_MANDATORY,
					'label'    => 'Конец блока',
					'service'  => true,
					'constant' => true,
					'value'    => 'TRADDET',
					'number'   => '34',
				],
			],
		],
		[
			'type'   => 'sequence',
			'status' => Entity::STATUS_MANDATORY,
			'name'   => 'C',
			'label'  => 'Финансовый инструмент/Счет ',
			'scheme' => [
				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16R',
					'service'  => true,
					'constant' => true,
					'label'    => 'Начало блока',
					'value'    => 'FIAC',
					'number'   => '35',
				],
				[
					'type'         => 'collection',
					'name'         => 'CU1',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_MANDATORY,
							'name'      => '36B',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['ESTT', 'PSTT', 'RSTT']
								],
								[
									'label'  => 'Код типа количества',
									'strict' => ['AMOR', 'FAMT', 'UNIT']

								],
								[
									'label' => 'Количество',
								],
							],
							'label'     => 'Количество финансового инструмента',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c//4!c/15d',
							'number'    => '36',
						],
					],
				],
				[
					'type'         => 'collection',
					'name'         => 'CU2',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '19A',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['PSTT', 'RSTT']
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
							],
							'label'     => 'Сумма',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c//~[N]~3!a~15d',
							'number'    => '37',
						],
					],
				],
				[
					'name'      => '70D',
					'status'    => Entity::STATUS_OPTIONAL,
					'label'     => 'Свободный текст',
					'scheme'    => [
						[
							'label'  => 'Определитель',
							'strict' => ['DENC',]
						],
						[
							'label' => 'Свободный текст',
						],
					],
					'fullLabel' => 'Выбор номинала',
					'mask'      => ':4!c//6*35x',
					'number'    => '38',
				],
				[
					'type'         => 'collection',
					'name'         => 'CU3',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '13B',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['CERT',]
								],
								[
									'label' => 'Система кодировки',
								],
								[
									'label' => 'Номер',
								],
							],
							'label'     => 'Определение номера',
							'fullLabel' => 'Номер сертификата',
							'mask'      => ':4!c/[8c]/30x',
							'number'    => '39',
						],
					],
				],
				[
					'type'       => 'choice',
					'status'     => Entity::STATUS_OPTIONAL,
					'name'       => '95a',
					'label'      => 'Сторона',
					'fullLabel'  => 'Владелец счета',
					'scheme'     => getChoiceScheme('95a', ['P', 'R']),
					'number'     => '40',
				],
				[
					'type'         => 'collection',
					'name'         => 'CU4',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'       => 'choice',
							'status'     => Entity::STATUS_MANDATORY,
							'name'       => '97a',
							'label'      => 'Счет',
							'fullLabel'  => '(see qualifier description)',
							'scheme'     => getChoiceScheme('97a', ['A', 'B', 'E']),
							'number'     => '41',
						],
					],
				],
				[
					'type'       => 'choice',
					'status'     => Entity::STATUS_OPTIONAL,
					'name'       => '94a',
					'label'      => 'Место',
					'fullLabel'  => 'Место хранения',
					'scheme'     => getChoiceScheme('94a', ['B', 'C', 'F']),
					'number'     => '42',
				],
				[
					'name'         => 'C1',
					'status'       => Entity::STATUS_OPTIONAL,
					'label'        => 'Распределение количества',
					'type'         => 'collection',
					'disableLabel' => true,
					'scheme'       => [
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
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '13B',
							'label'     => 'Определение номера',
							'fullLabel' => 'Номер лота',
							'mask'      => ':4!c/[8c]/30x',
							'number'    => '44',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['LOTS',]
								],
								[
									'label' => 'Система кодировки',
								],
								[
									'label' => 'Номер',
								],
							],
						],
						[
							'status'     => Entity::STATUS_OPTIONAL,
							'name'       => '36B',
							'label'      => 'Количество финансового инструмента',
							'fullLabel'  => 'Количество финансового инструмента в лоте',
							'mask'       => ':4!c//4!c/15d',
							'number'     => '45',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['LOTS',]
								],
								[
									'label' => 'Код типа количества',
								],
								[
									'label' => 'Количество',
								],
							],
						],
						[
							'type'       => 'choice',
							'status'     => Entity::STATUS_OPTIONAL,
							'name'       => '98a',
							'label'      => 'Дата/Время',
							'fullLabel'  => 'Дата/Время лота',
							'scheme'     => getChoiceScheme('98a', ['A', 'C', 'E']),
							'number'     => '46',
						],

						[
							'type'       => 'choice',
							'status'     => Entity::STATUS_OPTIONAL,
							'name'       => '90a',
							'label'      => 'Цена',
							'fullLabel'  => 'Учетная цена лота',
							'scheme'     => getChoiceScheme('90a', ['A', 'B']),
							'number'     => '47',
						],
						[
							'type'         => 'collection',
							'name'         => 'C1U1',
							'disableLabel' => true,
							'scheme'       => [
								[
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '22F',
									'scheme'    => [
										[
											'label'  => 'Определитель',
											'strict' => ['PRIC', 'SSBT']
										],
										[
											'label' => 'Система кодировки',
										],
										[
											'label'  => 'Признак',
											// см. документацию
//											'strict' => ['AVER']
										],
									],
									'label'     => 'Признак',
									'fullLabel' => '(see qualifier description)',
									'mask'      => ':4!c/[8c]/4!c',
									'number'    => '48',
								],
							],
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
					],
				],
				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16S',
					'service'  => true,
					'constant' => true,
					'label'    => 'Конец блока',
					'value'    => 'FIAC',
					'number'   => '50',
				],
			]
		],
		[
			'type'   => 'sequence',
			'status' => Entity::STATUS_OPTIONAL,
			'name'   => 'D',
			'label'  => ' Детали операции, состоящей из двух взаимосвязанных сторон',
			'scheme' => [
				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16R',
					'service'  => true,
					'constant' => true,
					'label'    => 'Начало блока',
					'value'    => 'REPO',
					'number'   => '51',
				],
				[
					'type'         => 'collection',
					'name'         => 'DU1',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'       => 'choice',
							'status'     => Entity::STATUS_OPTIONAL,
							'name'       => '98a',
							'label'      => 'Дата/Время',
							'fullLabel'  => '(see qualifier description)',
							'scheme'     => getChoiceScheme('98a', ['A', 'B', 'C']),
							'number'     => '52',
						],
					],
				],
				//-----|

				//----->
				[
					'type'         => 'collection',
					'name'         => 'DU2',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '22F',
							'label'     => 'Признак',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c/[8c]/4!c',
							'number'    => '53',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['RERT', 'MICO', 'REVA', 'LEGA', 'INTR']
								],
								[
									'label' => 'Система кодировки',
								],
								[
									'label'  => 'Признак',
									// см. документацию
//									'strict' => [
//										'A001', 'A002', 'A003', 'A004', 'A005', 'A006', 'A007', 'A008', 'A009',
//										'A010', 'A011', 'A012', 'A013', 'A014', 'OTHR', 'FIXE', 'FORF', 'VARI',
//										'REVY', 'REVN', 'FRAN', 'GIVE', 'TAKE'
//									]
								],
							],
						],
					],
				],
				//-----|

				//----->
				[
					'type'         => 'collection',
					'name'         => 'DU3',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '20C',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['REPO', 'SECO']
								],
								[
									'label' => 'Референс',
								],
							],
							'label'     => 'Референс',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c//16x',
							'number'    => '54',
						],
					],
				],
				//-----|

				//----->
				[
					'type'         => 'collection',
					'name'         => 'DU4',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'       => 'choice',
							'status'     => Entity::STATUS_OPTIONAL,
							'name'       => '92a',
							'label'      => 'Ставка',
							'fullLabel'  => '(see qualifier description)',
							'scheme'     => getChoiceScheme('92a', ['A', 'C']),
							'number'     => '55',
						],
					],
				],
				//-----|

				//----->
				[
					'type'         => 'collection',
					'name'         => 'DU5',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '99B',
							'label'     => 'Количество',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c//3!n',
							'number'    => '56',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['CADE', 'TOCO']
								],
								[
									'label' => 'Количество',
								],
							],
						],
					],
				],
				//-----|

				//----->
				[
					'type'         => 'collection',
					'name'         => 'DU6',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '19A',
							'label'     => 'Сумма',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c//~[N]~3!a~15d',
							'number'    => '57',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['FORF', 'TRTE', 'REPP', 'ACRU', 'DEAL', 'TAPC']
								],
								[
									'label' => 'Знак',
								],
								[
									'label' => 'Код валюты',
									'stritc' => $currency
								],
								[
									'label' => 'Сумма',
								],
							],
						],
					],
				],
				//-----|

				[
					'status'    => Entity::STATUS_OPTIONAL,
					'name'      => '70C',
					'label'     => 'Свободный текст',
					'fullLabel' => 'Информация о второй стороне операции ',
					'mask'      => ':4!c//4*35x',
					'number'    => '58',
					'scheme'    => [
						[
							'label'  => 'Определитель',
							'strict' => ['SECO',]
						],
						[
							'label' => 'Свободный текст',
						],
					],
				],

				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16S',
					'service'  => true,
					'constant' => true,
					'label'    => 'Конец блока',
					'value'    => 'REPO',
					'number'   => '59',
				],
			]
		],
		[
			'type'   => 'sequence',
			'status' => Entity::STATUS_MANDATORY,
			'name'   => 'E',
			'label'  => 'Детали расчетов',
			'scheme' => [
				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16R',
					'service'  => true,
					'constant' => true,
					'label'    => 'Начало блока',
					'value'    => 'SETDET',
					'number'   => '60',
				],

				//----->
				[
					'type'         => 'collection',
					'name'         => 'EU1',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_MANDATORY,
							'name'      => '22F',
							'label'     => 'Признак',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c/[8c]/4!c',
							'number'    => '61',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => [
										'STCO', 'SETR', 'TRCA', 'STAM', 'RTGS', 'REGT', 'BENE', 'CASY', 'DBNM',
										'TCPI', 'MACL', 'BLOC', 'REST', 'SETS', 'NETT', 'CCPT', 'LEOG', 'COLA',
										'REPT', 'COLE', 'SSBT', 'CSBT'
									]
								],
								[
									'label' => 'Система кодировки',
								],
								[
									'label'  => 'Признак',
									// см. доку
//									'strict' => [
//										'144A', 'AGEN', 'ASGN', 'BFWD', 'BLCH', 'BLPA', 'BRKR', 'BSBK', 'BUTC',
//										'BYIY', 'CADJ', 'CALL', 'CCIR', 'CLEN', 'CLNT', 'CNCB', 'COLA', 'COLI',
//										'COLN', 'COLO', 'COMM', 'CONV', 'CRDS', 'CRPR', 'CRSP', 'CRTL', 'CUST',
//										'DIRT', 'DLWM', 'DRAW', 'EQPT', 'EQUS', 'ETFT', 'EXER', 'EXPT', 'EXTD',
//										'FCTA', 'FIXI', 'FORW', 'FORX', 'FRCL', 'FUTR', 'GROS', 'INSP', 'INTE',
//										'ISSU', 'KNOC', 'LIQU', 'MAKT', 'MKDW', 'MKUP', 'NBEN', 'NCCP', 'NETS',
//										'NETT', 'NLEG', 'NNET', 'NOMC', 'NPAR', 'NREG', 'NRST', 'NRTG', 'NSET',
//										'NSYN', 'OPTN', 'OTCD', 'OWNE', 'OWNI', 'PADJ', 'PAIR', 'PART', 'PAYM',
//										'PHYS', 'PLAC', 'PORT', 'PRIN', 'RATE', 'REAL', 'REDI', 'REDM', 'RELE',
//										'REPO', 'REPU', 'RESI', 'RHYP', 'RODE', 'ROLP', 'RPTO', 'RSTR', 'RVPO',
//										'SAGE', 'SBBK', 'SBRE', 'SBSB', 'SCIE', 'SCIR', 'SCRP', 'SECB', 'SECL',
//										'SLEB', 'SLOA', 'SLRE', 'SPDL', 'SPRI', 'SPST', 'SUBS', 'SWPT', 'SYND',
//										'TBAC', 'TBAS', 'TCRP', 'TOPU', 'TRAD', 'TRAN', 'TRIP', 'TRPO', 'TRVO',
//										'TURN', 'UNEX', 'VEND', 'WTHD', 'YBEN', 'YCCP', 'YLEG', 'YNET', 'YREG',
//										'YRTG', 'YSET'
//									]
								],
							],
						],
					],
				],
				//-----|

				//-----> Mandatory Repetitive Subsequence E1 Settlement Parties
				[
					'type'   => 'collection',
					'status' => Entity::STATUS_MANDATORY,
					'name'   => 'E1',
					'label'  => 'Стороны при расчетах',
					'scheme' => [
						[
							'status'   => Entity::STATUS_MANDATORY,
							'name'     => '16R',
							'service'  => true,
							'constant' => true,
							'label'    => 'Начало блока',
							'value'    => 'SETPRTY',
							'number'   => '62',
						],

						//----->
						[
							'type'         => 'collection',
							'name'         => 'E1U1',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'       => 'choice',
									'status'     => Entity::STATUS_MANDATORY,
									'name'       => '95a',
									'label'      => 'Сторона',
									'fullLabel'  => '(see qualifier description)',
									'scheme'     => getChoiceScheme('95a', ['C', 'P', 'Q', 'R', 'S']),
									'number'     => '63',
								],
							],
						],
						//-----|

						[
							'type'       => 'choice',
							'status'     => Entity::STATUS_OPTIONAL,
							'name'       => '97a',
							'label'      => 'Счет депо',
							'fullLabel'  => 'Счет депо',
							'scheme'     => getChoiceScheme('97a', ['A', 'B']),
							'number'     => '64',
						],

						[
							'type'       => 'choice',
							'status'     => Entity::STATUS_OPTIONAL,
							'name'       => '98a',
							'label'      => 'Дата/Время обработки',
							'fullLabel'  => 'Дата/Время обработки',
							'scheme'     => getChoiceScheme('98a', ['A', 'C']),
							'number'     => '65',
						],

						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '20C',
							'label'     => 'Референс обработки',
							'fullLabel' => 'Референс обработки',
							'mask'      => ':4!c//16x',
							'number'    => '66',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['PROC',]
								],
								[
									'label' => 'Референс',
								],
							],
						],

						//----->
						[
							'type'         => 'collection',
							'name'         => 'E1U2',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'       => 'choice',
									'status'     => Entity::STATUS_OPTIONAL,
									'name'       => '70a',
									'label'      => 'Свободный текст',
									'fullLabel'  => '(see qualifier description)',
									'scheme'     => getChoiceScheme('70a', ['C', 'D', 'E']),
									'number'     => '67',
								],
							],
						],
						//-----|

						[
							'status'   => Entity::STATUS_MANDATORY,
							'name'     => '16S',
							'service'  => true,
							'constant' => true,
							'label'    => 'Конец блока',
							'value'    => 'SETPRTY',
							'number'   => '68',
						],
					],
					//-----| End of Subsequence E1 Settlement Parties
				],

				//-----> Optional Repetitive Subsequence E2 Cash Parties
				[
					'type'   => 'collection',
					'status' => Entity::STATUS_OPTIONAL,
					'name'   => 'E2',
					'label'  => 'Стороны при расчетах',
					'scheme' => [
						[
							'status'   => Entity::STATUS_MANDATORY,
							'name'     => '16R',
							'service'  => true,
							'constant' => true,
							'label'    => 'Начало блока',
							'value'    => 'CSHPRTY',
							'number'   => '69',
						],

						//----->
						[
							'type'         => 'collection',
							'name'         => 'E2U1',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'       => 'choice',
									'status'     => Entity::STATUS_MANDATORY,
									'name'       => '95a',
									'label'      => 'Сторона',
									'fullLabel'  => '(see qualifier description)',
									'scheme'     => getChoiceScheme('95a', ['P', 'Q', 'R', 'S']),
									'number'     => '70',
								],
							],
						],
						//-----|

						//----->
						[
							'type'         => 'collection',
							'name'         => 'E2U2',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'       => 'choice',
									'status'     => Entity::STATUS_OPTIONAL,
									'name'       => '97a',
									'label'      => 'Счет',
									'fullLabel'  => '(see qualifier description)',
									'scheme'     => getChoiceScheme('97a', ['A', 'E']),
									'number'     => '71',
								],
							],
						],
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '20C',
							'label'     => 'Референс',
							'fullLabel' => 'Референс обработки',
							'mask'      => ':4!c//16x',
							'number'    => '72',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['PROC',]
								],
								[
									'label' => 'Референс',
								],
							],
						],
						[
							'type'         => 'collection',
							'name'         => 'E2U3',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'       => 'choice',
									'status'     => Entity::STATUS_OPTIONAL,
									'name'       => '70a',
									'label'      => 'Свободный текст',
									'fullLabel'  => '(see qualifier description)',
									'scheme'     => getChoiceScheme('70a', ['C', 'E']),
									'number'     => '73',
								],
							],
						],
						//-----|

						[
							'status'   => Entity::STATUS_MANDATORY,
							'name'     => '16S',
							'service'  => true,
							'constant' => true,
							'label'    => 'Конец блока',
							'value'    => 'CSHPRTY',
							'number'   => '74',
						],
					],
				],
				//-----| End of Subsequence E2 Cash Parties

				//-----> Optional Repetitive Subsequence E3 Amount
				[
					'type'   => 'collection',
					'status' => Entity::STATUS_OPTIONAL,
					'name'   => 'E3',
					'label'  => 'Суммы',
					'scheme' => [
						[
							'status'   => Entity::STATUS_MANDATORY,
							'name'     => '16R',
							'service'  => true,
							'constant' => true,
							'label'    => 'Начало блока',
							'value'    => 'AMT',
							'number'   => '75',
						],

						//----->
						[
							'type'         => 'collection',
							'name'         => 'E3U1',
							'disableLabel' => true,
							'scheme'       => [
								[
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '17B',
									'scheme'    => [
										[
											'label'  => 'Определитель',
											'strict' => ['ACRU', 'STAM']
										],
										[
											'label'  => 'Флаг',
											'strict' => ['N', 'Y']
										],
									],
									'label'     => 'Флаг',
									'fullLabel' => '(see qualifier description)',
									'mask'      => ':4!c//1!a',
									'number'    => '76',
								],
							],
						],
						//-----|

						//----->
						[
							'type'         => 'collection',
							'name'         => 'E3U2',
							'disableLabel' => true,
							'scheme'       => [
								[
									'status'    => Entity::STATUS_MANDATORY,
									'name'      => '19A',
									'label'     => 'Сумма',
									'fullLabel' => '(see qualifier description)',
									'mask'      => ':4!c//~[N]~3!a~15d',
									'number'    => '77',
									'scheme'    => [
										[
											'label'  => 'Определитель',
											'strict' => [
												'ACRU', 'ANTO', 'BOOK', 'CHAR', 'COMT', 'COUN', 'DEAL', 'ESTT',
												'EXEC', 'ISDI', 'LADT', 'LEVY', 'LOCL', 'LOCO', 'MARG', 'OTHR',
												'REGF', 'SHIP', 'SPCN', 'STAM', 'STEX', 'TRAN', 'TRAX', 'VATA',
												'WITH', 'COAX', 'ACCA', 'RESU', 'OCMT'
											]
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
									],
								],
							],
						],
						[
							'type'       => 'choice',
							'status'     => Entity::STATUS_OPTIONAL,
							'name'       => '98a',
							'label'      => 'Дата/Время',
							'fullLabel'  => 'Дата/Время валютирования',
							'scheme'     => getChoiceScheme('98a', ['A', 'C']),
							'number'     => '78',
						],
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '92B',
							'label'     => 'Курс',
							'fullLabel' => 'Курс конвертации',
							'mask'      => ':4!c//3!a/3!a/15d',
							'number'    => '79',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['EXCH',]
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
							],
						],

						[
							'status'   => Entity::STATUS_MANDATORY,
							'name'     => '16S',
							'service'  => true,
							'constant' => true,
							'label'    => 'Конец блока',
							'value'    => 'AMT',
							'number'   => '80',
						],
					],
				],
				//-----| End of Subsequence E3 Amount

				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16S',
					'service'  => true,
					'constant' => true,
					'label'    => 'Конец блока',
					'value'    => 'SETDET',
					'number'   => '81',
				],
			],
		],
		[
			'type'   => 'collection',
			'status' => Entity::STATUS_OPTIONAL,
			'name'   => 'F',
			'label'  => 'Прочие стороны',
			'scheme' => [
				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16R',
					'service'  => true,
					'constant' => true,
					'label'    => 'Начало блока',
					'value'    => 'OTHRPRTY',
					'number'   => '82',
				],

				//----->
				[
					'type'         => 'collection',
					'name'         => 'FU1',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'       => 'choice',
							'status'     => Entity::STATUS_MANDATORY,
							'name'       => '95a',
							'label'      => 'Сторона',
							'fullLabel'  => '(see qualifier description)',
							'scheme'     => getChoiceScheme('95a', ['C', 'P', 'Q', 'R', 'S']),
							'number'     => '83',
						],
					],
				],
				//-----|

				[
					'status'    => Entity::STATUS_OPTIONAL,
					'name'      => '97A',
					'label'     => 'Счет',
					'fullLabel' => 'Счет депо',
					'mask'      => ':4!c//35x',
					'number'    => '84',
					'scheme'    => [
						[
							'label'  => 'Определитель',
							'strict' => ['SAFE',]
						],
						[
							'label' => 'Номер счета',
						],
					],
				],
				//----->
				[
					'type'         => 'collection',
					'name'         => 'FU2',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'       => 'choice',
							'status'     => Entity::STATUS_OPTIONAL,
							'name'       => '70a',
							'label'      => 'Свободный текст',
							'fullLabel'  => '(see qualifier description)',
							'scheme'     => getChoiceScheme('70a', ['C', 'D', 'E']),
							'number'     => '85',
						],
					],
				],
				//-----|

				[
					'status'    => Entity::STATUS_OPTIONAL,
					'name'      => '20C',
					'label'  => 'Референс',
					'fullLabel' => 'Референс обработки',
					'mask'      => ':4!c//16x',
					'number'    => '86',
					'scheme'    => [
						[
							'label'  => 'Определитель',
							'strict' => ['PROC',]
						],
						[
							'label' => 'Референс',
						],
					],
				],

				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16S',
					'service'  => true,
					'constant' => true,
					'label'    => 'Конец блока',
					'value'    => 'OTHRPRTY',
					'number'   => '87',
				],
			],
		],
	]
];
