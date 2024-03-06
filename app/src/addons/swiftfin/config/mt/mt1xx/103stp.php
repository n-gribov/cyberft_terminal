<?php
namespace addons\swiftfin\config\mt1xx;
include_once(__DIR__.'/choiceScheme.php');
include(__DIR__.'/../base/currency.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;
use yii\helpers\Url;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'formable' => true,
	'type'     => '103STP',
	'aliases'  => [
		'currency' => ['32A', 'currency'],
		'sum'      => ['32A', 'sum'],
		'date'     => ['32A', 'date'],
	],
	'scheme'   => [
		[
			'name'   => '20',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Sender\'s reference',
			'mask'   => '16x',
			'number' => '1',
			'scheme' => [
				[
					'label' => 'Reference',
				],
			],
		],
		[
			'type'         => 'collection',
			'name'         => '13C',
			'disableLabel' => true,
			'scheme'       => [
				[
					'name'   => '13C',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Time indication',
					'mask'   => '/8c~/4!n~1!x~4!n',
					'number' => '2',
					'scheme' => [
						[
							'label' => 'Code',
						],
						[
							'label'  => 'Time indication',
						],
						[
							'label' => 'Sign',
						],
						[
							'label' => 'Time offset',
						],
					],
				],
			],
		],
		[
			'name'   => '23B',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Bank Operation Code',
			'mask'   => '4!c',
			'number' => '3',
			'scheme' => [
				[
					'label' => 'Type',
					'strict' => ['CRED', 'CRTS', 'SPAY', 'SPRI', 'SSTD']
				],
			],
		],
		[
			'type'         => 'collection',
			'name'         => '23E',
			'disableLabel' => true,
			'scheme'       => [
				[
					'name'   => '23E',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Instruction Code',
					'mask'   => '4!c[/30x]',
					'number' => '4',
					'scheme' => [
						[
							'label' => 'Instruction',
							'strict' => [
								'SDVA', 'INTC', 'REPA', 'CORT', 'HOLD', 'CHQB', 'PHOB', 'TELB', 'PHON', 'TELE', 'PHOI',
								'TELI'
							]
						],
						[
							'label' => 'Additional Information',
						],
					],
				],
			],
		],
		[
			'name'   => '26T',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Transaction Type Code',
			'mask'   => '3!c',
			'number' => '5',
		],
		[
			'name'   => '32A',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Value Date / Currency / Interbank Settled Amount',
			'mask'   => '6!n~3!a~15d',
			'number' => '6',
			'scheme' => [
				[
					'name'  => 'date',
					'label' => 'Date',
				],
				[
					'name'   => 'currency',
					'label'  => 'Currency',
					'strict' => $currency
				],
				[
					'name'  => 'sum',
					'label' => 'Sum',
				],
			],
            'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper32A',
		],
		[
			'name'   => '33B',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Currency/Instructed Amount',
			'mask'   => '3!a~15d',
			'number' => '7',
			'scheme' => [
				[
					'name'   => 'currency',
					'label'  => 'Currency',
					'strict' => $currency
				],
				[
					'name'  => 'sum',
					'label' => 'Sum',
				],
			],
            'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper33B',
		],
		[
			'name'   => '36',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Exchange Rate',
			'mask'   => '12d',
			'number' => '8',
		],
		[
			'name'   => '50a',
			'type'   => 'choice',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Ordering Customer',
			'scheme' => getChoiceScheme('50a', ['A', 'F', 'K']),
			'number' => '9',
		],
		[
			'name'   => '51A',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Sending Institution',
			'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4!a2!a2!c[3!c]",
			'number' => '10',
            'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper5xA',
			'scheme' => [
				[
					'label' => 'Option Party Identifier',
				],
				[
					'label' => 'Party Identifier',
				],
				[
					'label' => 'Identifier Code',
                    'type' => 'select2',
                    'dataUrl' => Url::toRoute(['/swiftfin/dict-bank/list'])
				],
			]
		],
		[
			'name'   => '52a',
			'type'   => 'choice',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Ordering Institution',
			'scheme' => getChoiceScheme('52a', ['A', 'D']),
			'number' => '11',
		],
		[
			'name'   => '53a',
			'type'   => 'choice',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Sender\'s Correspondent',
			'scheme' => getChoiceScheme('53a', ['A', 'B', 'D']),
			'number' => '12',
		],
		[
			'name'   => '54a',
			'type'   => 'choice',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Receiver\'s Correspondent',
			'scheme' => getChoiceScheme('54a', ['A', 'B', 'D']),
			'number' => '13',
		],
		[
			'name'   => '55a',
			'type'   => 'choice',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Third Reimbursement Institution',
			'scheme' => getChoiceScheme('55a', ['A', 'B', 'D']),
			'number' => '14',
		],
		[
			'name'   => '56a',
			'type'   => 'choice',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Intermediary Institution',
			'scheme' => getChoiceScheme('56a', ['A', 'C', 'D']),
			'number' => '15',
		],
		[
			'name'   => '57a',
			'type'   => 'choice',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Account With Institution',
			'scheme' => getChoiceScheme('57a', ['A', 'B', 'C', 'D']),
			'number' => '16',
		],
		[
			'name'   => '59a',
			'type'   => 'choice',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Beneficiary Customer',
			'scheme' => getChoiceScheme('59a', ['', 'A']),
			'number' => '17',
		],
		[
			'name'   => '70',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Remittance Information',
			'mask'   => '4*35x',
			'number' => '18',
		],
		[
			'name'   => '71A',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Details of Charges',
			'mask'   => '3!a',
			'number' => '19',
			'scheme' => [
				[
					'label'  => 'Code',
					'strict' => ['BEN', 'OUR', 'SHA']
				],
			],
		],
		[
			'type'         => 'collection',
			'name'         => '71F',
			'disableLabel' => true,
			'scheme'       => [
				[
					'name'   => '71F',
					'status' => Entity::STATUS_OPTIONAL,
					'label'  => 'Sender\'s Charges',
                    'mask'   => '3!a~15d',
					'number' => '19',
                	'scheme' => [
                        [
                            'name'   => 'currency',
                            'label'  => 'Currency',
                            'strict' => $currency
                        ],
                        [
                            'name'  => 'sum',
                            'label' => 'Sum',
                        ],
                    ],
                    'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper71F',
				],
                
			],
		],
        [
            'type'         => 'collection',
            'name'         => '71G',
            'disableLabel' => true,
            'scheme'       => [
                [
                    'name'   => '71G',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label'  => 'Receiver\'s Charges',
                    'mask'   => '3!a~15d',
                    'number' => '20',
                    'scheme' => [
                        [
                            'name'   => 'currency',
                            'label'  => 'Currency',
                            'strict' => $currency
                        ],
                        [
                            'name'  => 'sum',
                            'label' => 'Sum',
                        ],
                    ],
                    'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper71F',
                ],

            ],
        ],
		[
			'name'   => '72',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Sender to Receiver Information',
			'mask'   => '6*35x',
			'number' => '21',
		],
		[
			'name'   => '77B',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Regulatory Reporting',
			'mask'   => '3*35x',
			'number' => '22',
		],
	],
	'networkRules' => [
		'D75' => [
			'message' => 'Если в сообщении присутствует поле 33В и если указанный в нем код валюты отличен от кода валюты в поле 32А, в сообщении должно присутствовать поле 36; в остальных случаях поле 36 не используется',
		],
		'D49' => [
			'message' => 'Если коды стран в кодах BIC Отправителя и Получателя входят в следующий перечень кодов стран: AD, AT, BE, BV, BG, CH, CY, CZ, DE, DK, EE, ES, FI, FR, GB, GF, GI, GP, GR, HU, IE, IS, IT, LI, LT, LU, LV, MC, MQ, MT, NL, NO, PL, PM, PT, RE, RO, SE, SI, SJ, SK, SM, TF и VA, - то поле 33В является обязательным, в остальных случаях поле 33В необязательное',
		],
		'E01' => [
			'message' => 'Если поле 23В содержит код SPRI, то в поле 23Е могут использоваться только коды SDVA, TELB, PHOB, INTC',
		],
		'E02' => [
			'message' => 'Если поле 23В содержит код SSTD или SPAY, то поле 23Е не должно использоваться',
		],
		'E03' => [
			'message' => 'Если поле 23В содержит один из кодов SPRI, SSTD или SPAY, то поле 53a не должно использоваться с опцией D',
		],
		'E04' => [
			'message' => 'Если поле 23В содержит один из кодов SPRI, SSTD или SPAY, а поле 53a используется с опцией В , то в поле 53В должно присутствовать подполе «Идентификация стороны»',
		],
		'E05' => [
			'message' => 'Если поле 23В содержит один из кодов SPRI, SSTD или SPAY, то поле 54a может использоваться только с опцией А',
		],
		'E06' => [
			'message' => 'Если в сообщении присутствует поле 55а, то должны также присутствовать как поле 53а, так и поле 54а',
		],
		'E07' => [
			'message' => 'Если поле 23В содержит один из кодов SPRI, SSTD или SPAY, то поле 55a может использоваться только с опцией А',
		],
		'C81' => [
			'message' => 'Если в сообщении присутствует поле 56а, то должно присутствовать также и поле 57а',
		],
		'E16' => [
			'message' => 'Если поле 23В содержит код SPRI, то поле 56а не должно использоваться',
		],
		'E17' => [
			'message' => 'Если поле 23В содержит один из кодов SSTD или SPAY, то поле 56a может использоваться либо с опцией А, либо с опцией С. Если используется опция С, то в этом поле должен быть указан клиринговый код',
		],
		'E09' => [
			'message' => 'Если поле 23В содержит код SPRI, SSTD или SPAY, то поле 57a может использоваться с опцией А, с опцией С или с опцией D. При использовании опции D в поле 57a должно присутствовать подполе 1 «Идентификация стороны»',
		],
		'E10' => [
			'message' => 'Если поле 23В содержит один из кодов SPRI, SSTD или SPAY, то в поле 59а «Клиент-бенефициар» обязательно должно присутствовать подполе 1 «Счет»',
		],
		'E18' => [
			'message' => 'Если какое-либо из полей 23Е содержит код CHQB, подполе 1 «Счет» в поле 59а «Клиент-бенефициар» не используется',
		],
		'E12' => [
			'message' => 'Поля 70 и 77Т являются взаимоисключающими',
		],
		'E13' => [
			'message' => 'Если поле 71А содержит код «OUR», то поле 71F не должно использоваться, а поле 71G необязательное',
		],
		'D50' => [
			'message' => 'Если поле 71А содержит код «SHA», то поле (поля) 71F необязательное, а поле 71G не должно использоваться',
		],
		'E15' => [
			'message' => 'Если поле 71А содержит код «BEN», то обязательно должно присутствовать хотя бы одно поле 71F, а поле 71G не используется',
		],
		'D51' => [
			'message' => 'Если в сообщении присутствует либо поле 71F (хотя бы один раз), либо поле 71G, то поле 33В является обязательным, в остальных случаях поле 33В необязательное',
		],
		'E44' => [
			'message' => 'Если поле 56а отсутствует, ни одно из полей 23Е не должно содержать коды TELI или PHOI',
		],
		'E45' => [
			'message' => 'Если поле 57а отсутствует, ни одно из полей 23Е не должно содержать коды TELE или PHON',
		],
		'C02' => [
			'message' => 'Код валюты в полях 71G и 32А должен быть одинаковым',
		],
	],
];
