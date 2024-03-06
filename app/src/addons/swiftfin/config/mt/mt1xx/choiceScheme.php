<?php
namespace addons\swiftfin\config\mt1xx;

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;
use yii\helpers\Url;

function getChoiceScheme($tag, $keys)
{
	$tagScheme = [
		'11a' => [
			'R' => [
				'name'   => 'R',
				'mask'   => "3!n~".Entity::INLINE_BREAK."6!n~".Entity::INLINE_BREAK."[4!n6!n]",
				'scheme' => [
					[
						'label' => 'Тип сообщения',
					],
					[
						'label' => 'Дата',
                        'name' => 'date'
					],
					[
						'label' => 'Номер сессии и ISN',
					],
				],
			],
		'S' => [
				'name'   => 'S',
				'mask'   => "3!n~".Entity::INLINE_BREAK."6!n~".Entity::INLINE_BREAK."[4!n6!n]",
				'scheme' => [
					[
						'label' => 'Тип сообщения',
					],
					[
						'label' => 'Дата',
                        'name' => 'date'
					],
					[
						'label' => 'Номер сессии и ISN',
					],
				],
			]
		],
		'32a' => [
			'A' => [
				'name'   => 'A',
				'mask'   => "6!n~3!a~15d",
				'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper32A',
				'scheme' => [
					[
						'name'  => 'date',
						'label' => 'Date',
					],
					[
						'name'   => 'currency',
						'label'  => 'Currency',
                        'strict' => \common\helpers\Currencies::getCodeLabels()
					],
					[
						'name'  => 'sum',
						'label' => 'Sum',
					],
				],
			], 
			'B' => [
				'name'   => 'B',
				'mask'   => "3!a~15d",
				'scheme' => [
					[
						'name'   => 'currency',
						'label'  => 'Currency',
                        'strict' => \common\helpers\Currencies::getCodeLabels()
					],
					[
						'name'  => 'sum',
						'label' => 'Sum',
					],
				],
			], 
		],
		'50a' => [
			'A' => [
				'name'   => 'A',
				'mask'   => "[/34x]~".Entity::INLINE_BREAK."4!a2!a2!c[3!c]",
                'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper5xA',
				'scheme' => [
					[
						'label' => 'Счет',
                        'name' => 'account'
					],
					[
						'label' => 'Идентификационный код',
                        'type' => 'select2',
                        'dataUrl' => Url::toRoute(['/swiftfin/dict-bank/list'])
					],
				]
			],
			'C' => [
				'name'   => 'C',
				'label'  => 'Приказодатель',
				'mask'   => "4!a2!a2!c[3!c]",
                'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper5xA',
				'scheme' => [
					[
						'label' => 'Идентификационный код',
                        'type' => 'select2',
                        'dataUrl' => Url::toRoute(['/swiftfin/dict-bank/list'])
					],
				]
			],
			// @todo еще один уровень влоенности работы поля, подумать после ввода составных полей
            'F' => [
                'name'   => 'F',
                'label'  => 'Клиент-заказчик',
                'mask'	 => "35x".Entity::INLINE_BREAK."4*35x",
                'scheme' => [
                    [
                        'label' => 'Идентификация стороны',
                        'name' => 'identityPart'
                    ],
                    [
                        'label' => 'Наименование и адрес',
                    ]
                ]
            ],
			'G' => [
				'name'   => 'G',
				'label'  => 'Клиент-заказчик',
				'mask'	 => "/34x".Entity::INLINE_BREAK."4!a2!a2!c[3!c]",
				'scheme' => [
					[
						'label' => 'Счет',
                        'name' => 'account'
					],
					[
						'label' => 'Идентификационный код',
					]
				]
			],
			'H' => [
				'name'   => 'H',
				'label'  => 'Клиент-заказчик',
				'mask'	 => "/34x".Entity::INLINE_BREAK."4*35x",
				'scheme' => [
					[
						'label' => 'Счет',
                        'name' => 'account'
					],
					[
						'label' => 'Наименование и адрес',
					]
				]
			],
			'K' => [
				'name'   => 'K',
				'mask'	 => "[/34x]".Entity::INLINE_BREAK."4*35x",
                'transliterable' => [false, true, true, true],
				'scheme' => [
					[
						'label' => 'Счет',
                        'name' => 'account'
					],
					[
						'label' => 'Наименование и адрес',
					],
				]
			],
			'L' => [
				'name'   => 'L',
				'mask'   => "35x",
				'label'  => 'Приказодатель',
                'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper5xA',
				'scheme' => [
					[
						'label' => 'Идентификация стороны',
                        'name' => 'identityPart'
					],
				]
			],
		],
		'52a' => [
			'A' => [
				'name'   => 'A',
				'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4!a2!a2!c[3!c]",
                'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper5xA',
				'scheme' => [
					[
						'label' => 'Опция идентификации стороны',
					],
					[
						'label' => 'Идентификация стороны',
                        'name' => 'identityPart'
					],
					[
						'label' => 'Идентификационный код',
                        'type' => 'select2',
                        'dataUrl' => Url::toRoute(['/swiftfin/dict-bank/list'])
					],
				]
			],
			'C' => [
				'name'   => 'C',
				'mask'   => "/34x",
                'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper5xA',
				'scheme' => [
					[
						'label' => 'Идентификация стороны',
						'strict' => [
							'AT', 'AU', 'BL', 'CC', 'CH', 'CP', 'ES', 'FW', 'GR', 'HK', 'IE', 'IN', 'IT', 'PL', 'PT', 'RU', 'SC', 'SW', 'SW',
						]
					],
				]
			],
			'D' => [
				'name'   => 'D',
				'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4*35x",
                'transliterable' => [false, false, false, true],
				'scheme' => [
					[
						'label' => 'Опция идентификации стороны',
					],
					[
						'label' => 'Идентификация стороны',
                        'name' => 'identityPart'
					],
					[
						'label' => 'Наименование и адрес',
					],
				]
			],
		],
		'53a' => [
			'A' => [
				'name'   => 'A',
				'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4!a2!a2!c[3!c]",
                'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper5xA',
				'scheme' => [
					[
						'label' => 'Опция идентификации стороны',
					],
					[
						'label' => 'Идентификация стороны',
                        'name' => 'identityPart'
					],
					[
						'label' => 'Идентификационный код',
                        'type' => 'select2',
                        'dataUrl' => Url::toRoute(['/swiftfin/dict-bank/list'])
					],
				]
			],
			'B' => [
				'name'   => 'B',
				'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."[35x]",
				'scheme' => [
					[
						'label' => 'Опция идентификации стороны',
                        'name' => 'identityOption'
					],
					[
						'label' => 'Идентификация стороны',
                        'name' => 'identityPart'
					],
					[
						'label' => 'Местонахождение',
                        'name' => 'place'
					],
				]
			],
			'D' => [
				'name'   => 'D',
				'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4*35x",
				'scheme' => [
					[
						'label' => 'Опция идентификации стороны',
                        'name' => 'identityOption'
					],
					[
						'label' => 'Идентификация стороны',
                        'name' => 'identityPart'
					],
					[
						'label' => 'Наименование и адрес',
                        'name' => 'address'
					],
				]
			],
		],
		'54a' => [
			'A' => [
				'name'   => 'A',
				'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4!a2!a2!c[3!c]",
                'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper5xA',
				'scheme' => [
					[
						'label' => 'Опция идентификации стороны',
					],
					[
						'label' => 'Идентификация стороны',
                        'name' => 'identityPart'
					],
					[
						'label' => 'Идентификационный код',
                        'type' => 'select2',
                        'dataUrl' => Url::toRoute(['/swiftfin/dict-bank/list'])
					],
				]
			],
			'B' => [
				'name'   => 'B',
				'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."[35x]",
				'scheme' => [
					[
						'label' => 'Опция идентификации стороны',
					],
					[
						'label' => 'Идентификация стороны',
                        'name' => 'identityPart'
					],
					[
						'label' => 'Местонахождение',
					],
				]
			],
			'D' => [
				'name'   => 'D',
				'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4*35x",
				'scheme' => [
					[
						'label' => 'Опция идентификации стороны',
					],
					[
						'label' => 'Идентификация стороны',
                        'name' => 'identityPart'
					],
					[
						'label' => 'Наименование и адрес',
					],
				]
			],
		],
		'55a' => [
			'A' => [
				'name'   => 'A',
				'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4!a2!a2!c[3!c]",
                'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper5xA',
				'scheme' => [
					[
						'label' => 'Опция идентификации стороны',
					],
					[
						'label' => 'Идентификация стороны',
                        'name' => 'identityPart'
					],
					[
						'label' => 'Идентификационный код',
                        'type' => 'select2',
                        'dataUrl' => Url::toRoute(['/swiftfin/dict-bank/list'])
					],
				]
			],
			'B' => [
				'name'   => 'B',
				'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."[35x]",
				'scheme' => [
					[
						'label' => 'Опция идентификации стороны',
					],
					[
						'label' => 'Идентификация стороны',
                        'name' => 'identityPart'

					],
					[
						'label' => 'Местонахождение',
					],
				]
			],
			'D' => [
				'name'   => 'D',
				'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4*35x",
				'scheme' => [
					[
						'label' => 'Опция идентификации стороны',
					],
					[
						'label' => 'Идентификация стороны',
                        'name' => 'identityPart'
					],
					[
						'label' => 'Наименование и адрес',
					],
				]
			],
		],
		'56a' => [
			'A' => [
				'name'   => 'A',
				'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4!a2!a2!c[3!c]",
                'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper5xA',
				'scheme' => [
					[
						'label' => 'Опция идентификации стороны',
					],
					[
						'label' => 'Идентификация стороны',
                        'name' => 'identityPart'
					],
					[
						'label' => 'Идентификационный код',
                        'type' => 'select2',
                        'dataUrl' => Url::toRoute(['/swiftfin/dict-bank/list'])
					],
				]
			],
			'C' => [
				'name'   => 'C',
				'mask'   => "/34x",
				'scheme' => [
					[
						'label' => 'Идентификация стороны',
                        'name' => 'identityPart'
					],
				]
			],
			'D' => [
				'name'   => 'D',
				'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4*35x",
				'scheme' => [
					[
						'label' => 'Опция идентификации стороны',
					],
					[
						'label' => 'Идентификация стороны',
                        'name' => 'identityPart'
					],
					[
						'label' => 'Наименование и адрес',
					],
				]
			],
		],
		'57a' => [
			'A' => [
				'name'   => 'A',
				'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4!a2!a2!c[3!c]",
                'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper5xA',
				'scheme' => [
					[
						'label' => 'Опция идентификации стороны',
					],
					[
						'label' => 'Идентификация стороны',
                        'name' => 'identityPart'
					],
					[
						'label' => 'Идентификационный код',
                        'type' => 'select2',
                        'dataUrl' => Url::toRoute(['/swiftfin/dict-bank/list'])
					],
				]
			],
			'B' => [
				'name'   => 'B',
				'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."[35x]",
				'scheme' => [
					[
						'label' => 'Опция идентификации стороны',
					],
					[
						'label' => 'Идентификация стороны',
                        'name' => 'identityPart'
					],
					[
						'label' => 'Местонахождение',
					],
				]
			],
			'C' => [
				'name'   => 'C',
				'mask'   => "/34x",
				'scheme' => [
					[
						'label' => 'Идентификация стороны',
                        'name' => 'identityPart'
					],
				]
			],
			'D' => [
				'name'   => 'D',
				'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4*35x",
                'transliterable' => [false, false, false, true],
				'scheme' => [
					[
						'label' => 'Опция идентификации стороны',
					],
					[
						'label' => 'Идентификация стороны',
                        'name' => 'identityPart'
					],
					[
						'label' => 'Наименование и адрес',
					],
				]
			],
		],
		'59a' => [
			'' => [
				'name'   => '',
				'mask'   => "/34x".Entity::INLINE_BREAK."4*35x",
                'transliterable' => [false, true, true],
				'scheme' => [
					[
						'label' => 'Счет',
                        'name' => 'account'
					],
					[
						'label' => 'Наименование и адрес',
					],
				]
			],
			'A' => [
				'name'   => 'A',
				'mask'   => "[/34x]~".Entity::INLINE_BREAK."4!a2!a2!c[3!c]",
                'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper5xA',
				'scheme' => [
					[
						'label' => 'Счет',
                        'name' => 'account'
					],
					[
						'label' => 'Идентификационный код',
                        'type' => 'select2',
                        'dataUrl' => Url::toRoute(['/swiftfin/dict-bank/list'])
					],
				]
			],
		],
	];

	if (!isset($tagScheme[$tag])) {
		$scheme = [
			$keys[0] => [
				'name'   => $keys[0],
				'type'   => 'sequence',
				'scheme' => [
					[
						#'name'  => (int)$tag.$keys[0],
						'label' => 'Ввод',
						'field' => 'textarea'
					]
				]
			]
		];
		return $scheme;
	}

	return array_intersect_key($tagScheme[$tag], array_fill_keys($keys, null));
}