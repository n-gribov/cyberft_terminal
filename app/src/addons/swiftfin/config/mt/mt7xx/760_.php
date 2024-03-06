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
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Sequence of Total',
		],
		[
			'name'   => '20',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Transaction Reference Number',
		],
		[
			'name'   => '23',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Further Identification',
		],
		[
			'name'   => '30',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Date',
		],
		[
			'name'   => '40C',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Applicable Rules',
		],
		[
			'name'   => '77C',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Details of Guarantee',
		],
		[
			'name'   => '72',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Sender to Receiver Information',
		],
	],
];
