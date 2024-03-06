<?php
namespace addons\swiftfin\config\mt0xx;
include_once(__DIR__ . '/choiceScheme.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

/**
 * Только схема документа
 */
return [
	[
		'name'   => '175',
		'status' => Entity::STATUS_MANDATORY,
		'label'  => 'Sender-local input time of the delivered message',
	],
	[
		'name'   => '106',
		'status' => Entity::STATUS_MANDATORY,
		'label'  => 'Message input reference',
	],
	[
		'name'   => '108',
		'status' => Entity::STATUS_OPTIONAL,
		/**
		 * @todo check if choice is needed here
		 */
		'label'  => 'Message user reference',
	],
	[
		'type'   => 'sequence',
		'name'   => 'A',
		'status' => Entity::STATUS_MANDATORY,
		'scheme' => [
			[
				'name'   => '175',
				'status' => Entity::STATUS_MANDATORY,
				'label'  => 'Receiver-local output time or the delivered message',
			],
		],
	],
	[
		'name'   => '107',
		'status' => Entity::STATUS_MANDATORY,
		'label'  => 'Message output reference',
	],
];
