<?php
namespace addons\swiftfin\config\mt9xx;
include_once(__DIR__.'/choiceScheme.php');
include(__DIR__.'/../base/currency.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '900',
	'formable' => true,
	'aliases' => [
		'currency' => ['32A', 'currency'],
		'sum'      => ['32A', 'sum'],
	],
	'scheme'   => [
		[
			'name'   => '20',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Референс операции',
			'mask'   => "16x",
			'number' => '1',
		],
		[
			'name'   => '21',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Related Reference',
			'mask'   => "16x",
			'number' => '2',
		],
		[
			'name'   => '25',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Номер счета',
			'mask'   => "35x",
			'number' => '3',
		],
		[
			'name'   => '32A',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Дата валютирования, код валюты, сумма',
			'mask'   => "6!n~3!a~15d",
			'number' => '4',
            'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper32A',
			'scheme' => [
				[
					'name'  => 'date',
					'label' => 'Дата',
				],
				[
					'name'   => 'currency',
					'label'  => 'Валюта',
					'strict' => $currency
				],
				[
					'name'  => 'sum',
					'label' => 'Сумма',
				],
			],
		],
		[
			'type'   => 'choice',
			'name'   => '52a',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Банк-Плательщик',
			'scheme' => getChoiceScheme('52a', ['A', 'D']),
			'number' => '5',
		],
		[
			'name'   => '72',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Sender to Receiver Information',
			'mask'   => "6*35x",
			'number' => '6',
		],
	],
];		
