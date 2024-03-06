<?php
namespace addons\swiftfin\config\mt5xx;
include_once(__DIR__.'/choiceScheme.php');
include(__DIR__.'/../base/currency.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '548',
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
							'strict' => ['CAST', 'INST']
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
							'number'   => '5',
						],
						[
							'type'       => 'choice',
							'status'     => Entity::STATUS_OPTIONAL,
							'name'       => '13a',
							'label'      => 'Определение номера ',
							'fullLabel'  => 'Связанная операция',
							'scheme'     => getChoiceScheme('13a', ['A', 'B']),
							'number'     => '6',
						],
						[
							'name'      => '20C',
							'status'    => Entity::STATUS_MANDATORY,
							'label'     => 'Референс связанного сообщения',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['POOL', 'PREV', 'RELA', 'TRRF', 'COMM', 'CORP', 'TCTR', 'CLTR', 'CLCI',
												 'TRCI', 'NTSP', 'MITI', 'PCTI']
								],
								[
									'label' => 'Референс',
								],
							],
							'fullLabel' => '(См. описание определителей)',
							'mask'      => ':4!c//16x',
							'number'    => '7',
						],
						[
							'name'     => '16S',
							'status'   => Entity::STATUS_MANDATORY,
							'label'    => 'Конец блока',
							'service'  => true,
							'constant' => true,
							'value'    => 'LINK',
							'number'   => '8',
						],
					]
				],
				[
					'name'   => 'A2',
					'type'   => 'collection',
					'label'  => 'Статус',
					'status' => Entity::STATUS_MANDATORY,
					'scheme' => [
						[
							'name'     => '16R',
							'status'   => Entity::STATUS_MANDATORY,
							'label'    => 'Начало блока',
							'service'  => true,
							'constant' => true,
							'value'    => 'STAT',
							'number'   => '9',
						],
						[
							'name'      => '25D',
							'status'    => Entity::STATUS_MANDATORY,
							'label'     => 'Статус',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['CALL', 'CPRC', 'INMH', 'IPRC', 'MTCH', 'SETT', 'SPRC', 'TPRC']
								],
								[
									'label' => 'Система кодировки',
								],
								[
									'label'  => 'Код статуса',
									'strict' => ['CACK', 'CAND', 'CANP', 'CGEN', 'CPRC', 'DEND', 'MACH', 'MODC', 'MOPN',
												 'MPRC', 'NMAT', 'PACK', 'PEND']
								],
							],
							'fullLabel' => '(См. описание определителей)',
							'mask'      => ':4!c/[8c]/4!c',
							'number'    => '10',
						],
						[
							'name'   => 'A2a',
							'type'   => 'collection',
							'label'  => 'Причина',
							'status' => Entity::STATUS_OPTIONAL,
							'scheme' => [
								[
									'name'     => '16R',
									'status'   => Entity::STATUS_MANDATORY,
									'label'    => 'Начало блока',
									'service'  => true,
									'constant' => true,
									'value'    => 'REAS',
									'number'   => '11',
								],
								[
									'name'      => '24B',
									'status'    => Entity::STATUS_MANDATORY,
									'label'     => 'Причина',
									'scheme'    => [
										[
											'label'  => 'Определитель',
											'strict' => ['CAND', 'CANP', 'CGEN', 'CACK', 'DEND', 'MOPN', 'NMAT', 'PEND',
														 'PENF', 'PPRC', 'PACK', 'REJT', 'REPR']
										],
										[
											'label' => 'Система кодировки',
										],
										[
											'label'  => 'Код причины',
											'strict' => ['PACK', 'PHCK', 'PHSE', 'PREA', 'CPEC', 'ACRU', 'ADEA', 'AWMO',
														 'AWSH', 'BATC', 'BENO', 'BLOC', 'BOTH', 'BYIY', 'CACK', 'CADE',
														 'CAEV', 'CAIS', 'CALD', 'CALP', 'CAND', 'CANI', 'CANP', 'CANR',
														 'CANS', 'CANT', 'CANZ', 'CASH', 'CASY', 'CDAC', 'CDLR', 'CERT',
														 'CGEN', 'CHAS', 'CLAC', 'CLAT', 'CLHT', 'CMIS', 'CMON', 'COLL',
														 'CONF', 'CORP', 'CPCA', 'CSDH', 'CSUB', 'CTHP', 'CVAL', 'CYCL',
														 'DCAL', 'DCAN', 'DDAT', 'DDEA', 'DELN', 'DEND', 'DENO', 'DEPO',
														 'DEPT', 'DFOR', 'DISA', 'DKNY', 'DMON', 'DOCC', 'DOCY', 'DPRG',
														 'DQUA', 'DREP', 'DSEC', 'DSET', 'DTRA', 'DTRD', 'EXPI', 'FLIM',
														 'FORF', 'FRAP', 'FROZ', 'FUTU', 'GLOB', 'IAAD', 'ICAG', 'ICUS',
														 'IEXE', 'IIND', 'INBC', 'INCA', 'INPS', 'INVB', 'INVL', 'INVN',
														 'LAAW', 'LACK', 'LALO', 'LATE', 'LEOG', 'LINK', 'LIQU', 'MIME',
														 'MINO', 'MISM', 'MLAT', 'MONY', 'MOPN', 'MUNO', 'NARR', 'NCON',
														 'NCRR', 'NEWI', 'NEXT', 'NMAS', 'NMAT', 'NOFX', 'NRGM', 'NRGN',
														 'OBJT', 'PART', 'PEND', 'PENF', 'PHYS', 'PLCE', 'PODU', 'PPRC',
														 'PRCY', 'PRSY', 'REFE', 'REFS', 'REFU', 'REGD', 'REGT', 'REJT',
														 'REPA', 'REPO', 'REPP', 'REPR', 'RERT', 'RODE', 'RTGS', 'SAFE',
														 'SBLO', 'SCEX', 'SDUT', 'SETR', 'SETS', 'SMPG', 'SPLI', 'STCD',
														 'TAMM', 'TERM', 'THRD', 'TRAN', 'TXST', 'UNBR', 'VALR', 'VASU',
														 'YCOL']
										],
									],
									'fullLabel' => '(См. описание определителей)',
									'mask'      => ':4!c/[8c]/4!c',
									'number'    => '12',
								],
								[
									'name'      => '70D',
									'status'    => Entity::STATUS_OPTIONAL,
									'label'     => 'Свободный текст/ Описание причины',
									'scheme'    => [
										[
											'label'  => 'Определитель',
											'strict' => ['REAS',]
										],
										[
											'label' => 'Свободный текст',
										],
									],
									'fullLabel' => 'Указание причины в свободном тексте',
									'mask'      => ':4!c//6*35x',
									'number'    => '13',
								],
								[
									'name'     => '16S',
									'status'   => Entity::STATUS_MANDATORY,
									'label'    => 'Конец блока',
									'service'  => true,
									'constant' => true,
									'value'    => 'REAS',
									'number'   => '14',
								],
							]
						],
						[
							'name'     => '16S',
							'status'   => Entity::STATUS_MANDATORY,
							'label'    => 'Конец блока',
							'service'  => true,
							'constant' => true,
							'value'    => 'STAT',
							'number'   => '15',
						],
					],
				],
				[
					'name'     => '16S',
					'status'   => Entity::STATUS_MANDATORY,
					'label'    => 'Конец блока',
					'service'  => true,
					'constant' => true,
					'value'    => 'GENL',
					'number'   => '16',
				],
			],
		],
		[
			'name'   => 'B',
			'type'   => 'sequence',
			'label'  => 'Детали расчетной операции',
			'status' => Entity::STATUS_OPTIONAL,
			'scheme' => [
				[
					'name'     => '16R',
					'status'   => Entity::STATUS_MANDATORY,
					'label'    => 'Начало блока',
					'service'  => true,
					'constant' => true,
					'value'    => 'SETTRAN',
					'number'   => '17',
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
							'scheme'     => getChoiceScheme('94a', ['B', 'C', 'F', 'H']),
							'number'     => '18',
						],
					],
				],
				[
					'name'   => '35B',
					'status' => Entity::STATUS_MANDATORY,
					'label'  => 'Определение финансового инструмента',
					'mask'   => "[ISIN1!e12!c]~".Entity::INLINE_BREAK."[4*35x]",
					'number' => '19',
					'scheme' => [
						[
							'label' => 'Определение финансового инструмента',
						],
						[
							'label' => 'Описание финансового инструмента',
						],
					],
				],
				[
					'type'         => 'collection',
					'name'         => 'BU2',
					'disableLabel' => true,
					'scheme'       => [
						[
							'status'    => Entity::STATUS_MANDATORY,
							'name'      => '36B',
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
							'label'     => 'Количество ценных бумаг',
							'fullLabel' => 'Количество финансового инструмента, подлежащее расчетам',
							'mask'      => ':4!c//4!c/15d',
							'number'    => '20',
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
							'name'      => '19A',
							'label'     => 'Сумма',
							'fullLabel' => '(see qualifier description)',
							'mask'      => ':4!c//~[N]~3!a~15d',
							'number'    => '21',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['OCMT', 'SETT']
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
					'type'       => 'choice',
					'status'     => Entity::STATUS_OPTIONAL,
					'name'       => '95a',
					'label'      => 'Сторона',
					'fullLabel'  => 'Владелец счета',
					'scheme'     => getChoiceScheme('95a', ['P', 'R']),
					'number'     => '22',
				],
				[
					'name'       => '97a',
					'status'     => Entity::STATUS_MANDATORY,
					'label'      => 'Количество ценных бумаг',
					'type'       => 'choice',
					'fullLabel'  => 'Счет депо',
					'scheme'     => getChoiceScheme('97a', ['A', 'B']),
					'number'     => '23',
				],
				[
					'type'         => 'collection',
					'name'         => 'BU4',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'       => 'choice',
							'status'     => Entity::STATUS_MANDATORY,
							'name'       => '22a',
							'label'      => 'Признак',
							'fullLabel'  => '(see qualifier description)',
							'scheme'     => getChoiceScheme('22a', ['F', 'H']),
							'number'     => '24',
						],
					],
				],
				[
					'type'         => 'collection',
					'name'         => 'BU5',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'       => 'choice',
							'status'     => Entity::STATUS_MANDATORY,
							'name'       => '98a',
							'label'      => 'Дата/Время',
							'fullLabel'  => '(see qualifier description)',
							'scheme'     => getChoiceScheme('98a', ['A', 'B', 'C', 'E']),
							'number'     => '25',
						],
					],
				],
				[
					'status'    => Entity::STATUS_OPTIONAL,
					'name'      => '70E',
					'label'     => 'Свободный текст',
					'fullLabel' => 'Сведения  об  обработке расчетных инструкций в свободном тексте',
					'mask'      => ':4!c//10*35x',
					'number'    => '26',
					'scheme'    => [
						[
							'label'  => 'Определитель',
							'strict' => ['SPRO']
						],
						[
							'label' => 'Свободный текст',
						],
					],
				],
				[
					'name'   => 'B1',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Стороны при расчетах',
					'type'   => 'collection',
					'scheme' => [
						[
							'name'     => '16R',
							'status'   => Entity::STATUS_MANDATORY,
							'label'    => 'Начало блока',
							'service'  => true,
							'constant' => true,
							'value'    => 'SETPRTY',
							'number'   => '27',
						],
						[
							'name'       => '95a',
							'status'     => Entity::STATUS_MANDATORY,
							'label'      => 'Сторона/ Место проведения расчётов',
							'type'       => 'choice',
							'scheme'     => getChoiceScheme('95a', ['C', 'P', 'Q', 'R']),
							'fullLabel'  => '(see qualifier description)',
							'number'     => '28',
						],
						[
							'name'       => '97a',
							'status'     => Entity::STATUS_OPTIONAL,
							'label'      => 'Счет',
							'type'       => 'choice',
							'scheme'     => getChoiceScheme('97a', ['A', 'B']),
							'fullLabel'  => 'Счет депо',
							'scheme'     => getChoiceScheme('97a', ['A', 'B']),
							'number'     => '29',
						],
						[
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '20C',
							'label'     => 'Референс',
							'fullLabel' => 'Референс обработки',
							'mask'      => ':4!c//16x',
							'number'    => '30',
							'scheme'    => [
								[
									'label'  => 'Функция',
									'strict' => ['PROC']
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
							'value'    => 'SETPRTY',
							'number'   => '31',
						],
					],
				],
				[
					'name'     => '16S',
					'status'   => Entity::STATUS_MANDATORY,
					'label'    => 'Конец блока',
					'service'  => true,
					'constant' => true,
					'value'    => 'SETTRAN',
					'number'   => '32',
				],
			],
		],
		[
			'type'   => 'sequence',
			'status' => Entity::STATUS_OPTIONAL,
			'name'   => 'C',
			'label'  => 'Дополнительная информация',
			'scheme' => [
				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16R',
					'service'  => true,
					'constant' => true,
					'label'    => 'Начало блока',
					'value'    => 'ADDINFO',
					'number'   => '32',
				],
				[
					'type'         => 'collection',
					'name'         => 'CU1',
					'disableLabel' => true,
					'scheme'       => [
						[
							'type'       => 'choice',
							'status'     => Entity::STATUS_MANDATORY,
							'name'       => '95a',
							'label'      => 'Сторона',
							'fullLabel'  => '(see qualifier description)',
							'scheme'     => getChoiceScheme('95a', ['C', 'P', 'Q', 'R']),
							'number'     => '34',
						],
					],
				],
				[
					'name'     => '16S',
					'status'   => Entity::STATUS_MANDATORY,
					'label'    => 'Конец блока',
					'service'  => true,
					'constant' => true,
					'value'    => 'ADDINFO',
					'number'   => '35',
				]
			]
		]
	]
];
