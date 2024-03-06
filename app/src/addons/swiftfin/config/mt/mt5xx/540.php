<?php
namespace addons\swiftfin\config\mt5xx;
include_once(__DIR__.'/choiceScheme.php');
include(__DIR__.'/../base/currency.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '540',
	'formable' => true,
	'scheme'   => [
		// Mandatory Sequence A General Information
		[
			'type'   => 'sequence',
			'status' => Entity::STATUS_MANDATORY,
			'name'   => 'A',
			'label'  => 'General Information',
			'scheme' => [
				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16R',
					'service'  => true,
					'constant' => true,
					'label'    => 'Start of Block',
					'value'    => 'GENL',
					'number'   => '1',
				],

				[
					'status'    => Entity::STATUS_MANDATORY,
					'name'      => '20C',
					'label'     => 'Reference',
					'fullLabel' => 'Sender\'s Message Reference',
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
					'label'  => 'Function of the Message',
					'mask'   => '4!c[/4!c]',
					'number' => '3',
					'scheme' => [
						[
							'label'  => 'Функция',
							'strict' => ['CANC', 'NEWM', 'PREA']
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
					'label'     => 'Date/Time',
					'fullLabel' => 'Preparation Date/Time',
					'scheme'    => getChoiceScheme('98a', ['A', 'C', 'E']),
					'number'    => '4',
				],

				//----->
				[
					'type'         => 'collection',
					'name'         => '99B',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '99B',
							'label'     => 'Number Count',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c//3!n',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['SETT', 'TOSE']
								],
								[
									'label' => 'Количество',
								],
							],
							'number'    => '5',
						],
					],
				],
				//-----|

				//-----> Optional Repetitive Subsequence A1 Linkages
				[
					'type'   => 'collection',
					'name'   => 'A1',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Linkages',
					'scheme' => [
						[
							'status'   => Entity::STATUS_MANDATORY,
							'name'     => '16R',
							'service'  => true,
							'constant' => true,
							'label'    => 'Start of Block',
							'value'    => 'LINK',
							'number'   => '6',
						],

						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '22F',
							'label'     => 'Indicator',
							'fullLabel' => 'Linkage Type Indicator',
							'mask'      => ':4!c/[8c]/4!c',
							'number'    => '7',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['LINK']
								],
								[
									'label' => 'Система кодировки',
								],
								[
									'label' => 'Признак',
								],
							]
						],

						[
							'type'      => 'choice',
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '13a',
							'label'     => 'Number Identification',
							'fullLabel' => 'Linked Message',
							'scheme'    => getChoiceScheme('13a', ['A', 'B']),
							'number'    => '8',
						],

						[
							'status'    => Entity::STATUS_MANDATORY,
							'name'      => '20C',
							'label'     => 'Reference',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c//16x',
							'number'    => '9',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['POOL', 'PREA', 'PREV', 'RELA', 'TRRF', 'COMM', 'COLR', 'CERT', 'CLCI',
												 'CLTR', 'PCTI', 'TRCI', 'TCTR']
								],
								[
									'label' => 'Референс',
								],
							]
						],

						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '36B',
							'label'     => 'Quantity of Financial Instrument',
							'fullLabel' => '(see qualifier description)',
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
							]
						],

						[
							'status'   => Entity::STATUS_MANDATORY,
							'name'     => '16S',
							'service'  => true,
							'constant' => true,
							'label'    => 'End of Block',
							'value'    => 'LINK',
							'number'   => '11',
						],
					],
				],
				//-----| End of Subsequence A1 Linkages

				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16S',
					'service'  => true,
					'constant' => true,
					'label'    => 'End of Block',
					'value'    => 'GENL',
					'number'   => '12',
				],
			],
		],
		// End of Sequence A General Information

		// Mandatory Sequence B Trade Details
		[
			'type'   => 'sequence',
			'name'   => 'B',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Trade Details',

			'scheme' => [
				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16R',
					'service'  => true,
					'constant' => true,
					'label'    => 'Start of Block',
					'value'    => 'TRADDET',
					'number'   => '13',
				],

				//----->
				[
					'type'         => 'collection',
					'name'         => '94',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'      => 'choice',
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '94a',
							'label'     => 'Place',
							'fullLabel' => '(see qualifier description)',
							'scheme'    => getChoiceScheme('94a', ['B', 'H']),
							'number'    => '14',
						],
					],
				],
				//-----|

				//----->
				[
					'type'         => 'collection',
					'name'         => '98a',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'      => 'choice',
							'status'    => Entity::STATUS_MANDATORY,
							'name'      => '98a',
							'label'     => 'Date/Time',
							'fullLabel' => '(see qualifier description)',
							'scheme'    => getChoiceScheme('98a', ['A', 'B', 'C', 'E']),
							'number'    => '15',
						],
					],
				],
				//-----|

				[
					'type'      => 'choice',
					'status'    => Entity::STATUS_OPTIONAL,
					'name'      => '90a',
					'label'     => 'Price',
					'fullLabel' => 'Deal Price',
					'scheme'    => getChoiceScheme('90a', ['A', 'B']),
					'number'    => '16',
				],

				[
					'status'    => Entity::STATUS_OPTIONAL,
					'name'      => '99A',
					'label'     => 'Number Count',
					'fullLabel' => 'Number of Days Accrued',
					'mask'      => ':4!c//[N]3!n',
					'number'    => '17',
					'scheme'    => [
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
					]
				],

				[
					'status'    => Entity::STATUS_MANDATORY,
					'name'      => '35B',
					'label'     => 'Identification of the Financial Instrument',
					'fullLabel' => 'Identification of the Financial Instrument',
					// Важно: здесь перевод строки для многострочного поля
					'mask'      => "[ISIN1!e12!c]~".Entity::INLINE_BREAK."[4*35x]",
					'number'    => '18',
					'scheme'    => [
						[
							'label' => 'Определение финансового инструмента',
						],
						[
							'label' => 'Описание финансового инструмента',
						],
					]
				],

				// Optional Subsequence B1 Financial Instrument Attributes
				[
					'type'   => 'sequence',
					'status' => Entity::STATUS_OPTIONAL,
					'name'   => 'B1',
					'label'  => 'Financial Instrument Attributes',
					'scheme' => [
						[
							'status'   => Entity::STATUS_MANDATORY,
							'name'     => '16R',
							'service'  => true,
							'constant' => true,
							'label'    => 'Start of Block',
							'value'    => 'FIA',
							'number'   => '19',
						],

						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '94B',
							'label'     => 'Place',
							'fullLabel' => 'Place of Listing',
							'mask'      => ':4!c/[8c]/4!c[/30x]',
							'number'    => '20',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['PLIS'],
								],
								[
									'label' => 'Система кодировки',
								],
								[
									'label'  => 'Код места',
									'strict' => ['EXCH', 'OTCO']
								],
								[
									'label' => 'Свободный текст',
								],
							]
						],

						//----->
						[
							'type'         => 'collection',
							'name'         => '22F',
							'disableLabel' => true,
							'scheme'       => [
								[
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '22F',
									'label'     => 'Indicator',
									'fullLabel' => '(see qualifier description)',
									'mask'      => ':4!c/[8c]/4!c',
									'number'    => '21',
									'scheme'    => [
										[
											'label'  => 'Определитель',
											'strict' => ['MICO', 'FORM', 'PFRE', 'PAYS', 'CFRE'],
										],
										[
											'label' => 'Система кодировки',
										],
										[
											'label'  => 'Признак',
											'strict' => [
												/**
												 * Если определитель имеет значение «MICO», а подполе «Система кодировки» не используется
												 */
												'A001', 'A002', 'A003', 'A004', 'A005', 'A006', 'A007', 'A008', 'A009',
												'A010', 'A011', 'A012', 'A013', 'A014', 'OTHR',
												/**
												 * Если определитель имеет значение «FORM», а подполе «Система кодировки» не используется
												 */
												'BEAR', 'REGD',
												/**
												 * Если определитель имеет значение «PFRE», а подполе «Система кодировки» не используется
												 */
												'ANNU', 'MNTH', 'QUTR', 'SEMI', 'WEEK',
												/**
												 * Если определитель имеет значение «PAYS», а подполе «Система кодировки» не используется
												 */
												'FULL', 'NILL', 'PART',
												/**
												 * Если определитель имеет значение «CFRE», а подполе «Система кодировки» не используется
												 */
												'ANNU', 'MNTH', 'QUTR', 'SEMI', 'WEEK'
											]
										],
									]
								],
							],
						],
						//-----|

						//----->
						[
							'type'         => 'collection',
							'name'         => '12a',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'      => 'choice',
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '12a',
									'label'     => 'Type of Financial Instrument',
									'fullLabel' => '(see qualifier description)',
									'scheme'    => getChoiceScheme('12a', ['A', 'B', 'C']),
									'number'    => '22',
								],
							],
						],
						//-----|

						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '11A',
							'label'     => 'Currency',
							'fullLabel' => 'Currency of Denomination',
							'mask'      => ':4!c//3!a',
							'number'    => '23',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['DENO'],
								],
								[
									'label' => 'Код валюты',
									'strict' => $currency,
                                    'name' => 'currency'
								],
							]
						],

						//----->
						[
							'type'         => 'collection',
							'name'         => '98A',
							'disableLabel' => true,
							'scheme'       => [
								[
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '98A',
									'label'     => 'Date',
									'fullLabel' => '(see qualifier description)',
									'mask'      => ':4!c//8!n',
									'number'    => '24',
									'scheme'    => [
										[
											'label'  => 'Определитель',
											'strict' => ['COUP', 'EXPI', 'FRNR', 'MATU', 'ISSU', 'CALD', 'PUTT', 'DDTE',
														 'FCOU'],
										],
										[
											'label' => 'Дата',
                                            'name' => 'date'
										],
									]
								],
							],
						],
						//-----|

						//----->
						[
							'type'         => 'collection',
							'name'         => '92A',
							'disableLabel' => true,
							'scheme'       => [
								[
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '92A',
									'label'     => 'Rate',
									'fullLabel' => '(see qualifier description)',
									'mask'      => ':4!c//[N]15d',
									'number'    => '25',
									'scheme'    => [
										[
											'label'  => 'Определитель',
											'strict' => ['PRFC', 'CUFC', 'NWFC', 'INTR', 'NXRT', 'INDX', 'YTMR'],
										],
										[
											'label' => 'Знак',
										],
										[
											'label' => 'Ставка',
										],
									]
								],
							],
						],
						//-----|

						//----->
						[
							'type'         => 'collection',
							'name'         => '13a',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'      => 'choice',
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '13a',
									'label'     => 'Number Identification',
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
							'name'         => '17B',
							'disableLabel' => true,
							'scheme'       => [
								[
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '17B',
									'label'     => 'Flag',
									'fullLabel' => '(see qualifier description)',
									'mask'      => ':4!c//1!a',
									'number'    => '27',
									'scheme'    => [
										[
											'label'  => 'Определитель',
											'strict' => ['FRNF', 'CALL', 'PUTT'],
										],
										[
											'label'  => 'Флаг',
											'strict' => ['Y', 'N'],
										],
									]
								],
							],
						],
						//-----|

						//----->
						[
							'type'         => 'collection',
							'name'         => '90a',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'      => 'choice',
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '90a',
									'label'     => 'Price',
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
							'name'         => '36B',
							'disableLabel' => true,
							'scheme'       => [
								[
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '36B',
									'label'     => 'Quantity of Financial Instrument',
									'fullLabel' => '(see qualifier description)',
									'mask'      => ':4!c//4!c/15d',
									'number'    => '29',
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
									]
								],
							],
						],
						//-----|

						//----->
						[
							'type'         => 'collection',
							'name'         => '35B',
							'disableLabel' => true,
							'scheme'       => [
								[
									'status' => Entity::STATUS_OPTIONAL,
									'name'   => '35B',
									'label'  => 'Identification of the Financial Instrument',
									// Важно: здесь перевод строки для многострочного поля
									'mask'   => "[ISIN1!e12!c]~".Entity::INLINE_BREAK."[4*35x]",
									'number' => '30',
									'scheme' => [
										[
											'label' => 'Определение финансового инструмента',
										],
										[
											'label' => 'Описание финансового инструмента',
										],
									]
								],
							],
						],
						//-----|

						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '70E',
							'label'     => 'Narrative',
							'fullLabel' => 'Financial Instrument Attribute Narrative',
							'mask'      => ':4!c//10*35x',
							'number'    => '31',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['FIAN'],
								],
								[
									'label' => 'Свободный текст',
								],
							]
						],

						[
							'status'   => Entity::STATUS_MANDATORY,
							'name'     => '16S',
							'service'  => true,
							'constant' => true,
							'label'    => 'End of Block',
							'value'    => 'FIA',
							'number'   => '32',
						],
					],
				],
				// End of Subsequence B1 Financial Instrument Attributes

				//----->
				[
					'type'         => 'collection',
					'name'         => '22F',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '22F',
							'label'     => 'Indicator',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c/[8c]/4!c',
							'number'    => '33',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['PROC', 'RPOR', 'PRIR', 'BORR', 'TTCO', 'INCA', 'TRCA', 'PRIC'],
								],
								[
									'label' => 'Система кодировки',
								],
								[
									'label'  => 'Признак',
									'strict' => [
										/**
										 * Если определитель имеет значение «PROC», а подполе «Система кодировки» не используется
										 */
										'CLOP', 'OPEP',
										/**
										 * Если определитель имеет значение «RPOR», а подполе «Система кодировки» не используется
										 */
										'DEFR', 'EXCH', 'TRRE',
										/**
										 * Если определитель имеет значение «PRIR», а подполе «Система кодировки» не
										 * используется, то подполе «Признак» должно содержать числовое значение в
										 * диапазоне от 0001 до 9999, где 0001 означает наивысший приоритет
										 */
										// [0001-9999],
										/**
										 * Если определитель имеет значение «BORR», а подполе «Система кодировки» не используется
										 */
										'LAMI', 'NBOR', 'YBOR',
										/**
										 * Если определитель имеет значение «TTCO», а подполе «Система кодировки» не используется
										 */
										'BCBL', 'BCBN', 'BCFD', 'BCPD', 'BCRO', 'BCRP', 'CBNS', 'CCPN', 'CDIV', 'CRTS',
										'CWAR', 'GTDL', 'GTDL', 'MAPR', 'NEGO', 'NMPR', 'SPCU', 'SPEX', 'XBNS', 'XCPN',
										'XDIV', 'XRTS', 'XWAR',
										/**
										 * Если используется определитель «INCA», а подполе «Система кодировки» не используется
										 */
										'ELIG', 'PROF', 'RETL',
										/**
										 * Если используется определитель «TRCA», а подполе «Система кодировки» не используется
										 */
										'INFI', 'MKTM', 'MLTF', 'RMKT', 'SINT', 'TAGT',
										/**
										 * Если определитель имеет значение «PRIC», а подполе «Система кодировки» не используется
										 */
										'AVER',
										/**
										 * Коды, используемые с определителем «INCA», должны быть согласованы на
										 * двусторонней основе.
										 */
									]
								],
							]
						],
					],
				],
				//-----|

				[
					'status'    => Entity::STATUS_OPTIONAL,
					'name'      => '11A',
					'label'     => 'Currency',
					'fullLabel' => 'Currency to Sell',
					'mask'      => ':4!c//3!a',
					'number'    => '34',
					'scheme'    => [
						[
							'label'  => 'Определитель',
							'strict' => ['FXIS'],
						],
						[
							'label' => 'Код валюты',
							'strict' => $currency,
                            'name' => 'currency'
						],
					]
				],

				//----->
				[
					'type'         => 'collection',
					'name'         => '25D',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '25D',
							'label'     => 'Status',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c/[8c]/4!c',
							'number'    => '35',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['MTCH', 'AFFM'],
								],
								[
									'label' => 'Система кодировки',
								],
								[
									'label'  => 'Код статуса',
									'strict' => [
										/**
										 * Если поле имеет определитель «AFFM», а подполе «Система кодировки» не используется
										 */
										'AFFI', 'NAFI',
										/**
										 * Если поле имеет определитель «MTCH», а подполе «Система кодировки» не используется
										 */
										'MACH', 'NMAT',
									],
								],
							]
						],
					],
				],
				//-----|

				//----->
				[
					'type'         => 'collection',
					'name'         => '70E',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '70E',
							'label'     => 'Narrative',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c//10*35x',
							'number'    => '36',
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
					],
				],
				//-----|

				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16S',
					'service'  => true,
					'constant' => true,
					'label'    => 'End of Block',
					'value'    => 'TRADDET',
					'number'   => '37',
				],
			],
		],
		// End of Sequence B Trade Details

		// Mandatory Sequence C Financial Instrument/Account
		[
			'type'   => 'sequence',
			'status' => Entity::STATUS_MANDATORY,
			'name'   => 'C',
			'label'  => 'Financial Instrument/Account',
			'scheme' => [
				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16R',
					'service'  => true,
					'constant' => true,
					'label'    => 'Start of Block',
					'value'    => 'FIAC',
					'number'   => '38',
				],

				//----->
				[
					'type'         => 'collection',
					'name'         => '36B',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_MANDATORY,
							'name'      => '36B',
							'label'     => 'Quantity of Financial Instrument',
							'fullLabel' => 'Quantity of Financial Instrument to be Settled',
							'mask'      => ':4!c//4!c/15d',
							'number'    => '39',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['SETT'],
								],
								[
									'label'  => 'Код типа количества',
									'strict' => ['AMOR', 'FAMT', 'UNIT'],
								],
								[
									'label' => 'Количество',
								],
							]
						],
					],
				],
				//-----|

				[
					'status'    => Entity::STATUS_OPTIONAL,
					'name'      => '70D',
					'label'     => 'Narrative',
					'fullLabel' => 'Denomination Choice',
					'mask'      => ':4!c//6*35x',
					'number'    => '40',
					'scheme'    => [
						[
							'label'  => 'Определитель',
							'strict' => ['DENC'],
						],
						[
							'label' => 'Свободный текст',
						],
					]
				],

				//----->
				[
					'type'         => 'collection',
					'name'         => '13B',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '13B',
							'label'     => 'Number Identification',
							'fullLabel' => 'Certificate Number',
							'mask'      => ':4!c/[8c]/30x',
							'number'    => '41',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['CERT'],
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
				//-----|

				[
					'type'      => 'choice',
					'status'    => Entity::STATUS_OPTIONAL,
					'name'      => '95a',
					'label'     => 'Party',
					'fullLabel' => 'Account Owner',
					'scheme'    => getChoiceScheme('95a', ['P', 'R']),
					'number'    => '42',
				],

				//----->
				[
					'type'         => 'collection',
					'name'         => '97a',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'      => 'choice',
							'status'    => Entity::STATUS_MANDATORY,
							'name'      => '97a',
							'label'     => 'Account',
							'fullLabel' => '(see qualifier description)',
							'scheme'    => getChoiceScheme('97a', ['A', 'B', 'E']),
							'number'    => '43',
						],
					],
				],
				//-----|

				[
					'type'      => 'choice',
					'status'    => Entity::STATUS_OPTIONAL,
					'name'      => '94a',
					'label'     => 'Place',
					'fullLabel' => 'Place of Safekeeping',
					'scheme'    => getChoiceScheme('94a', ['B', 'C', 'F']),
					'number'    => '44',
				],

				//-----> Optional Repetitive Subsequence C1 Quantity Breakdown
				[
					'type'   => 'collection',
					'status' => Entity::STATUS_OPTIONAL,
					'name'   => 'C1',
					'label'  => 'Quantity Breakdown',

					'scheme' => [
						[
							'status'   => Entity::STATUS_MANDATORY,
							'name'     => '16R',
							'service'  => true,
							'constant' => true,
							'label'    => 'Start of Block',
							'value'    => 'BREAK',
							'number'   => '45',
						],

						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '13B',
							'label'     => 'Lot Number',
							'fullLabel' => 'Number Identification',
							'mask'      => ':4!c/[8c]/30x',
							'number'    => '46',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['LOTS'],
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
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '36B',
							'label'     => 'Quantity of Financial Instrument',
							'fullLabel' => 'Quantity of Financial Instrument In The Lot',
							'mask'      => ':4!c//4!c/15d',
							'number'    => '47',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['LOTS'],
								],
								[
									'label'  => 'Код типа количества',
									'strict' => ['AMOR', 'FAMT', 'UNIT'],
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
							'label'     => 'Date/Time',
							'fullLabel' => 'Lot Date/Time',
							'scheme'    => getChoiceScheme('98a', ['A', 'C', 'E']),
							'number'    => '48',
						],

						[
							'type'      => 'choice',
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '90a',
							'label'     => 'Price',
							'fullLabel' => 'Book/Lot Price',
							'scheme'    => getChoiceScheme('90a', ['A', 'B']),
							'number'    => '49',
						],

						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '22F',
							'label'     => 'Indicator',
							'fullLabel' => 'Type of Price Indicator',
							'mask'      => ':4!c/[8c]/4!c',
							'number'    => '50',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['PRIC'],
								],
								[
									'label' => 'Система кодировки',
								],
								[
									'label' => 'Признак',
									/**
									 * Если подполе «Система кодировки» не используется, подполе «Признак»
									 */
									//'strict' => ['AVER'],
								],
							]
						],

						[
							'status'   => Entity::STATUS_MANDATORY,
							'name'     => '16S',
							'service'  => true,
							'constant' => true,
							'label'    => 'End of Block',
							'value'    => 'BREAK',
							'number'   => '51',
						],
					],
				],
				//-----| End of Subsequence C1 Quantity Breakdown

				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16S',
					'service'  => true,
					'constant' => true,
					'label'    => 'End of Block',
					'value'    => 'FIAC',
					'number'   => '52',
				],
			],
		],
		// End of Sequence C Financial Instrument/Account

		// Optional Sequence D Two Leg Transaction Details
		[
			'type'   => 'sequence',
			'status' => Entity::STATUS_OPTIONAL,
			'name'   => 'D',
			'label'  => 'Two Leg Transaction Details',

			'scheme' => [
				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16R',
					'service'  => true,
					'constant' => true,
					'label'    => 'Start of Block',
					'value'    => 'REPO',
					'number'   => '53',
				],

				//----->
				[
					'type'         => 'collection',
					'name'         => '98a',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'      => 'choice',
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '98a',
							'label'     => 'Date/Time',
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
					'name'         => '22F',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '22F',
							'label'     => 'Indicator',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c/[8c]/4!c',
							'number'    => '55',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['RERT', 'MICO', 'REVA', 'LEGA', 'OMAT', 'INTR'],
								],
								[
									'label' => 'Система кодировки',
								],
								[
									'label' => 'Признак',
									// @todo перенести strict после ввода зависимостей подполей
								],
							]
						],
					],
				],
				//-----|

				//----->
				[
					'type'         => 'collection',
					'name'         => '20C',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '20C',
							'label'     => 'Reference',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c//16x',
							'number'    => '56',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['SECO', 'REPO'],
								],
								[
									'label' => 'Референс',
								],
							]
						],
					],
				],
				//-----|

				//----->
				[
					'type'         => 'collection',
					'name'         => '92a',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'      => 'choice',
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '92a',
							'label'     => 'Rate',
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
					'name'         => '99B',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '99B',
							'label'     => 'Number Count',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c//3!n',
							'number'    => '58',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['CADE', 'TOCO'],
								],
								[
									'label' => 'Количество',
								],
							]
						],
					],
				],
				//-----|

				//----->
				[
					'type'         => 'collection',
					'name'         => '19A',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '19A',
							'label'     => 'Amount',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c//~[N]~3!a~15d',
							'number'    => '59',
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
                                    'name' => 'currency'
								],
								[
									'label' => 'Сумма',
                                    'name' => 'sum'
								],
							]

						],
					],
				],
				//-----|

				[
					'status'    => Entity::STATUS_OPTIONAL,
					'name'      => '70C',
					'label'     => 'Narrative',
					'fullLabel' => 'Second Leg Narrative',
					'mask'      => ':4!c//4*35x',
					'number'    => '60',
					'scheme'    => [
						[
							'label'  => 'Определитель',
							'strict' => ['SECO'],
						],
						[
							'label' => 'Свободный текст',
						],
					]
				],

				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16S',
					'service'  => true,
					'constant' => true,
					'label'    => 'End of Block',
					'value'    => 'REPO',
					'number'   => '61',
				],
			],
		],
		// End of Sequence D Two Leg Transaction Details

		// Mandatory Sequence E Settlement Details
		[
			'type'   => 'sequence',
			'status' => Entity::STATUS_MANDATORY,
			'name'   => 'E',
			'label'  => 'Settlement Details',

			'scheme' => [
				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16R',
					'service'  => true,
					'constant' => true,
					'label'    => 'Start of Block',
					'value'    => 'SETDET',
					'number'   => '62',
				],

				//----->
				[
					'type'         => 'collection',
					'name'         => '22F',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_MANDATORY,
							'name'      => '22F',
							'label'     => 'Indicator',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c/[8c]/4!c',
							'number'    => '63',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => [
										'STCO', 'SETR', 'TRCA', 'STAM', 'RTGS', 'REGT', 'BENE', 'CASY', 'DBNM', 'TCPI',
										'MACL', 'FXCX', 'BLOC', 'REST', 'SETS', 'NETT', 'CCPT', 'LEOG', 'COLA', 'TRAK',
										'REPT', 'COLE', 'SSBT', 'CSBT',
									],
								],
								[
									'label' => 'Система кодировки',
								],
								[
									'label' => 'Признак',
									// @todo добавить strict после ввода зависимостей
								],
							]
						],
					],
				],
				//-----|

				//-----> Mandatory Repetitive Subsequence E1 Settlement Parties
				[
					'type'   => 'collection',
					'status' => Entity::STATUS_MANDATORY,
					'name'   => 'E1',
					'label'  => 'Settlement Parties',
					'scheme' => [
						[
							'status'   => Entity::STATUS_MANDATORY,
							'name'     => '16R',
							'service'  => true,
							'constant' => true,
							'label'    => 'Start of Block',
							'value'    => 'SETPRTY',
							'number'   => '64',
						],

						//----->
						[
							'type'         => 'collection',
							'name'         => '95a',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'      => 'choice',
									'status'    => Entity::STATUS_MANDATORY,
									'name'      => '95a',
									'label'     => 'Party',
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
							'label'     => 'Account',
							'fullLabel' => 'Safekeeping Account',
							'scheme'    => getChoiceScheme('97a', ['A', 'B']),
							'number'    => '66',
						],

						[
							'type'      => 'choice',
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '98a',
							'label'     => 'Date/Time',
							'fullLabel' => 'Processing Date/Time',
							'scheme'    => getChoiceScheme('98a', ['A', 'C']),
							'number'    => '67',
						],

						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '20C',
							'label'     => 'Reference',
							'fullLabel' => 'Processing Reference',
							'mask'      => ':4!c//16x',
							'number'    => '68',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['PROC'],
								],
								[
									'label' => 'Референс',
								],
							]
						],

						//----->
						[
							'type'         => 'collection',
							'name'         => '70a',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'      => 'choice',
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '70a',
									'label'     => 'Narrative',
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
							'label'    => 'End of Block',
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
					'label'  => 'Cash Parties',
					'scheme' => [
						[
							'status'   => Entity::STATUS_MANDATORY,
							'name'     => '16R',
							'service'  => true,
							'constant' => true,
							'label'    => 'Start of Block',
							'value'    => 'CSHPRTY',
							'number'   => '71',
						],

						//----->
						[
							'type'         => 'collection',
							'name'         => '95a',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'      => 'choice',
									'status'    => Entity::STATUS_MANDATORY,
									'name'      => '95a',
									'label'     => 'Party',
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
							'name'         => '97a',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'      => 'choice',
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '97a',
									'label'     => 'Account',
									'fullLabel' => '(see qualifier description)',
									'scheme'    => getChoiceScheme('97a', ['A', 'E']),
									'number'    => '73',
								],
							],
						],
						//-----|

						//----->
						[
							'type'         => 'collection',
							'name'         => '70a',
							'disableLabel' => true,
							'scheme'       => [
								[
									'type'      => 'choice',
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '70a',
									'label'     => 'Narrative',
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
							'label'    => 'End of Block',
							'value'    => 'CSHPRTY',
							'number'   => '75',
						],
					],
				],
				//-----| End of Subsequence E2 Cash Parties

				//-----> Optional Repetitive Subsequence E3 Amount
				[
					'type'   => 'collection',
					'status' => Entity::STATUS_OPTIONAL,
					'name'   => 'E3',
					'label'  => 'Amount',
					'scheme' => [
						[
							'status'   => Entity::STATUS_MANDATORY,
							'name'     => '16R',
							'service'  => true,
							'constant' => true,
							'label'    => 'Start of Block',
							'value'    => 'AMT',
							'number'   => '76',
						],

						//----->
						[
							'type'         => 'collection',
							'name'         => '17B',
							'disableLabel' => true,
							'scheme'       => [
								[
									'status'    => Entity::STATUS_OPTIONAL,
									'name'      => '17B',
									'label'     => 'Flag',
									'fullLabel' => '(see qualifier description)',
									'mask'      => ':4!c//1!a',
									'number'    => '77',
									'scheme'    => [
										[
											'label'  => 'Определитель',
											'strict' => ['ACRU', 'STAM'],
										],
										[
											'label'  => 'Код',
											'strict' => ['Y', 'N'],
										],
									]
								],
							],
						],
						//-----|

						//----->
						[
							'type'         => 'collection',
							'name'         => '19A',
							'disableLabel' => true,
							'scheme'       => [
								[
									'status'    => Entity::STATUS_MANDATORY,
									'name'      => '19A',
									'label'     => 'Amount',
									'fullLabel' => '(see qualifier description)',
									'mask'      => ':4!c//~[N]~3!a~15d',
									'number'    => '78',
									'scheme'    => [
										[
											'label'  => 'Определитель',
											'strict' => [
												'ACRU', 'CHAR', 'COUN', 'DEAL', 'EXEC', 'ISDI', 'LADT', 'LEVY', 'LOCL',
												'LOCO', 'MARG', 'OTHR', 'REGF', 'SETT', 'SHIP', 'SPCN', 'STAM', 'STEX',
												'TRAN', 'TRAX', 'VATA', 'WITH', 'ANTO', 'BOOK', 'COAX', 'ACCA', 'RESU',
												'OCMT',
											],
										],
										[
											'label' => 'Знак',
										],
										[
											'label' => 'Код валюты',
											'strict' => $currency,
                                            'name' => 'currency'
										],
										[
											'label' => 'Сумма',
                                            'name' => 'sum'
										],
									]
								],
							],
						],
						//-----|

						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '92B',
							'label'     => 'Rate',
							'fullLabel' => 'Exchange Rate',
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
                                    'name' => 'currency1'
								],
								[
									'label' => 'Код второй валюты',
									'strict' => $currency,
                                    'name' => 'currency2'
								],
								[
									'label' => 'Курс',
								],
							]
						],

						[
							'status'   => Entity::STATUS_MANDATORY,
							'name'     => '16S',
							'service'  => true,
							'constant' => true,
							'label'    => 'End of Block',
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
					'label'    => 'End of Block',
					'value'    => 'SETDET',
					'number'   => '81',
				],
			],
		],
		// End of Sequence E Settlement Details

		//-----> Optional Repetitive Sequence F Other Parties
		[
			'type'   => 'collection',
			'status' => Entity::STATUS_OPTIONAL,
			'name'   => 'F',
			'label'  => 'Other Parties',
			'scheme' => [
				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16R',
					'service'  => true,
					'constant' => true,
					'label'    => 'Start of Block',
					'value'    => 'OTHRPRTY',
					'number'   => '82',
				],

				//----->
				[
					'type'         => 'collection',
					'name'         => '95a',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'      => 'choice',
							'status'    => Entity::STATUS_MANDATORY,
							'name'      => '95a',
							'label'     => 'Party',
							'fullLabel' => '(see qualifier description)',
							'scheme'    => getChoiceScheme('95a', ['C', 'P', 'Q', 'R', 'S']),
							'number'    => '83',
						],
					],
				],
				//-----|

				[
					'status'    => Entity::STATUS_OPTIONAL,
					'name'      => '97A',
					'label'     => 'Account',
					'fullLabel' => 'Safekeeping Account',
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

				//----->
				[
					'type'         => 'collection',
					'name'         => '70a',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'      => 'choice',
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '70a',
							'label'     => 'Narrative',
							'fullLabel' => '(see qualifier description)',
							'scheme'    => getChoiceScheme('70a', ['C', 'D', 'E']),
							'number'    => '85',
						],
					],
				],
				//-----|

				[
					'status'    => Entity::STATUS_OPTIONAL,
					'name'      => '20C',
					'label'     => 'Reference',
					'fullLabel' => 'Processing Reference',
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
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16S',
					'service'  => true,
					'constant' => true,
					'label'    => 'End of Block',
					'value'    => 'OTHRPRTY',
					'number'   => '87',
				],
			],
		],
		//-----| End of Sequence F Other Parties
	],
];