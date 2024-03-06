<?php
namespace addons\swiftfin\config\mt2xx;
include_once(__DIR__. '/choiceScheme.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '200',
	'formable' => true,
	'aliases'  => [
		'currency' => ['32A', 'currency'],
		'sum'      => ['32A', 'sum'],
		'date'     => ['32A', 'date'],
	],
	'scheme'   => [
		[
			'name'   => '20',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Референс операции',
			'mask'   => '16x',
			'number' => '1',
		],
		[
			'name'   => '32A',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Дата валютирования, код валюты, сумма',
			'mask'   => '6!n~3!a~15d ',
			'number' => '2',
			'scheme' => [
				[
					'label' => 'Дата',
					'name' => 'date'
				],
				[
					'label' => 'Валюта',
					'name' => 'currency',
					'strict' => \common\helpers\Currencies::getCodeLabels()
				],
				[
					'label' => 'Сумма',
					'name' => 'sum'
				],
			],
			'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper32A',
		],
		[
			'name'   => '53B',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Корреспондент Отправителя',
			'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4!a2!a2!c[3!c]",
			'number' => '3',
			'scheme' => [
				[
					'label' => 'Идентификация стороны',
					'name' => 'identityPart'
				],
				[
					'label' => 'Идентификационный код',
				],
			],
		],
		[
			'name'   => '56a',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Посредник',
			'type'   => 'choice',
			'number' => '4',
			'scheme' => getChoiceScheme('56a', ['A', 'D']),
		],
		[
			'name'   => '57a',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Банк бенефициара',
			'type'   => 'choice',
			'number' => '5',
			'scheme' => getChoiceScheme('57a', ['A', 'B', 'D']),
		],
		[
			'name'   => '72',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Информация Отправителя Получателю',
			'mask'   => '6*35x',
			'number' => '6',
			'scheme' => [
				[
					'label' => 'Свободный текст - Структурированный формат'
				],
			],
		],
	],
];