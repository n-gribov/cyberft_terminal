<?php
namespace addons\swiftfin\config\mt2xx;

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;
use yii\helpers\Url;

function getChoiceScheme($tag, $keys)
{
	$tagScheme = [
		'32a' => [
			'C' => [
				'name'   => 'C',
				'mask'   => "6!n~3!a~15d",
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
			], 
			'D' => [
				'name'   => 'D',
				'mask'   => "6!n~3!a~15d",
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
			// @todo еще один уровень вложенности работы поля, подумать после ввода составных полей
			'F' => [
				'name'   => 'F',
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

			'K' => [
				'name'   => 'K',
				'mask'	 => "[/34x]".Entity::INLINE_BREAK."4*35x",
                'transliterable' => [false, true],
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
			
			'C' => [
				'name'   => 'C',
				'mask'   => "4!a2!a2!c[3!c]",
                'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper5xA',
				'scheme' => [
					[
						'label' => 'Идентификационный код',
					],
				]
			],
			'F' => [
				'name'   => 'F',
				'mask'   => "35x".Entity::INLINE_BREAK."4*35x",
                'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper5xA',
				'scheme' => [
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
                        'dataUrl' => Url::toRoute(['/swiftfin/dict-bank/list']),
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

		'58a' => [
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