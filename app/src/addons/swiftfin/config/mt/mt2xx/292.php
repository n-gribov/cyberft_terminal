<?php
namespace addons\swiftfin\config\mt2xx;
include_once(__DIR__ . '/choiceScheme.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;
use \Yii;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '292',
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
			'name'   => '11S',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Тип и дата исходного сообщения',
			'mask'   => '3!n~' . Entity::INLINE_BREAK. '6!n~' . Entity::INLINE_BREAK . '[4!n]~[6!n]',
			'number' => '3',
			'scheme' => [
				[
					'label' => 'Тип сообщения'
				],
				[
					'label' => 'Дата',
                    'name' => 'date'
				],
				[
					'label' => 'Номер сессии'
				],
				[
					'label' => 'ISN'
				],
			],
		],
		[
			'name'   => '79',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Описание исходного сообщения в свободном формате',
			'mask'   => '35*50x',
			'number' => '4',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],
	],
];