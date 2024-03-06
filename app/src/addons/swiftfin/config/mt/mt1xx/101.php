<?php
namespace addons\swiftfin\config\mt1xx;
include_once(__DIR__.'/choiceScheme.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;
use Yii;
use yii\helpers\Url;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'formable' => true,
	'type'     => '101',
	'scheme'   => [
		[
			'name'   => '20',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Sender\'s reference',
			'mask'   => '16x',
			'number' => '1',
			'scheme' => [
				[
					'label' => 'Reference',
				],
			],
		],
		[
			'name'   => '21R',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Референс клиента',
			'mask'   => '16x',
			'number' => '2',
			'scheme' => [
				[
					'label' => 'Reference',
				],
			],
		],
		[
			'name'   => '28D',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Номер сообщения/Общее число сообщений',
			'mask'   => '5n~/5n',
			'number' => '3',
			'scheme' => [
				[
					'label' => 'Номер сообщения'
				],
				[
					'label' => 'Общее число'
				],
			],
		],
		[
			'name'   => '50a',
			'type'   => 'choice',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Ordering Customer',
			'scheme' => getChoiceScheme('50a', ['C', 'L', 'F', 'G', 'H']),
			'number' => '4',
		],
		[
			'name'   => '52a',
			'type'   => 'choice',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Обслуживающая счет организация',
			'scheme' => getChoiceScheme('52a', ['A', 'C']),
			'number' => '6',
		],
        [
            'name'   => '51A',
            'status' => Entity::STATUS_OPTIONAL,
            'label'  => 'Sending Institution',
            'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4!a2!a2!c[3!c]",
            'number' => '10',
            'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper5xA',
            'scheme' => [
                [
                    'label' => 'Option Party Identifier',
                ],
                [
                    'label' => 'Party Identifier',
                    'name' => 'identityCode',
                ],
                [
                    'label' => 'Identifier Code',
                    'type' => 'select2',
                    'dataUrl' => Url::toRoute(['/swiftfin/dict-bank/list'])
                ],
            ]
        ],
		[
			'name'   => '30',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Запрашиваемая дата исполнения',
			'mask'   => '6!n',
			'number' => '8',
			'scheme' => [
				[
					'label' => 'Дата',
                    'name' => 'date'
				],
			],
		],
		[
			'name'   => '25',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Разрешение на выполнение операции',
			'mask'   => '35x',
			'number' => '9',
			'scheme' => [
			],
		],

		[
			'type'   => 'collection',
			'name'   => '21',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Референс операции',
			'scheme' => [
			],		
		]
	],
];
