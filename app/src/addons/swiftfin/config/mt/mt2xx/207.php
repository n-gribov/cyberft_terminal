<?php
namespace addons\swiftfin\config\mt2xx;
include_once(__DIR__. '/choiceScheme.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;
use yii\helpers\Url;

return [
	'class'    => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
	'view'     => '/wizard/mtFields/mtUniversal.php',
	'type'     => '207',
	'formable' => true,
	'scheme'   => [
			[ // Обязательная последовательность A  Общая информация
				'name' => 'A',
				'type' => 'sequence',
				'status' => Entity::STATUS_MANDATORY,
				'label' => 'Общая информация',
				'scheme' => [
					[
						'name'   => '20',
						'status' => Entity::STATUS_MANDATORY,
						'label'  => 'Референс Отправителя',
						'mask'   => '16x',
						'number' => '1',
					],
					[
						'name'   => '21R',
						'status' => Entity::STATUS_OPTIONAL,
						'label'  => 'Референс, указанный организацией-заказчиком',
						'mask'   => '16x',
						'number' => '2',
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
						'name'   => '30',
						'status' => Entity::STATUS_MANDATORY,
						'label'  => 'Требуемая дата исполнения',
						'mask'   => '6!n',
						'number' => '4',
						'scheme' => [
							[
								'label' => 'Дата',
                                'name' => 'date'
							],
						],
					],	
					[
						'name'   => '51A',
						'status' => Entity::STATUS_OPTIONAL,
						'label'  => 'Финансовая организация-приказодатель',
						'mask'   => "[/1!a]~[/34x]~".Entity::INLINE_BREAK."4!a2!a2!c[3!c]",
						'number' => '5',
						'scheme' => [
							[
								'label' => 'Идентификация стороны',
                                'name' => 'identityPart'
							],
							[
								'label' => 'Идентификационный код'
							],
						],
					],
					[
						'name'   => '52G',
						'status' => Entity::STATUS_MANDATORY,
						'label'  => 'Финансовая организация-заказчик',
						'mask'   => "/34x~".Entity::INLINE_BREAK."4!a2!a2!c[3!c]",
						'number' => '6',
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
						],
					],
					[
						'name'   => '52',
						'status' => Entity::STATUS_OPTIONAL,
						'label'  => 'Обслуживающая счет организация',
						'type'   => 'choice',
						'number' => '7',
						'scheme' => getChoiceScheme('52', ['A', 'C']),
					],
					[
						'name'   => '72',
						'status' => Entity::STATUS_OPTIONAL,
						'label'  => 'Информация Отправителя Получателю',
						'mask'   => '6*35x',
						'number' => '8',
						'scheme' => [
							[
								'label' => 'Свободный текст - Структурированный формат'
							],
						],
					],			
				],
			], // Окончание последовательности А Общая информация			
		[  // Обязательная повторяющаяся последовательность В Детали операции
				'name' => 'B',
				'type' => 'sequence',
				'status' => Entity::STATUS_MANDATORY,
				'label' => 'Детали операции',
				'scheme' => [
						[
							'type' => 'collection',
							'name' => '21-58a',
							'disableLabel' => true,
							'scheme' => [
							[
								'name' => '21',
								'status' => Entity::STATUS_OPTIONAL,
								'label' => 'Референс операции',
								'mask' => '16x',
								'number' => '9',
							],			
							[
								'type' => 'collection',
								'name' => '23E',
								'disableLabel' => true,
								'scheme' => [	
									[
										'name'   => '23E',
										'status' => Entity::STATUS_OPTIONAL,
										'label'  => 'Код инструкций',
										'mask'   => "4!c~[/30x]",
										'number' => '10',
										'scheme' => [
											[
												'label' => 'Код инструкций'
											],
											[
												'label' => 'Дополнительная информация'
											],
										],
									],
								],	
							],
						[
							'name'   => '32B',
							'status' => Entity::STATUS_MANDATORY,
							'label'  => 'Валюта/Сумма операции',
							'mask'   => '3!a~15d',
							'number' => '11',
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
							'name'   => '56a',
							'status' => Entity::STATUS_OPTIONAL,
							'label'  => 'Посредник',
							'type'   => 'choice',
							'number' => '12',
							'scheme' => getChoiceScheme('56a', ['A', 'D']),
						],	
						[
							'name'   => '57a',
							'status' => Entity::STATUS_OPTIONAL,
							'label'  => 'Банк бенефициара',
							'type'   => 'choice',
							'number' => '13',
							'scheme' => getChoiceScheme('57a', ['A', 'B', 'D']),
						],

						[
							'name'   => '58a',
							'status' => Entity::STATUS_MANDATORY,
							'label'  => 'Финансовая организация-бенефициар',
							'type'   => 'choice',
							'number' => '14',
							'scheme' => getChoiceScheme('58a', ['A', 'D']),
						],
					],	
				],		
			],
		], // Окончание  последовательности В  Детали операции 
	],
];