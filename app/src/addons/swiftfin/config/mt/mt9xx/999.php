<?php
namespace addons\swiftfin\config\mt9xx;
include_once(__DIR__.'/choiceScheme.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '999',
	'formable' => true,
	'scheme'   => [
		[
			'name'   => '20',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Референс операции',
			'mask'   => '16x',
		],
		[
			'name'   => '21',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Related Reference',
			'mask'   => '16x',
		],
		[
			'name'   => '79',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Свободный текст',
			'field'  => 'textarea',
			'mask'   => '35*50x',
            'scheme' => [
                [
                    'label' => 'Свободный текст',
                    'name' => 'value'
                ],
            ],
		],
	],
];		
