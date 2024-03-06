<?php
namespace addons\swiftfin\config\mt5xx;
include_once(__DIR__.'/choiceScheme.php');
include(__DIR__.'/../base/currency.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '543',
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
							'strict' => ['CODU', 'COPY', 'DUPL', 'RECO']
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
					'type'         => 'collection',
					'name'         => 'AU1',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '99B',
							'label'     => 'Количество',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c//3!n',
							'number'    => '5',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['SETT', 'TOSE']
								],
								[
									'label' => 'Количество',
								],
							],
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
							'label'     => 'Признак',
							'fullLabel' => 'Признак типа связок',
							'mask'      => ':4!c/[8c]/4!c',
							'number'    => '7',
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
									// см. доку
//									'strict' => ['AFTE', 'BEFO', 'INFO', 'WITH']
								],
							],
						],
						[
							'type'      => 'choice',
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '13a',
							'label'     => 'Определение номера ',
							'fullLabel' => 'Связанная операция',
							'scheme'    => getChoiceScheme('98a', ['A', 'B']),
							'number'    => '8',
						],
						[
							'name'      => '20C',
							'status'    => Entity::STATUS_MANDATORY,
							'label'     => 'Референс',
							'fullLabel' => '(См. описание определителей)',
							'mask'      => ':4!c//16x',
							'number'    => '9',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => [
										'POOL', 'PREV', 'RELA', 'TRRF', 'COMM', 'COLR', 'CORP', 'TCTR', 'CLTR',
										'CLCI', 'TRCI', 'MITI', 'PCTI'
									]
								],
								[
									'label' => 'Референс',
								],
							],
						],
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '36B',
							'label'     => 'Количество финансового инструмента',
							'fullLabel' => 'Количество финансового инструмента в лоте',
							'mask'      => ':4!c//4!c/15d',
							'number'    => '10',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['PAIR', 'TURN']
								],
								[
									'label'  => 'Код типа количества',
									'strict' => ['AMOR', 'FAMT', 'UNIT']

								],
								[
									'label' => 'Количество',
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
							'number'   => '11',
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
					'number'   => '12',
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
					'number'   => '13',
				],
				[
					'type'         => 'collection',
					'name'         => 'BU1',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'      => 'choice',
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '94a',
							'label'     => 'Место',
							'fullLabel' => '(see qualifier description)',
							'scheme'    => getChoiceScheme('94a', ['B', 'H']),
							'number'    => '14',
						],
					],
				],
				[
					'type'         => 'collection',
					'name'         => 'BU2',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'      => 'choice',
							'status'    => Entity::STATUS_MANDATORY,
							'name'      => '98a',
							'label'     => 'Дата/Время',
							'fullLabel' => '(see qualifier description)',
							'scheme'    => getChoiceScheme('98a', ['A', 'B', 'C', 'E']),
							'number'    => '15',
						],
					],
				],
				[
					'type'      => 'choice',
					'status'    => Entity::STATUS_OPTIONAL,
					'name'      => '90a',
					'label'     => 'Цена',
					'fullLabel' => 'Цена сделки',
					'scheme'    => getChoiceScheme('90a', ['A', 'B']),
					'number'    => '16',
				],
				[
					'status'    => Entity::STATUS_OPTIONAL,
					'name'      => '99A',
					'label'     => 'Количество',
					'fullLabel' => 'Количество дней, за которые начисляются проценты',
					'mask'      => ':4!c//[N]3!n',
					'number'    => '17',
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
				],
				
				[
					'status'    => Entity::STATUS_MANDATORY,
					'name'      => '35B',
					'label'     => 'Определение финансового инструмента',
					'fullLabel' => 'Определение финансового инструмента',
					'mask'      => "[ISIN1!e12!c]~".Entity::INLINE_BREAK."[4*35x]",
					'number'    => '18',
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
							'number'   => '19',
						],
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '94B',
							'label'     => 'Место',
							'fullLabel' => 'Место котировки',
							'mask'      => ':4!c/[8c]/4!c[/30x]',
							'number'    => '20',
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
//									'strict' => ['EXCH', 'OTCO'] // см. доку
								],
								[
									'label' => 'Свободный текст',
								],
							],
						],
						[
							'type'         => 'collection',
							'name'         => 'B1U1',
							'disableLabel' => true,
							'scheme'       => [
								[
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '22F',
									'label'     => 'Признак',
									'fullLabel' => '(see qualifier description)',
									'mask'      => ':4!c/[8c]/4!c',
									'number'    => '21',
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
											// см. доку
//											'strict' => ['A001', 'A002', 'A003', 'A004', 'A005', 'A006', 'A007', 'A008',
//														 'A009', 'A010', 'A011', 'A012', 'A013', 'A014', 'OTHR', 'BEAR',
//														 'REGD', 'ANNU', 'MNTH', 'QUTR', 'SEMI', 'WEEK', 'FULL', 'NILL',
//														 'PART', 'ANNU', 'MNTH', 'QUTR', 'SEMI', 'WEEK']
										],
									],
								],
							],
						],
						[
							'type'         => 'collection',
							'name'         => 'B1U2',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'      => 'choice',
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '12a',
									'label'     => 'Тип финансового инструмента ',
									'fullLabel' => '(see qualifier description)',
									'scheme'    => getChoiceScheme('12a', ['A', 'B', 'C']),
									'number'    => '22',
								],
							],
						],
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '11A',
							'label'     => 'Валюта',
							'fullLabel' => 'Валюта номинала',
							'mask'      => ':4!c//3!a',
							'number'    => '23',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['DENO',]
								],
								[
									'label' => 'Код валюты',
									'strict' => $currency
								],
							],
						],
						[
							'type'         => 'collection',
							'name'         => 'B1U3',
							'disableLabel' => true,
							'scheme'       => [
								[
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '98A',
									'label'     => 'Дата',
									'fullLabel' => '(see qualifier description)',
									'mask'      => ':4!c//8!n',
									'number'    => '24',
									'scheme'    => [
										[
											'label'  => 'Определитель',
											'strict' => [
												'COUP', 'EXPI', 'FRNR', 'MATU', 'ISSU', 'CALD', 'PUTT', 'DDTE',
												'FCOU'
											]
										],
										[
											'label' => 'Дата',
										],
									],
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
									'label'     => 'Ставка',
									'fullLabel' => '(see qualifier description)',
									'mask'      => ':4!c//[N]15d',
									'number'    => '25',
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
								],
							],
						],
						[
							'type'         => 'collection',
							'name'         => 'B1U5',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'      => 'choice',
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '13a',
									'label'     => 'Определение номера',
									'fullLabel' => '(see qualifier description)',
									'scheme'    => getChoiceScheme('13a', ['A', 'B']),
									'number'    => '26',
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
									'number'    => '27',
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
									'number'    => '28',
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
									'number'    => '29',
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
									'mask'   => "[ISIN1!e12!c]~".Entity::INLINE_BREAK."[4*35x]",
									'number' => '30',
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
							'number'    => '31',
						],

						[
							'status'   => Entity::STATUS_MANDATORY,
							'name'     => '16S',
							'service'  => true,
							'constant' => true,
							'label'    => 'Конец блока',
							'value'    => 'FIA',
							'number'   => '32',
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
							'label'     => 'Признак',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c/[8c]/4!c',
							'number'    => '33',
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
									// @todo и плюс Если определитель имеет значение «PRIR», а подполе «Система кодировки» не используется, то подполе «Признак» должно содержать числовое значение в диапазоне от 0001 до 9999, где 0001 означает наивысший приоритет
//									'strict' => ['CLOP', 'OPEP', 'DEFR', 'EXCH', 'TRRE', 'LAMI', 'NBOR', 'YBOR', 'BCBL',
//												 'BCBN', 'BCFD', 'BCPD', 'BCRO', 'BCRP', 'CBNS', 'CCPN', 'CDIV', 'CRTS',
//												 'CWAR', 'GTDL', 'MAPR', 'NEGO', 'NMPR', 'SPCU', 'SPEX', 'XBNS', 'XCPN',
//												 'XDIV', 'XRTS', 'XWAR', 'ELIG', 'PROF', 'RETL', 'INFI', 'MKTM', 'MLTF',
//												 'RMKT', 'SINT', 'TAGT', 'AVER']
								],
							],
						],
					],
				],
				[
					'status'    => Entity::STATUS_OPTIONAL,
					'name'      => '11A',
					'label'     => 'Валюта',
					'fullLabel' => 'Код валюты',
					'mask'      => ':4!c//3!a',
					'number'    => '34',
					'scheme'    => [
						[
							'label'  => 'Определитель',
							'strict' => ['FXIB',]
						],
						[
							'label' => 'Код валюты',
							'strict' => $currency
						],
					],
				],
				[
					'type'         => 'collection',
					'name'         => 'BU4',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '25D',
							'label'     => 'Статус',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c/[8c]/4!c',
							'number'    => '35',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['MTCH', 'AFFM']
								],
								[
									'label' => 'Система кодировки',
								],
								[
									'label'  => 'Код статуса',
//									'strict' => ['AFFI', 'NAFI', 'MACH', 'NMAT'] // см. доку
								],
							],
						],
					],
				],
				[
					'type'         => 'collection',
					'name'         => 'BU5',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '70E',
							'label'     => 'Свободный текст',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c//10*35x',
							'number'    => '36',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['FXIN', 'SPRO']
								],
								[
									'label' => 'Свободный текст',
								],
							],
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
					'number'   => '37',
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
					'number'   => '38',
				],
				[
					'type'         => 'collection',
					'name'         => 'CU1',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_MANDATORY,
							'name'      => '36B',
							'label'     => 'Количество финансового инструмента',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c//4!c/15d',
							'number'    => '39',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['SETT']
								],
								[
									'label'  => 'Код типа количества',
									'strict' => ['AMOR', 'FAMT', 'UNIT']

								],
								[
									'label' => 'Количество',
								],
							],
						],
					],
				],
				[
					'name'      => '70D',
					'status'    => Entity::STATUS_OPTIONAL,
					'label'     => 'Свободный текст',
					'fullLabel' => 'Выбор номинала',
					'mask'      => ':4!c//6*35x',
					'number'    => '40',
					'scheme'    => [
						[
							'label'  => 'Определитель',
							'strict' => ['DENC',]
						],
						[
							'label' => 'Свободный текст',
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
							'name'      => '13B',
							'label'     => 'Определение номера',
							'fullLabel' => 'Номер сертификата',
							'mask'      => ':4!c/[8c]/30x',
							'number'    => '41',
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
						],
					],
				],
				[
					'type'      => 'choice',
					'status'    => Entity::STATUS_OPTIONAL,
					'name'      => '95a',
					'label'     => 'Сторона',
					'fullLabel' => 'Владелец счета',
					'scheme'    => getChoiceScheme('95a', ['P', 'R']),
					'number'    => '42',
				],
				[
					'type'         => 'collection',
					'name'         => 'CU3',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'      => 'choice',
							'status'    => Entity::STATUS_MANDATORY,
							'name'      => '97a',
							'label'     => 'Счет',
							'fullLabel' => '(see qualifier description)',
							'scheme'    => getChoiceScheme('97a', ['A', 'B', 'E']),
							'number'    => '43',
						],
					],
				],
				[
					'type'      => 'choice',
					'status'    => Entity::STATUS_OPTIONAL,
					'name'      => '94a',
					'label'     => 'Место',
					'fullLabel' => 'Место хранения',
					'scheme'    => getChoiceScheme('94a', ['B', 'C', 'F']),
					'number'    => '44',
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
							'number'   => '45',
						],
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '13B',
							'label'     => 'Определение номера',
							'fullLabel' => 'Номер лота',
							'mask'      => ':4!c/[8c]/30x',
							'number'    => '46',
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
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '36B',
							'label'     => 'Количество финансового инструмента',
							'fullLabel' => 'Количество финансового инструмента в лоте',
							'mask'      => ':4!c//4!c/15d',
							'number'    => '47',
						],
						[
							'type'      => 'choice',
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '98a',
							'label'     => 'Дата/Время',
							'fullLabel' => 'Дата/Время лота',
							'scheme'    => getChoiceScheme('98a', ['A', 'C', 'E']),
							'number'    => '48',
						],

						[
							'type'      => 'choice',
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '90a',
							'label'     => 'Цена',
							'fullLabel' => 'Учетная цена лота',
							'scheme'    => getChoiceScheme('90a', ['A', 'B']),
							'number'    => '49',
						],
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '22F',
							'label'     => 'Признак',
							'fullLabel' => 'Признак типа связок',
							'mask'      => ':4!c/[8c]/4!c',
							'number'    => '50',
							'scheme'    => [
								[
								 'label'  => 'Определитель',
								 'strict' => ['PRIC',]
								],
								[
									'label' => 'Система кодировки',
								],
								[
									'label'  => 'Признак',
//									'strict' => ['AVER'] // см. доку
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
							'number'   => '51',
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
					'number'   => '52',
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
					'number'   => '53',
				],
				[
					'type'         => 'collection',
					'name'         => 'DU1',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'      => 'choice',
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '98a',
							'label'     => 'Дата/Время',
							'fullLabel' => '(see qualifier description)',
							'scheme'    => getChoiceScheme('98a', ['A', 'B', 'C']),
							'number'    => '54',
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
							'number'    => '55',
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
//									'strict' => ['A001', 'A002', 'A003', 'A004', 'A005', 'A006', 'A007', 'A008', 'A009',
//												 'A010', 'A011', 'A012', 'A013', 'A014', 'OTHR', 'FIXE', 'FORF', 'VARI',
//												 'REVY', 'REVN', 'FRAN', 'GIVE', 'TAKE']
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
							'label'     => 'Референс',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c//16x',
							'number'    => '56',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['SECO', 'REPO']
								],
								[
									'label' => 'Референс',
								],
							],
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
							'type'      => 'choice',
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '92a',
							'label'     => 'Ставка',
							'fullLabel' => '(see qualifier description)',
							'scheme'    => getChoiceScheme('92a', ['A', 'C']),
							'number'    => '57',
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
							'number'    => '58',
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
							'number'    => '59',
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
									'strict' => $currency
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
					'number'    => '60',
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
					'number'   => '61',
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
					'number'   => '62',
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
							'number'    => '63',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['STCO', 'SETR', 'TRCA', 'STAM', 'RTGS', 'REGT', 'BENE', 'CASY', 'DBNM',
												 'TCPI', 'MACL', 'FXCX', 'BLOC', 'REST', 'SETS', 'NETT', 'CCPT', 'LEOG',
												 'RTRN', 'COLA', 'TRAK', 'REPT', 'COLE', 'SSBT', 'CSBT']
								],
								[
									'label' => 'Система кодировки',
								],
								[
									'label'  => 'Признак',
//									'strict' => ['144A', 'AGEN', 'ASGN', 'BFWD', 'BLCH', 'BLPA', 'BRKR', 'BSBK', 'CADJ',
//												 'CALL', 'CCIR', 'CLEN', 'CLNT', 'CNCB', 'COLA', 'COLI', 'COLN', 'COLO',
//												 'COMM', 'CONV', 'CRDS', 'CRPR', 'CRSP', 'CRTL', 'CUST', 'DIRT', 'DLWM',
//												 'DMON', 'DQUA', 'DRAW', 'DUEB', 'EQPT', 'EQUS', 'ETFT', 'EXER', 'EXPT',
//												 'EXTD', 'FCTA', 'FIXI', 'FORW', 'FORX', 'FRCL', 'FUTR', 'FXNO', 'FXYE',
//												 'GROS', 'INSP', 'INTE', 'ISSU', 'KNOC', 'LIQU', 'MAKT', 'MKDW', 'MKUP',
//												 'NBEN', 'NCCP', 'NETS', 'NETT', 'NLEG', 'NNET', 'NOMC', 'NPAR', 'NREG',
//												 'NRST', 'NRTG', 'NSET', 'NSYN', 'OPTN', 'OTCD', 'OWNE', 'OWNI', 'PADJ',
//												 'PAIR', 'PARD', 'PART', 'PAYM', 'PENS', 'PHYS', 'PLAC', 'PORT', 'PRIN',
//												 'RATE', 'REAL', 'REDI', 'REDM', 'RELE', 'REPO', 'REPU', 'RESI', 'RHYP',
//												 'RODE', 'ROLP', 'RPTO', 'RSTR', 'RVPO', 'SAFE', 'SAGE', 'SBBK', 'SBRE',
//												 'SBSB', 'SCIE', 'SCIR', 'SCRP', 'SECB', 'SECL', 'SHOR', 'SINO', 'SLEB',
//												 'SLOA', 'SLRE', 'SPDL', 'SPRI', 'SPST', 'SUBS', 'SWPT', 'SYND', 'TBAC',
//												 'TBAS', 'TCRP', 'TOPU', 'TRAC', 'TRAD', 'TRAN', 'TRIP', 'TRPO', 'TRVO',
//												 'TURN', 'UNEX', 'UNRE', 'UNTR', 'VEND', 'WTHD', 'YBEN', 'YCCP', 'YLEG',
//												 'YNET', 'YREG', 'YRTG', 'YSET']
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
							'number'   => '64',
						],

						//----->
						[
							'type'         => 'collection',
							'name'         => 'E1U1',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'      => 'choice',
									'status'    => Entity::STATUS_MANDATORY,
									'name'      => '95a',
									'label'     => 'Сторона',
									'fullLabel' => '(see qualifier description)',
									'scheme'    => getChoiceScheme('95a', ['C', 'P', 'Q', 'R', 'S']),
									'number'    => '65',
								],
							],
						],
						//-----|

						[
							'type'      => 'choice',
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '97a',
							'label'     => 'Счет',
							'fullLabel' => 'Счет депо',
							'scheme'    => getChoiceScheme('97a', ['A', 'B']),
							'number'    => '66',
						],

						[
							'type'      => 'choice',
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '98a',
							'label'     => 'Дата/Время',
							'fullLabel' => 'Дата/Время обработки',
							'scheme'    => getChoiceScheme('98a', ['A', 'C']),
							'number'    => '67',
						],

						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '20C',
							'label'     => 'Референс',
							'fullLabel' => 'Референс обработки',
							'mask'      => ':4!c//16x',
							'number'    => '68',
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
									'type'      => 'choice',
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '70a',
									'label'     => 'Свободный текст',
									'fullLabel' => '(see qualifier description)',
									'scheme'    => getChoiceScheme('70a', ['C', 'D', 'E']),
									'number'    => '69',
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
							'number'   => '70',
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
							'number'   => '71',
						],

						//----->
						[
							'type'         => 'collection',
							'name'         => 'E2U1',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'      => 'choice',
									'status'    => Entity::STATUS_MANDATORY,
									'name'      => '95a',
									'label'     => 'Сторона',
									'fullLabel' => '(see qualifier description)',
									'scheme'    => getChoiceScheme('95a', ['P', 'Q', 'R', 'S']),
									'number'    => '72',
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
									'type'      => 'choice',
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '97a',
									'label'     => 'Счет',
									'fullLabel' => '(see qualifier description)',
									'scheme'    => getChoiceScheme('97a', ['A', 'E']),
									'number'    => '73',
								],
							],
						],
						[
							'type'         => 'collection',
							'name'         => 'E2U3',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'      => 'choice',
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '70a',
									'label'     => 'Свободный текст',
									'fullLabel' => '(see qualifier description)',
									'scheme'    => getChoiceScheme('70a', ['C', 'E']),
									'number'    => '74',
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
							'number'   => '75',
						],
					],
				],
				//-----| End of Subsequence E2 Cash Parties

				//-----> Mandatory Repetitive Subsequence E3 Amount
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
							'number'   => '76',
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
									'label'     => 'Флаг',
									'fullLabel' => '(see qualifier description)',
									'mask'      => ':4!c//1!a',
									'number'    => '77',
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
									'number'    => '78',
									'scheme'    => [
										[
											'label'  => 'Определитель',
											'strict' => [
												'ACRU', 'ANTO', 'BOOK', 'CHAR', 'COMT', 'COUN', 'DEAL', 'ESTT', 'EXEC',
												'ISDI', 'LADT', 'LEVY', 'LOCL', 'LOCO', 'MARG', 'OTHR', 'REGF', 'SETT',
												'SHIP', 'SPCN', 'STAM', 'STEX', 'TRAN', 'TRAX', 'VATA', 'WITH', 'COAX',
												'ACCA', 'RESU', 'OCMT'
											]
										],
										[
											'label' => 'Знак',
										],
										[
											'label' => 'Код валюты',
											'strict' => $currency
										],
										[
											'label' => 'Сумма',
										],
									],
								],
							],
						],
						[
							'type'      => 'choice',
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '98a',
							'label'     => 'Дата/Время',
							'fullLabel' => 'Дата/Время валютирования',
							'scheme'    => getChoiceScheme('98a', ['A', 'C']),
							'number'    => '79',
						],
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '92B',
							'label'     => 'Курс',
							'fullLabel' => 'Курс конвертации',
							'mask'      => ':4!c//3!a/3!a/15d',
							'number'    => '80',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['EXCH',]
								],
								[
									'label' => 'Код первой валюты',
									'strict' => $currency
								],
								[
									'label' => 'Код второй валюты',
									'strict' => $currency
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
							'number'   => '81',
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
					'number'   => '82',
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
					'number'   => '83',
				],

				//----->
				[
					'type'         => 'collection',
					'name'         => 'FU1',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'      => 'choice',
							'status'    => Entity::STATUS_MANDATORY,
							'name'      => '95a',
							'label'     => 'Сторона',
							'fullLabel' => '(see qualifier description)',
							'scheme'    => getChoiceScheme('95a', ['C', 'P', 'Q', 'R', 'S']),
							'number'    => '84',
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
					'number'    => '85',
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
							'type'      => 'choice',
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '70a',
							'label'     => 'Свободный текст',
							'fullLabel' => '(see qualifier description)',
							'scheme'    => getChoiceScheme('70a', ['C', 'D', 'E']),
							'number'    => '86',
						],
					],
				],
				//-----|

				[
					'status'    => Entity::STATUS_OPTIONAL,
					'name'      => '20C',
					'label'     => 'Референс',
					'fullLabel' => 'Референс обработки',
					'mask'      => ':4!c//16x',
					'number'    => '87',
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
					'number'   => '88',
				],
			],
		],
	]
];
