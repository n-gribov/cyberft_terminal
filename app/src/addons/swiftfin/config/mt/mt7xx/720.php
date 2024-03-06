<?php
namespace addons\swiftfin\config\mt7xx;
include_once(__DIR__.'/choiceScheme.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '720',
	'formable' => true,
	'scheme'   => [
		[
			'name'   => '27',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Порядковый номер',
			'mask'   => '1!n~1!n',
			'number' => '1',
			'scheme' => [
				[
					'label' => 'Номер'
				],
				[
					'label' => 'Общее количество'
				],
			],
		],
		[
			'name'   => '40B',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Вид документарного аккредитива',
			'mask'   => '24x~24x',
			'number' => '2',
			'scheme' => [
				[
					'label' => 'Вид'
				],
				[
					'label' => 'Код'
				],
			],			
		],
		[
			'name'   => '20',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Референс переводящего банка',
			'mask'   => '16x',
			'number' => '3',
		],
		[
			'name'   => '21',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Номер документарного аккредитива',
			'mask'   => '16x',
			'number' => '4',
		],
		[
			'name'   => '31C',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Дата открытия',
			'mask'   => '6!n',
			'number' => '5',
			'scheme' => [
				[
					'label' => 'Дата'
				],
			]
		],
		[
			'name'   => '40E',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Применяемые правила',
			'mask'   => '30x[/35x]',
			'number' => '6',
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
			'number' => '7',
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
			'name'   => '52a',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Банк-эмитент исходного аккредитива',
			'type'   => 'choice',
			'scheme' => getChoiceScheme('52a', ['A', 'D']),			
			'number' => '8',
		],
		[
			'name'   => '50B',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Эмитент исходного аккредитива, не являющийся банком',
			'mask'   => '4*35x',
			'number' => '9',
		],
		[
			'name'   => '50',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Первый бенефициар',
			'mask'   => '4*35x',
			'number' => '10',
		],
		[
			'name'   => '59',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Второй бенефициар',
			'mask'   => '[/34x]~4*35x',
			'number' => '11',
			'scheme' => [
				[
					'label' => 'Счет',
                    'name' => 'account'
				],
				[
					'label' => 'Наименование и адрес'
				],
			],
		],
		[
			'name'   => '32B',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Код валюты, сумма',
			'mask'   => '3!a~15d',
			'number' => '12',
            'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper32B',
			'scheme' => [
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
		[
			'name'   => '39A',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Процентный допуск аккредитива',
			'mask'   => '2n~2n',
			'number' => '13',
			'scheme' => [
				[
					'label' => 'Допуск 1'
				],
				[
					'label' => 'Допуск 2'
				],
			],
		],
		[
			'name'   => '39B',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Максимальная сумма аккредитива',
			'mask'   => '13x~13x',
			'number' => '14',
			'scheme' => [
				[
					'label'  => 'Применяемые правила',
					'strict' => [
						'NOT EXCEEDING',
					]
				],
				[
					'label' => 'Свободный текст',
				],
			],
		],
		[
			'name'   => '39C',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Дополнительные включаемые суммы',
			'mask'   => '4*35x',
			'number' => '15',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],				
			],
		],
		[
			'name'   => '41a',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Исполняющий банк... Способ исполнения...',
			'type'   => 'choice',
			'scheme' => getChoiceScheme('41a', ['A', 'D']),			
			'number' => '16',
		],
		[
			'name'   => '42C',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Условия тратт...',
			'mask'   => '3*35x',
			'number' => '17',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],				
			],
		],		
		[
			'name'   => '42a',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Трассат',
			'type'   => 'choice',
			'scheme' => getChoiceScheme('42a', ['A', 'D']),
			'number' => '18',
		],
		[
			'name'   => '42M',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Детали смешанной оплаты',
			'mask'   => '4*35x',
			'number' => '19',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],
		[
			'name'   => '42P',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Детали отсрочки платежа',
			'mask'   => '4*35x',
			'number' => '20',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],
		[
			'name'   => '43P',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Детали отсрочки платежа',
			'mask'   => '1*35x',
			'number' => '21',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],
		[
			'name'   => '43T',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Перегрузка',
			'mask'   => '1*35x',
			'number' => '22',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],
		[
			'name'   => '44A',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Место принятия к перевозке/отправка из.../место получения',
			'mask'   => '1*65x',
			'number' => '23',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],
		[
			'name'   => '44E',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Порт погрузки/аэропорт отправления',
			'mask'   => '1*65x',
			'number' => '24',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],
		[
			'name'   => '44F',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Порт выгрузки/аэропорт назначения',
			'mask'   => '1*65x',
			'number' => '25',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],
		[
			'name'   => '44B',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Место конечного назначения/для транспортировки в ... /место доставки',
			'mask'   => '1*65x',
			'number' => '26',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],
		[
			'name'   => '44C',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Последняя дата отгрузки',
			'mask'   => '6!n',
			'number' => '27',
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
			'number' => '28',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],
		[
			'name'   => '45A',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Описание товаров и/или услуг',
			'mask'   => '100*65x',
			'number' => '29',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],
		[
			'name'   => '46A',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Требуемые документы',
			'mask'   => '100*65x',
			'number' => '30',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],
		[
			'name'   => '47A',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Дополнительные условия',
			'mask'   => '100*65x',
			'number' => '31',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],
		[
			'name'   => '71B',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Расходы',
			'mask'   => '6*35x',
			'number' => '32',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],
		[
			'name'   => '48',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Срок представления документов',
			'mask'   => '4*35x',
			'number' => '33',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],
		[
			'name'   => '49',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Инструкции по подтверждению',
			'mask'   => '7!x',
			'number' => '34',
			'scheme' => [
				[
					'label' => 'Инструкции',
					'strict' => [
						'CONFIRM', 'MAY ADD', 'WITHOUT'
					]
				],
			],
		],
		[
			'name'   => '78',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Инструкции оплачивающему/акцептующему/негоциирующему банку',
			'mask'   => '12*65x',
			'number' => '35',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],
		[
			'name'   => '57a',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => '«Второй авизующий» банк',
			'type'   => 'choice',
			'scheme' => getChoiceScheme('57a', ['A', 'B', 'D']),
			'number' => '36',
		],
		[
			'name'   => '72',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Информация Отправителя Получателю',
			'mask'   => '6*35x',
			'number' => '37',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],
	],
];
