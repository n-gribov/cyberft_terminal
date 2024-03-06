<?php
namespace addons\swiftfin\config\mt2xx;
include_once(__DIR__ . '/choiceScheme.php');
include(__DIR__ . '/../base/currency.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'formable' => true,
	'type'     => '202',
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
			'name'   => '21',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Related Reference',
			'mask'   => '16x',
			'number' => '2',
			'scheme' => [
				[
					'label' => 'Reference',
				],
			],
		],
		[
			'type'         => 'collection',
			'name'         => '13C',
			'status'       => Entity::STATUS_OPTIONAL,
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
							'label' => 'Код',
							'strict' => ['CLSTIME','RNCTIME','SNDTIME']
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
			'name'   => '32A',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Value Date/Currency/Interbank Settled Amount',
			'mask'   => '6!n~3!a~15d',
			'number' => '4',
            'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper32A',
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
		],
		[
			'name'   => '52a',
			'type'   => 'choice',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Ordering Institution',
			'scheme' => getChoiceScheme('52a', ['A', 'D']),
			'number' => '5',
		],
		[
			'name'   => '53a',
			'type'   => 'choice',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Sender\'s Correspondent',
			'scheme' => getChoiceScheme('53a', ['A', 'B', 'D']),
			'number' => '6',
		],
		[
			'name'   => '54a',
			'type'   => 'choice',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Receiver\'s Correspondent',
			'scheme' => getChoiceScheme('54a', ['A', 'B', 'D']),
			'number' => '7',
		],
		[
			'name'   => '56a',
			'type'   => 'choice',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Intermediary Institution',
			'scheme' => getChoiceScheme('56a', ['A', 'D']),
			'number' => '8',
		],
		[
			'name'   => '57a',
			'type'   => 'choice',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Account With Institution',
			'scheme' => getChoiceScheme('57a', ['A', 'B', 'D']),
			'number' => '9',
		],
		[
			'name'   => '58a',
			'type'   => 'choice',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Beneficiary Institution',
			'scheme' => getChoiceScheme('58a', ['A', 'D']),
			'number' => '10',
		],
		[
			'name'   => '72',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Sender to Receiver Information',
			'mask'   => '6*35x',
			'number' => '11',
            'scheme' => [
                [
                    'label' => 'Sender to Receiver Information',
                    'name' => 'value'
                ],
            ],
		],
	],
	'networkRules' => [
		'C81' => [
			'message' => 'Если в сообщении присутствует поле 56а, то должно присутствовать также и поле 57а',
		],
	]
];