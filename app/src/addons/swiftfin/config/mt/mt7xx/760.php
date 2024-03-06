<?php
namespace addons\swiftfin\config\mt7xx;
include_once(__DIR__.'/choiceScheme.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '760',
	'formable' => true,
	'scheme'   => [
		[
			'name'   => '27',
			'mask'   => '1!n/1!n',
			'label'  => 'Порядковый номер',
			'status' => Entity::STATUS_MANDATORY,
			'number' => '1',
			'scheme' => [
				[
					'label' => 'Номер',
				],
				[
					'label' => 'Общее количество',
				],
			],
		],
		[
			'name'   => '20',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Референс операции',
			'mask'   => '16x',
			'number' => '2',
		],
		[
			'name'   => '23',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Дополнительное определение',
			'mask'   => '16x',
			'number' => '3'
		],
		[
			'name'   => '30',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Дата',
			'mask'   => '6!n',
			'number' => '4',
            'scheme' => [
                [
                    'label' => 'Дата',
                    'name' => 'date'
                ],
            ],
		],
		[
			'name'   => '40C',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Применяемые правила',
			'mask'   => '4a![/35x]',
			'number' => '5',
			'scheme' => [
				[
					'label'  => 'Тип',
					'strict' => ['ISPR', 'NONE', 'OTHR', 'URDG']
				],
				[
					'label' => 'Свободный текст',
				],
			],
		],
		[
			'name'   => '77C',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Детали гарантии',
			'mask'   => '150*65x',
			'number' => '6'
		],
		[
			'name'   => '72',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Sender to Receiver Information',
			'mask'   => '6*35x',
			'number' => '7'
		],
	],
];
