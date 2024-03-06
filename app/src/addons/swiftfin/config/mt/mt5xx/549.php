<?php
namespace addons\swiftfin\config\mt5xx;
include_once(__DIR__.'/choiceScheme.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '549',
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
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16R',
					'service'  => true,
					'constant' => true,
					'label'    => 'Начало блока',
					'value'    => 'GENL',
					'number'   => '1',
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
					'label'  => 'Функция сообщения',
					'mask'   => '4!c[/4!c]',
					'number' => '3',
					'scheme' => [
						[
							'label'  => 'Функция',
							'strict' => ['CANC', 'NEWM',]
						],
						[
							'label'  => 'Подфункция',
							'strict' => ['CODU', 'COPY', 'DUPL',]
						],
					],
				],
				[
					'type'   => 'choice',
					'status' => Entity::STATUS_OPTIONAL,
					'name'   => '98a',
					'label'  => 'Дата/время выписки',
					'scheme' => getChoiceScheme('98a', ['A', 'C']),
					'number' => '4',
				],
				[
					'type'   => 'choice',
					'status' => Entity::STATUS_OPTIONAL,
					'name'   => '69a',
					'label'  => 'Период выписки',
					'scheme' => getChoiceScheme('69a', ['A', 'B']),
					'number' => '5',
				],
				[
					'status' => Entity::STATUS_MANDATORY,
					'name'   => '13A',
					'label'  => 'Тип запрошенного сообщения',
					'mask'   => ':4!c//3!c',
					'number' => '6',
					'scheme' => [
						[
							'label'  => 'Определитель',
							'strict' => ['REQU',]
						],
						[
							'label'  => 'Определение номера',
							'strict' => [
								506, 507, 509, 510, 535, 536, 537, 538, 548, 558, 567, 569, 575, 576, 577, 586
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
					'number' => '7',
				],
				[
					'type'   => 'choice',
					'status' => Entity::STATUS_MANDATORY,
					'name'   => '97a',
					'label'  => 'Счет/ Счет депо',
					'scheme' => getChoiceScheme('97a', ['A', 'B']),
					'number' => '8',
				],
				[
					'type'         => 'collection',
					'disableLabel' => true,
					'name'         => '22F',
					'scheme'       => [
						[
							'name'      => '22F',
							'status'    => Entity::STATUS_OPTIONAL,
							'label'     => 'Признак',
							'fullLabel' => '(См. описание определителей)',
							'number'    => '9',
							'mask'      => ':4!c/[8c]/4!c',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['CODE', 'SFRE', 'STTY', 'STBA',]
								],
								[
									'label' => 'Система кодировки',
								],
								[
									'label' => 'Признак',
								],
							],
						]
					]
				],

				//-----> Необязательная повторяющаяся подпоследовательность А1  Связки 
				[
					'type'   => 'collection',
					'name'   => 'A1',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Связки',

					'scheme' => [
						[
							'status'   => Entity::STATUS_MANDATORY,
							'name'     => '16R',
							'service'  => true,
							'constant' => true,
							'label'    => 'Начало блока',
							'value'    => 'LINK',
							'number'   => '10',
						],

						[
							'type'      => 'choice',
							'status'    => Entity::STATUS_OPTIONAL,
							'name'      => '13a',
							'label'     => 'Определение',
							'fullLabel' => 'Связанное сообщение',
							'scheme'    => getChoiceScheme('13a', ['A', 'B']),
							'number'    => '11',
						],

						[
							'status'    => Entity::STATUS_MANDATORY,
							'name'      => '20C',
							'label'     => 'Референс',
							'fullLabel' => '(См. описание определителей)',
							'mask'      => ':4!c//16x',
							'number'    => '12',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => ['PREV', 'RELA',]
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
							'label'    => 'Конец блока ',
							'value'    => 'LINK',
							'number'   => '13',
						],
					],
				],
				//-----| Конец подпоследовательности А1  Связки

				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16S',
					'service'  => true,
					'constant' => true,
					'label'    => 'Конец блока',
					'value'    => 'GENL',
					'number'   => '14',
					// 'field'     => 'hiddenInput',
					// 'invisible'     => true
				],
			]
		],
		//-----|Конец последовательности А  Общая информация

		//−−  Необязательная повторяющаяся последовательность В  Определение выписки по статусу/причине и/или по финансовому инструменту
		[
			'name'   => 'B',
			'type'   => 'collection',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Выписка по статусу/причине и/или по финансовому инструменту',
			'scheme' => [
				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16R',
					'service'  => true,
					'constant' => true,
					'label'    => 'Начало блока',
					'value'    => 'BYSTAREA',
					'number'   => '15',
				],
				[
					'status' => Entity::STATUS_OPTIONAL,
					'name'   => '25D',
					'label'  => 'Статус',
					'number' => '16',
					'mask'   => ':4!c/[8c]/4!c',
					'scheme' => [
						[
							'label'  => 'Определитель',
							'strict' => [
								'AFFM', 'CPRC', 'EPRC', 'INMH', 'IPRC', 'MTCH', 'SETT', 'RPRC', 'RERC', 'CALL',
								'ESTA', 'TPRC', 'REST', 'ALOC'
							]
						],
						[
							'label' => 'Система кодировки',
						],
						[
							'label' => 'Код статуса',
						],
					],
				],
				[
					'type'         => 'collection',
					'disableLabel' => true,
					'name'         => '24B',
					'scheme'       => [
						[
							'name'      => '24B',
							'status'    => Entity::STATUS_OPTIONAL,
							'label'     => 'Причина',
							'fullLabel' => '(См. описание определителей)',
							'mask'      => ':4!c/[8c]/4!c',
							'number'    => '17',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => [
										'NMAT', 'PEND', 'PENF', 'REJT', 'DEND', 'CAND', 'CANP', 'MOPN', 'NAFI', 'PACK',
										'CACK', 'REPR', 'PPRC', 'CGEN'
									]
								],
								[
									'label' => 'Система кодировки',
								],
								[
									'label' => 'Код статуса',
								],
							],
						],
					],
				],
				[
					'type'         => 'collection',
					'disableLabel' => true,
					'name'         => '35B',
					'scheme'       => [
						[
							'name'      => '35B',
							'status'    => Entity::STATUS_OPTIONAL,
							'label'     => 'Определение финансового инструмента',
							'fullLabel' => '(Определение финансового инструмента)',
							'mask'      => "[ISIN1!e12!c]~".Entity::INLINE_BREAK."[4*35x]",
							'number'    => '18',
						],
					],
				],
				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16S',
					'service'  => true,
					'constant' => true,
					'label'    => 'Конец блока ',
					'value'    => 'BYSTAREA',
					'number'   => '19',
				],
			],
		],
		// -----|    Конец последовательности В  Определение выписки по статусу/причине и/или по финансовому инструменту

		// Необязательная повторяющаяся подпоследовательность С  Определение выписки по референсу инструкций
		[
			'name'   => 'C',
			'type'   => 'collection',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => ' Определение выписки по референсу инструкций',
			'scheme' => [
				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16R',
					'service'  => true,
					'constant' => true,
					'label'    => 'Начало блока',
					'value'    => 'REF',
					'number'   => '20',
				],
				[
					'type'         => 'collection',
					'disableLabel' => true,
					'name'         => '20C',
					'scheme'       => [
						[
							'name'      => '20C',
							'status'    => Entity::STATUS_OPTIONAL,
							'label'     => 'Референс',
							'fullLabel' => '(См. описание определителей)',
							'mask'      => ':4!c//16x',
							'number'    => '21',
							'scheme'    => [
								[
									'label'  => 'Определитель',
									'strict' => [
										'PREV', 'TRRF', 'COMM', 'RELA', 'MAST', 'BASK', 'INDX', 'LIST', 'PROG',
										'POOL', 'CORP', 'MITI', 'PCTI'
									]
								],
								[
									'label' => 'Референс',
								],
							],
						]
					],
				],
				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16S',
					'service'  => true,
					'constant' => true,
					'label'    => 'Конец блока ',
					'value'    => 'REF',
					'number'   => '22',
				]
			],
		],
		// -----|    Конец последовательности С  Определение выписки по референсу инструкций

		// Необязательная последовательность D  Дополнительная информация
		[
			'name'   => 'D',
			'type'   => 'sequence',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Дополнительная информация',
			'scheme' => [
				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16R',
					'service'  => true,
					'constant' => true,
					'label'    => 'Начало блока',
					'value'    => 'ADDINFO',
					'number'   => '23',
				],
				[
					'type'         => 'collection',
					'disableLabel' => true,
					'name'         => '95a',
					'scheme'       => [
						[
							'name'   => '95a',
							'type'   => 'choice',
							'status' => Entity::STATUS_MANDATORY,
							'label'  => 'Сторона',
							'scheme' => getChoiceScheme('95a', ['P', 'R', 'Q']),
							'number' => '24',

						]
					]
				],
				[
					'status'   => Entity::STATUS_MANDATORY,
					'name'     => '16S',
					'service'  => true,
					'constant' => true,
					'label'    => 'Начало блока',
					'value'    => 'ADDINFO',
					'number'   => '25',
				],
			]
		],
	],
];
