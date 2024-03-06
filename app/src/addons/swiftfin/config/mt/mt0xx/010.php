<?php
namespace addons\swiftfin\config\mt0xx;
include_once(__DIR__ . '/choiceScheme.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

/**
 * Только схема документа
 */
return [
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
		'name'   => '431',
		'status' => Entity::STATUS_MANDATORY,
		'label'  => 'Message status',
	],
	[
		'name'   => '102',
		'status' => Entity::STATUS_MANDATORY,
		'label'  => 'SWIFT address',
	],
	[
		'name'   => '104',
		'status' => Entity::STATUS_MANDATORY,
		'label'  => 'Message priority',
	],
];
