<?php
namespace addons\swiftfin\config\mt7xx;
include_once(__DIR__.'/choiceScheme.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '750',
	'formable' => true,
	'scheme'   => [
		[
			'name'   => '20',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Референс Отправителя',
			'mask'   => '16x',
			'number' => '1',
		],
		[
			'name'   => '21',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Связанный референс',
			'mask'   => '16x',
			'number' => '2',
		],
		[
			'name'   => '32B',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Основная сумма',
			'mask'   => '3!a~15d',
			'number' => '3',
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
			'name'   => '33B',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Дополнительные суммы',
			'mask'   => '3!a~15d',
			'number' => '4',
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
			'name'   => '71B',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Расходы, которые должны быть вычтены',
			'mask'   => '6*35x',			
			'number' => '5',			
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],
		[
			'name'   => '73',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Расходы, которые должны быть добавлены',
			'mask'   => '6*35x',
			'number' => '6',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],
		[
			'name'   => '34B',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Общая сумма подлежащая оплате',
			'mask'   => '3!a~15d',
			'number' => '7',
            'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper34B',
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
			'name'   => '57а',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Банк бенефициара',
			'type'   => 'choice',
			'scheme' => getChoiceScheme('57a', ['A', 'B', 'D']),			
			'number' => '6',			
		],
		[
			'name'   => '72',
			'status' => Entity::STATUS_OPTIONAL,
			'label'  => 'Информация Отправителя Получателю',
			'mask'   => '6*35x',
			'number' => '9',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],
			],
		],
		[
			'name'   => '77J',
			'status' => Entity::STATUS_MANDATORY,
			'label'  => 'Расхождения',
			'mask'   => '70*50x',
			'number' => '10',
			'scheme' => [
				[
					'label' => 'Свободный текст'
				],				
			],
		],
	],
];
