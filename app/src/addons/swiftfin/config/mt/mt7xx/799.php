<?php
namespace addons\swiftfin\config\mt7xx;
include_once(__DIR__.'/choiceScheme.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '799',
	'formable' => true,
	'scheme'   => [
		[
			'name'   => '20',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Референс операции',
			'mask'   => '16x',
			'scheme' => [
				[
					'label' => 'Референс операции',
				]
			]
		],
		[
			'name'   => '21',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Related Reference',
			'mask'   => '16x',
			'scheme' => [
				[
					'label'  => 'Related Reference',
				]
			]
		],
		[
			'name'   => '79',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Свободный текст',
			'mask'   => '35*50x',
			'scheme' => [
				[
					'label' => 'Свободный текст',
				]
			]
		],
	],
];
