<?php
namespace addons\swiftfin\config\mt7xx;
include_once(__DIR__.'/choiceScheme.php');
include(__DIR__.'/../base/currency.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '700',
	'formable' => true,
	'aliases'  => [
		'currency' => ['32B', 'currency'],
		'sum'      => ['32B', 'sum'],
	],
	'scheme'   => [
		[
			'name'   => '27',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Порядковый номер',
			'mask'   => '1!n/1!n',
			'scheme' => [
				[
					'label' => 'Номер',
				],
				[
					'label' => 'Общее количество',
				],
			],
		],
		[
			'name'   => '40A',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Вид документарного аккредитива',
			'mask'   => '24x',
			'scheme' => [
				[
					'label'  => 'Вид',
					'strict' => [
						'IRREVOCABLE', 'REVOCABLE', 'IRREVOCABLE TRANSFERABLE', 'REVOCABLE TRANSFERABLE',
						'IRREVOCABLE STANDBY', 'REVOCABLE STANDBY', 'IRREVOC TRANS STANDBY'
					]
				],
			],
		],
		[
			'name'   => '20',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Номер документарного аккредитива',
			'mask'   => '16x',
			'scheme' => [
				[
					'label' => 'Номер',
				],
			],
		],
		[
			'name'   => '23',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Ссылка на предварительное авизование',
			'mask'   => '16x',
			'scheme' => [
				[
					'label' => 'Ссылка',
				],
			],
		],
		[
			'name'   => '31C',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Дата открытия',
			'mask'   => '6!n',
			'scheme' => [
				[
					'label' => 'Дата',
				],
			],
		],
		[
			'name'   => '40E',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Применяемые правила',
			'mask'   => '30x[/35x]',
			'scheme' => [
				[
					'label'  => 'Применяемые правила',
					'strict' => [
						'EUCP LATEST VERSION', 'EUCPURR LATEST VERSION', 'ICP LATEST VERSION', 'OTHR',
						'UCP LATEST VERSION', 'UCPURR LATEST VERSION'
					]
				],
				[
					'label' => 'Свободный текст',
				],
			],
		],
		[
			'name'   => '31D',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Дата и место истечения срока',
			'mask'   => '6!n~29x',
			'scheme' => [
				[
					'label' => 'Дата',
                    'name' => 'date'
				],
				[
					'label' => 'Место',
				],
			],
		],
		[
			'name'   => '51a',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Банк аппликанта',
			'type'   => 'choice',
			'scheme' => getChoiceScheme('51a', ['A', 'D']),
		],
		[
			'name'   => '50',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Аппликант',
			'mask'   => '4*35x',
			'scheme' => [
				[
					'label' => 'Наименование и адрес',
				],
			],
		],
		[
			'name'   => '59',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Бенефициар',
			'mask'   => '[/34x]'.Entity::INLINE_BREAK.'4*35x',
			'scheme' => [
				[
					'label' => 'Счет',
                    'name' => 'account'
				],
				[
					'label' => 'Наименование и адрес',
				],
			],
		],
		[
			'name'   => '32B',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Код валюты, сумма',
			'mask'   => '3!a~15d',
			'scheme' => [
				[
					'name'   => 'currency',
					'label'  => 'Валюта',
					'strict' => $currency,
				],
				[
					'name'  => 'sum',
					'label' => 'Сумма',
				],
			],
		],
		[
			'name'   => '39A',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Процентный допуск аккредитива',
			'mask'   => '2n/2n',
			'scheme' => [
				[
					'label' => 'Допуск 1',
				],
				[
					'label' => 'Допуск 2',
				],
			],
		],
		[
			'name'   => '39B',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Максимальная сумма аккредитива',
			'mask'   => '13x',
			'scheme' => [
				[
					'label' => 'Сумма',
                    'name' => 'sum'
				],
			],
		],
		[
			'name'   => '39C',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Дополнительные включаемые суммы',
			'mask'   => '4*35x',
			'scheme' => [
				[
					'label' => 'Свободный текст',
				],
			],
		],
		[
			'name'   => '41a',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Исполняющий банк ... Способ исполнения ...',
			'type'   => 'choice',
			'scheme' => getChoiceScheme('41a', ['A', 'D']),
		],
		[
			'name'   => '42C',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Условия трат...',
			'mask'   => '3*35x',
			'scheme' => [
				[
					'label' => 'Свободный текст',
				],
			],
		],
		[
			'name'   => '42a',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Трассат',
			'type'   => 'choice',
			'scheme' => getChoiceScheme('42a', ['A', 'D']),
		],
		[
			'name'   => '42M',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Детали смешанной оплаты',
			'mask'   => '4*35x',
			'scheme' => [
				[
					'label' => 'Свободный текст',
				],
			],
		],
		[
			'name'   => '42P',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Детали отсрочки платежа',
			'mask'   => '4*35x',
			'scheme' => [
				[
					'label' => 'Свободный текст',
				],
			],
		],
		[
			'name'   => '43P',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Частичные отгрузки',
			'mask'   => '35x',
			'scheme' => [
				[
					'label' => 'Свободный текст',
				],
			],
		],
		[
			'name'   => '43T',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Перегрузка',
			'mask'   => '35x',
			'scheme' => [
				[
					'label' => 'Свободный текст',
				],
			],
		],
		[
			'name'   => '44A',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Место принятия к перевозке/отправка из.../место получения',
			'mask'   => '65x',
			'scheme' => [
				[
					'label' => 'Свободный текст',
				],
			],
		],
		[
			'name'   => '44E',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Порт погрузки/аэропорт отправления',
			'mask'   => '65x',
			'scheme' => [
				[
					'label' => 'Свободный текст',
				],
			],
		],
		[
			'name'   => '44F',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Порт выгрузки/аэропорт назначения',
			'mask'   => '65x',
			'scheme' => [
				[
					'label' => 'Свободный текст',
				],
			],
		],
		[
			'name'   => '44B',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Место конечного назначения/для транспортировки в .../место доставки',
			'mask'   => '65x',
			'scheme' => [
				[
					'label' => 'Свободный текст',
				],
			],
		],
		[
			'name'   => '44C',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Последняя дата отгрузки',
			'mask'   => '6!n',
			'scheme' => [
				[
					'label' => 'Дата',
                    'name' => 'date'
				],
			],
		],
		[
			'name'   => '44D',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Период отгрузки',
			'mask'   => '6*65x',
			'scheme' => [
				[
					'label' => 'Свободный текст',
				],
			],
		],
		[
			'name'   => '45A',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Описание товаров и/или услуг',
			'mask'   => '99*65x',
			'scheme' => [
				[
					'label' => 'Свободный текст',
				],
			],
		],
		[
			'name'   => '46A',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Требуемые документы',
			'mask'   => '99*65x',
			'scheme' => [
				[
					'label' => 'Свободный текст',
				],
			],
		],
		[
			'name'   => '47A',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Дополнительные условия',
			'mask'   => '99*65x',
			'scheme' => [
				[
					'label' => 'Свободный текст',
				],
			],
		],
		[
			'name'   => '71B',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Расходы',
			'mask'   => '6*65x',
			'scheme' => [
				[
					'label' => 'Свободный текст',
				],
			],
		],
		[
			'name'   => '48',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Срок представления документов',
			'mask'   => '4*35x',
			'scheme' => [
				[
					'label' => 'Свободный текст',
				],
			],
		],
		[
			'name'   => '49',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Инструкции по подтверждению',
			'mask'   => '7!x',
			'scheme' => [
				[
					'label' => 'Инструкции',
				],
			],
		],
		[
			'name'   => '53a',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Рамбурсирующий Банк',
			'type'   => 'choice',
			'scheme' => getChoiceScheme('53a', ['A', 'D']),
		],
		[
			'name'   => '78',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Инструкции оплачивающему/акцептующему/негоциирующему банку',
			'mask'   => '12*65x',
			'scheme' => [
				[
					'label' => 'Свободный текст',
				],
			],
		],
		[
			'name'   => '57a',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => '«Второй авизующий» банк',
			'type'   => 'choice',
			'scheme' => getChoiceScheme('57a', ['A', 'B', 'D']),
		],
		[
			'name'   => '72',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Sender to Receiver Information',
			'mask'   => '6*35x',
			'scheme' => [
				[
					'label' => 'Свободный текст',
				],
			],
		],
	],
];
