<?php
namespace addons\swiftfin\config\mt9xx;

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;
use yii\helpers\Url;

include(__DIR__.'/../base/currency.php');

function getChoiceScheme($tag, $keys)
{
	$tagScheme = [
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
					]
				]
			],
			'F' => [
				'name'   => 'F',
				'mask'   => "35x".Entity::INLINE_BREAK."4*35x",
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
				'mask'   => "[/34x]".Entity::INLINE_BREAK."4*35x",
                'transliterable' => [false, true],
				'scheme' => [
					[
						'label' => 'Счет',
                        'name' => 'account'
					],
					[
						'label' => 'Наименование и адрес',
					]
				]
			]
		],
		'52a' => [
			'A' => [
				'name'   => 'A',
				'mask'	 => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4!a2!a2!c[3!c]",
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
					]
				]
			],
			'D' => [
				'name'   => 'D',
				'mask'	 => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4*35x",
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
					]
				]
			]
		],
		'56a' => [
			'A' => [
				'name'   => 'A',
				'mask'	 => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4!a2!a2!c[3!c]",
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
					]
				]
			],
			'D' => [
				'name'   => 'D',
				'mask'	 => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4*35x",
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
					]
				]
			]
		],
		'60a' => [
			'F' => [
				'name'   => 'F',
				'mask'   => "1!a~6!n~3!a~15d",
				'scheme' => [
					[
						'label' => 'Знак дебета/кредита',
					],
					[
						'label' => 'Дата',
                        'name' => 'date',
					],
					[
						'label' => 'Валюта',
                        'name' => 'currency',
						'strict' => \common\helpers\Currencies::getCodeLabels()
					],
					[
						'label' => 'Сумма',
                        'name' => 'sum',
					],
				]
			],
			'M' => [
				'name'   => 'M',
				'mask'   => "1!a~6!n~3!a~15d",
				'scheme' => [
					[
						'label' => 'Знак дебета/кредита',
					],
					[
						'label' => 'Дата',
                        'name' => 'date',
					],
					[
						'label' => 'Валюта',
                        'name' => 'currency',
						'strict' => \common\helpers\Currencies::getCodeLabels()
					],
					[
						'label' => 'Сумма',
                        'name' => 'sum',
					],
				]
			]
		],
		'62a' => [
			'F' => [
				'name'   => 'F',
				'mask'   => "1!a~6!n~3!a~15d",
				'scheme' => [
					[
						'label'  => 'Знак дебета/кредита',
						'strict' => ['C', 'D'],
					],
					[
						'label' => 'Дата',
                        'name' => 'date',
					],
					[
						'label' => 'Валюта',
                        'name' => 'currency',
                        'strict' => \common\helpers\Currencies::getCodeLabels()
					],
					[
						'label' => 'Сумма',
                        'name' => 'sum',
					],
				]
			],
			'M' => [
				'name'   => 'M',
				'mask'   => "1!a~6!n~3!a~15d",
				'scheme' => [
					[
						'label'  => 'Знак дебета/кредита',
						'strict' => ['C', 'D'],
					],
					[
						'label' => 'Дата',
                        'name' => 'date',
					],
					[
						'label' => 'Валюта',
                        'name' => 'currency',
                        'strict' => \common\helpers\Currencies::getCodeLabels()
					],
					[
						'label' => 'Сумма',
                        'name' => 'sum',
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