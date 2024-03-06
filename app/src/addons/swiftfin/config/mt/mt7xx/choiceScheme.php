<?php
namespace addons\swiftfin\config\mt7xx;

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

include(__DIR__.'/../base/currency.php');

function getChoiceScheme($tag, $keys)
{
	$tagScheme = [
		'42a' => [
			'A' => [
				'name'   => 'A',
				'mask'   => '[/1!a][/34x]~4!a2!a2!c[3!c]',
				'scheme' => [
					[
						'label' => 'Идентификация стороны',
                        'name' => 'identityPart'
					],
					[
						'label' => 'Идентификационный код',						
					],					
				]
			],			
			'D' => [
				'name'   => 'D',
				'mask'   => '[/1!a][/34x]~4*35x',
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
		'41a' => [
			'A' => [
				'name'   => 'A',
				'mask'   => '4!a2!a2!c[3!c]~14x',
				'scheme' => [
					[
						'label' => 'Идентификационный код',
					],
					[
						'label' => 'Код',
						'strict' => [
						'BY ACCEPTANCE', 'BY DEF PAYMENT', 'BY MIXED PYMT', 'BY NEGOTIATION', 'BY PAYMENT'
						]
					],					
				]
			],			
			'D' => [
				'name'   => 'D',
				'mask'   => '14*35x*~14x',
				'scheme' => [
					[
						'label' => 'Наименование и адрес',
					],
					[
						'label' => 'Код',
						'strict' => [
						'BY ACCEPTANCE', 'BY DEF PAYMENT', 'BY MIXED PYMT', 'BY NEGOTIATION', 'BY PAYMENT'
						]
					],					
				]
			],
		],
		'52a' => [
			'A' => [
				'name'   => 'A',				
				'mask'   => '[/1!a][/34x]~4!a2!a2!c[3!c]',
				'scheme' => [
					[
						'label' => 'Идентификация стороны',
                        'name' => 'identityPart'
					],
					[
						'label' => 'Идентификационный код',
					],					
				]
			],			
			'D' => [
				'name'   => 'D',
                'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4*35x",
//				'mask'   => '[/1!a][/34x]~4*35x',
                'transliterable' => [false, false, false, true],
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
		'57a' => [
			'A' => [
				'name'   => 'A',				
				'mask'   => '[/1!a][/34x]~4!a2!a2!c[3!c]',
				'scheme' => [
					[
						'label' => 'Идентификация стороны',
                        'name' => 'identityPart'
					],
					[
						'label' => 'Идентификационный код',
					],					
				]
			],
			'B' => [
				'name'   => 'B',
				'mask'   => '[/1!a][/34x]~[35x]',
				'scheme' => [
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
				'mask'   => '[/1!a][/34x]~4*35x',
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
		'22a' => [
			'F' => [
				'name'   => 'F',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Система кодировки',
					],
					[
						'label' => 'Признак',
					]
				]
			],
			'H' => [
				'name'   => 'H',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Признак',
					]
				]
			]
		],
		'69a' => [
			'A' => [
				'name'   => 'A',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Дата',
					],
					[
						'label' => 'Дата',
					],
				]
			],
			'B' => [
				'name'   => 'B',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Дата',
					],
					[
						'label' => 'Время',
					],
					[
						'label' => 'Дата',
					],
					[
						'label' => 'Время',
					],
				]
			],
			'C' => [
				'name'   => 'C',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Дата',
					],
					[
						'label' => 'Время',
					],
					[
						'label' => 'Дата',
					],
					[
						'label' => 'Время',
					],
				]
			],
			'D' => [
				'name'   => 'D',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Дата',
					],
					[
						'label' => 'Время',
					],
					[
						'label' => 'Код даты',
					],
				]
			],
			'E' => [
				'name'   => 'E',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Код даты',
					],
					[
						'label' => 'Дата',
					],
				]
			],
			'F' => [
				'name'   => 'F',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Код даты',
					],
					[
						'label' => 'Дата',
					],
					[
						'label' => 'Время',
					],
				]
			],
			'J' => [
				'name'   => 'J',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Код даты',
					],
				]
			],
		],
		'70a' => [
			'C' => [
				'name'      => 'C',
				'type'      => 'sequence',
				'separator' => null,
				'scheme'    => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Свободный текст',
						'field' => 'textarea',
					],
				]
			],
			'D' => [
				'name'   => 'D',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Свободный текст',
						'field' => 'textarea',
					],
				]
			],
			'E' => [
				'name'   => 'E',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Свободный текст',
						'field' => 'textarea',
					],
				]
			],
			'G' => [
				'name'   => 'G',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Свободный текст',
						'field' => 'textarea',
					],
				]
			],
		],
		'90a' => [
			'A' => [
				'name'   => 'A',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Код типа процентов',
					],
					[
						'label' => 'Цена',
					],
				]
			],
			'B' => [
				'name'   => 'B',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Код типа суммы',
					],
					[
						'label' => 'Код валюты',
//						'strict' => $currency,
					],
					[
						'label' => 'Цена',
					],
				]
			],
			'E' => [
				'name'   => 'E',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Код цены',
					],
				]
			],
			'F' => [
				'name'   => 'F',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Код типа суммы',
					],
					[
						'label' => 'Код валюты',
//						'strict' => $currency,
					],
					[
						'label' => 'Сумма',
					],
					[
						'label' => 'Код типа количества',
					],
					[
						'label' => 'Количество',
					],
				]
			],
			'J' => [
				'name'   => 'J',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Код типа суммы',
					],
					[
						'label' => 'Код валюты',
//						'strict' => $currency,
					],
					[
						'label' => 'Сумма',
					],
					[
						'label' => 'Код валюты',
//						'strict' => $currency,
					],
					[
						'label' => 'Сумма',
					],
				]
			],
			'K' => [
				'name'   => 'K',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Индексные пункты',
					],
				]
			],
		],
		'92a' => [
			'A' => [
				'name'   => 'A',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					], [
						'label' => 'Знак',
					], [
						'label' => 'Ставка',
					],
				]
			],
			'B' => [
				'name'   => 'B',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					], [
						'label' => 'Код первой валюты',
//						'strict' => $currency,
					], [
						'label' => 'Код второй валюты',
//						'strict' => $currency,
					], [
						'label' => 'Курс',
					],
				]
			],
			'C' => [
				'name'   => 'C',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					], [
						'label' => 'Система кодировки',
					], [
						'label' => 'Название ставки',
					],
				]
			],
			'D' => [
				'name'   => 'D',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					], [
						'label' => 'Количество',
					], [
						'label' => 'Количество',
					],
				]
			],
			'E' => [
				'name'   => 'E',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					], [
						'label' => 'Код типа ставки',
					], [
						'label' => 'Знак',
					], [
						'label' => 'Ставка',
					], [
						'label' => 'Статус ставки',
					],
				]
			],
			'F' => [
				'name'   => 'F',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					], [
						'label' => 'Код валюты',
//						'strict' => $currency,
					], [
						'label' => 'Сумма',
					],
				]
			],
			'J' => [
				'name'   => 'J',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					], [
						'label' => 'Система кодировки',
					], [
						'label' => 'Код типа ставки',
					], [
						'label' => 'Код валюты',
//						'strict' => $currency,
					], [
						'label' => 'Сумма',
					], [
						'label' => 'Статус ставки',
					],
				]
			],
			'K' => [
				'name'   => 'K',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					], [
						'label' => 'Код типа ставки',
					],
				]
			],
			'L' => [
				'name'   => 'L',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					], [
						'label' => 'Код первой валюты',
//						'strict' => $currency,
					], [
						'label' => 'Сумма',
					], [
						'label' => 'Код второй валюты',
//						'strict' => $currency,
					], [
						'label' => 'Сумма',
					],
				]
			],
			'M' => [
				'name'   => 'M',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					], [
						'label' => 'Код валюты',
//						'strict' => $currency,
					], [
						'label' => 'Сумма',
					], [
						'label' => 'Количество',
					],
				]
			],
			'N' => [
				'name'   => 'B',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					], [
						'label' => 'Количество',
					], [
						'label' => 'Код валюты',
//						'strict' => $currency,
					], [
						'label' => 'Сумма',
					],
				]
			],
		],
		'93a' => [
			'A' => [
				'name'   => 'A',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					], [
						'label' => 'Система кодировки',
					], [
						'label' => 'Код типа остатка на субсчете',
					],
				]
			],
			'B' => [
				'name'   => 'B',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					], [
						'label' => 'Система кодировки',
					], [
						'label' => 'Код типа количества',
					], [
						'label' => 'Знак',
					], [
						'label' => 'Остаток',
					],
				]
			],
			'C' => [
				'name'   => 'C',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					], [
						'label' => 'Код типа количества',
					], [
						'label' => 'Код типа остатка',
					], [
						'label' => 'Знак',
					], [
						'label' => 'Остаток',
					],
				]
			],
		],
		'94a' => [
			'B' => [
				'name'   => 'B',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					], [
						'label' => 'Система кодировки',
					], [
						'label' => 'Код места',
					], [
						'label' => 'Свободный текст',
					],
				]
			],
			'C' => [
				'name'   => 'C',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					], [
						'label' => 'Код страны',
					],
				]
			],
			'D' => [
				'name'   => 'D',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					], [
						'label' => 'Код страны',
					], [
						'label' => 'Место',
					],
				]
			],
			'F' => [
				'name'   => 'F',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					], [
						'label' => 'Код места',
					], [
						'label' => 'Идентификационный код',
					],
				]
			],
			'E' => [
				'name'   => 'E',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					], [
						'label' => 'Адрес',
						'field' => 'textarea'
					],
				]
			],
			'H' => [
				'name'   => 'H',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					], [
						'label' => 'Идентификационный код',
					],
				]
			],
		],
		'95a' => [
			'C' => [
				'name'   => 'C',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Код страны',
					]
				]
			],
			'P' => [
				'name'   => 'P',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Идентификационный код',
					]
				]
			],
			'Q' => [
				'name'   => 'Q',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Наименование и адрес',
						'field' => 'textarea',
					]
				]
			],
			'R' => [
				'name'   => 'R',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Система кодировки',
					],
					[
						'label' => 'Собственный код',
					]
				]
			],
			'S' => [
				'name'   => 'S',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Система кодировки',
					],
					[
						'label' => 'Тип идентификации',
					],
					[
						'label' => 'Код страны',
					],
					[
						'label' => 'Альтернативная идентификация',
					],
				]
			],
		],
		'97a' => [
			'A' => [
				'name'   => 'A',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Номер счета',
					]
				]
			],
			'B' => [
				'name'   => 'B',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Система кодировки',
					],
					[
						'label' => 'Код типа счета',
					],
					[
						'label' => 'Номер счета',
					],
				]
			],
			'C' => [
				'name'   => 'C',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Код типа счета',
					],
				]
			],
			'E' => [
				'name'   => 'E',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Международный номер банковского счета IBAN',
					],
				]
			],
		],
		'98a' => [
			'A' => [
				'name'   => 'A',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Дата',
					]
				]
			],
			'B' => [
				'name'   => 'B',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Система кодировки',
					],
					[
						'label' => 'Код даты',
					]
				]
			],
			'C' => [
				'name'   => 'C',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Дата',
					],
					[
						'label' => 'Время',
					]
				]
			],
			'E' => [
				'name'   => 'E',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Дата',
					],
					[
						'label' => 'Время',
					],
					[
						'label' => 'Десятые доли',
					],
					[
						'label' => 'Значение времени UTC',
					]
				]
			],
			'F' => [
				'name'   => 'F',
				'type'   => 'sequence',
				'scheme' => [
					[
						'label' => 'Определитель',
					],
					[
						'label' => 'Система кодировки',
					],
					[
						'label' => 'Код даты',
					],
					[
						'label' => 'Время',
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