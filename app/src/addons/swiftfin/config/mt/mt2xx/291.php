<?php
namespace addons\swiftfin\config\mt2xx;
include_once(__DIR__ . '/choiceScheme.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;
use \Yii;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '291',
	'formable' => true,
	'scheme'   => [
		[
			'name'   => '20',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Референс операции',
			'mask'   => '16x',
			'number' => '1',
		],
		[
			'name'   => '21',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Связанный референс',
			'mask'   => '16x',
			'number' => '2',
		],
		[
			'name'   => '32B',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Код валюты, сумма',
			'mask'   => '3!a~15d',
			'number' => '3',
			'scheme' => [
				[
					'label' => 'Валюта'
				],
				[
					'label' => 'Сумма'
				],
			],
		],
		[
			'name'   => '52a',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Банк бенефициара',
			'type'   => 'choice',
			'number' => '4',
			'scheme' => getChoiceScheme('52a', ['A', 'D']),
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
			'name'   => '71B',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Детали расходов',
			'mask'   => '6*35x',
			'number' => '6',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],
		[
			'name'   => '72',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Информация Отправителя Получателю',
			'mask'   => '6*35x',
			'number' => '7',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],
	],
];