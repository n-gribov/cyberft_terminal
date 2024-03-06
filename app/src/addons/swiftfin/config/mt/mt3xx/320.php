<?php

namespace addons\swiftfin\config\mt3xx;

include_once(__DIR__.'/choiceScheme.php');
include(__DIR__.'/../base/currency.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;
use \Yii;

return [
    'class' => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
    'view' => '/wizard/mtFields/mtUniversal.php',
    'type' => '320',
    'formable' => true,
    'scheme' => [
        [ // Обязательная последовательность A Общая информация
            'name' => 'A',
            'type' => 'sequence',
            'status' => Entity::STATUS_MANDATORY,
            'label' => 'Общая информация',
            'scheme' => [
                [
                    'name' => '15A',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'New sequence',
                    'service' => true,
                    'constant' => true,
                    'value' => '',
                    'number' => '1',
                ],
                [
                    'status' => Entity::STATUS_MANDATORY,
                    'name' => '20',
                    'label' => 'Sender\'s reference',
                    'mask' => '16x',
                    'number' => '2',
                ],
                [
                    'name' => '21',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Related Reference',
                    'mask' => '16x',
                    'number' => '3',
                ],
                [
                    'name' => '22A',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'Тип операции',
                    'mask' => '4!c',
                    'number' => '4',
                    'scheme' => [
                        [
                            'label' => 'Тип',
                            'strict' => ['AMND', 'CANC', 'DUPL', 'NEWT',]
                        ],
                    ],
                ],
                [
                    'name' => '94A',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Характер операции',
                    'mask' => '4!c',
                    'number' => '5',
                    'scheme' => [
                        [
                            'label' => 'Характер',
                            'strict' => ['AGNT', 'BILA', 'BROK',]
                        ],
                    ],
                ],
                [
                    'name' => '22B',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'Тип события',
                    'mask' => '4!c',
                    'number' => '6',
                    'scheme' => [
                        [
                            'label' => 'Тип',
                            'strict' => ['CONF', 'MATU', 'ROLL',]
                        ],
                    ],
                ],
                [
                    'name' => '22C',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'Тип события',
                    'mask' => '4!a~2!c~4!n~4!a~2!c',
                    'number' => '7',
                    'scheme' => [
                        [
                            'label' => 'Код банка 1',
                        ],
                        [
                            'label' => 'Код местонахождения 1',
                        ],
                        [
                            'label' => 'Код референса',
                        ],
                        [
                            'label' => 'Код банка 2',
                        ],
                        [
                            'label' => 'Код местонахождения 2',
                        ],
                    ],
                ],
                [
                    'name' => '21N',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Номер контракта стороны А',
                    'mask' => '16x',
                    'number' => '8',
                    'scheme' => [
                        [
                            'label' => 'Номер контракта',
                        ],
                    ]
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_MANDATORY,
                    'name' => '82a',
                    'label' => 'Сторона A',
                    'scheme' => getChoiceScheme('82a', ['A', 'D', 'J']),
                    'number' => '9',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_MANDATORY,
                    'name' => '87a',
                    'label' => 'Сторона B',
                    'scheme' => getChoiceScheme('87a', ['A', 'D', 'J']),
                    'number' => '10',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '83a',
                    'label' => 'Фонд или инструктирующая сторона',
                    'scheme' => getChoiceScheme('83a', ['A', 'D', 'J']),
                    'number' => '11',
                ],
                [
                    'name' => '77D',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Условия контракта',
                    'mask' => '6*35x',
                    'number' => '12',
                    'scheme' => [
                        [
                            'label' => 'Свободный текст',
                        ],
                    ]
                ],
            ],
        ], // Окончание последовательности А Общая информация
        [ // Обязательная последовательность B Детали операции
            'name' => 'B',
            'type' => 'sequence',
            'status' => Entity::STATUS_MANDATORY,
            'label' => 'Детали операции',
            'scheme' => [
                [
                    'name' => '15B',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'New sequence',
                    'service' => true,
                    'constant' => true,
                    'value' => '',
                    'number' => '13',
                ],
                [
                    'name' => '17R',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'Роль стороны А',
                    'mask' => '1!a',
                    'number' => '14',
                    'scheme' => [
                        [
                            'label' => 'Признак',
                            'strict' => ['B', 'L']
                        ],
                    ]
                ],
                [
                    'name' => '30T',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'Дата сделки',
                    'mask' => '8!n',
                    'number' => '15',
                    'scheme' => [
                        [
                            'label' => 'Дата',
                            'name' => 'date'
                        ],
                    ]
                ],
                [
                    'name' => '30V',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'Дата валютирования',
                    'mask' => '8!n',
                    'number' => '16',
                    'scheme' => [
                        [
                            'label' => 'Дата',
                            'name' => 'date'
                        ],
                    ]
                ],
                [
                    'name' => '30P',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'Дата погашения',
                    'mask' => '8!n',
                    'number' => '17',
                    'scheme' => [
                        [
                            'label' => 'Дата',
                            'name' => 'date'
                        ],
                    ]
                ],
                [
                    'name' => '32B',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'Валюта и основная сумма',
                    'mask' => '3!a~15d',
                    'number' => '18',
                    'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper32B',
                    'scheme' => [
                        [
                            'label' => 'Валюта',
                            'name' => 'currency',
                            'strict' => $currency,
                        ],
                        [
                            'label' => 'Сумма',
                            'name' => 'sum'
                        ],
                    ],
                ],
                [
                    'name' => '32H',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Сумма, подлежащая переводу',
                    'mask' => '[N]~3!a~15d',
                    'number' => '19',
                    'scheme' => [
                        [
                            'label' => 'Знак',
                            'strict' => ['+', '-']
                        ],
                        [
                            'label' => 'Валюта',
                            'name' => 'currency',
                            'strict' => $currency,
                        ],
                        [
                            'label' => 'Сумма',
                            'name' => 'sum'
                        ],
                    ]
                ],
                [
                    'name' => '30X',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Сумма, подлежащая переводу',
                    'mask' => '8!n',
                    'number' => '20',
                    'scheme' => [
                        [
                            'label' => 'Сумма',
                            'name' => 'sum'
                        ],
                    ]
                ],
                [
                    'name' => '34E',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'Валюта и сумма процентов',
                    'mask' => '[N]~3!a~15d',
                    'number' => '21',
                    'scheme' => [
                        [
                            'label' => 'Знак',
                            'strict' => ['+', '-']
                        ],
                        [
                            'label' => 'Валюта',
                            'name' => 'currency',
                            'strict' => $currency,
                        ],
                        [
                            'label' => 'Сумма',
                            'name' => 'sum'
                        ],
                    ]
                ],
                [
                    'name' => '37G',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'Процентная ставка',
                    'mask' => '[N]12d',
                    'number' => '22',
                    'scheme' => [
                        [
                            'label' => 'Знак',
                            'strict' => ['+', '-']
                        ],
                        [
                            'label' => 'Ставка',
                        ],
                    ]
                ],
                [
                    'name' => '14D',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'Число дней при расчете',
                    'mask' => '7x',
                    'number' => '23',
                    'scheme' => [
                        [
                            'label' => 'Код',
                            'strict' => ['30E/360', '360/360', 'ACT/360', 'ACT/365', 'AFI/365']
                        ],
                    ]
                ],
                [
                    'name' => '30F',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'Последний день первого процентного периода',
                    'mask' => '8!n',
                    'number' => '24',
                    'scheme' => [
                        [
                            'label' => 'Дата',
                            'name' => 'date'
                        ],
                    ]
                ],
                [
                    'name' => '38J',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'Количество дней',
                    'mask' => '1!a~3!n',
                    'number' => '25',
                    'scheme' => [
                        [
                            'label' => 'Признак',
                            'strict' => [
                                'D' => 'Дней',
                                'M' => 'Месяцев'
                            ]
                        ],
                        [
                            'label' => 'Количество',
                        ],
                    ]
                ],
            ]
        ], // Окончание последовательности B «Детали операции»
        [ // Обязательная последовательность С «Расчетные инструкции для сумм, выплачиваемых стороной А»
            'name' => 'C',
            'type' => 'sequence',
            'status' => Entity::STATUS_MANDATORY,
            'label' => 'Расчетные инструкции для сумм, выплачиваемых стороной А',
            'scheme' => [
                [
                    'name' => '15C',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'New sequence',
                    'service' => true,
                    'constant' => true,
                    'value' => '',
                    'number' => '26',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '53a',
                    'label' => 'Агент-поставщик',
                    'scheme' => getChoiceScheme('53a', ['A', 'D', 'J']),
                    'number' => '27',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '86a',
                    'label' => 'Intermediary Institution',
                    'scheme' => getChoiceScheme('86a', ['A', 'D', 'J']),
                    'number' => '28',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '56a',
                    'label' => 'Intermediary Institution',
                    'scheme' => getChoiceScheme('56a', ['A', 'D', 'J']),
                    'number' => '29',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_MANDATORY,
                    'name' => '57a',
                    'label' => 'Агент-получатель',
                    'scheme' => getChoiceScheme('57a', ['A', 'D', 'J']),
                    'number' => '30',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '58a',
                    'label' => 'Организация-бенефициар',
                    'scheme' => getChoiceScheme('58a', ['A', 'D', 'J']),
                    'number' => '31',
                ],
            ]
        ], // Окончание последовательности С  «Расчетные инструкции для сумм, выплачиваемых стороной А»
        [ // Обязательная последовательность D «Расчетные инструкции для сумм, выплачиваемых стороной В»
            'name' => 'D',
            'type' => 'sequence',
            'status' => Entity::STATUS_MANDATORY,
            'label' => 'Расчетные инструкции для сумм, выплачиваемых стороной B',
            'scheme' => [
                [
                    'name' => '15D',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'New sequence',
                    'service' => true,
                    'constant' => true,
                    'value' => '',
                    'number' => '32',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '53a',
                    'label' => 'Агент-поставщик',
                    'scheme' => getChoiceScheme('53a', ['A', 'D', 'J']),
                    'number' => '33',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '86a',
                    'label' => 'Intermediary Institution 2',
                    'scheme' => getChoiceScheme('86a', ['A', 'D', 'J']),
                    'number' => '34',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '56a',
                    'label' => 'Intermediary Institution',
                    'scheme' => getChoiceScheme('56a', ['A', 'D', 'J']),
                    'number' => '35',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_MANDATORY,
                    'name' => '57a',
                    'label' => 'Агент-получатель',
                    'scheme' => getChoiceScheme('57a', ['A', 'D', 'J']),
                    'number' => '36',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '58a',
                    'label' => 'Организация-бенефициар',
                    'scheme' => getChoiceScheme('58a', ['A', 'D', 'J']),
                    'number' => '37',
                ],
            ]
        ], // Окончание последовательности D «Расчетные инструкции для сумм, выплачиваемых стороной В»
        [ // Необязательная последовательность Е «Расчетные инструкции для процентов, выплачиваемых стороной А»
            'name' => 'E',
            'type' => 'sequence',
            'status' => Entity::STATUS_OPTIONAL,
            'label' => 'Расчетные инструкции для процентов, выплачиваемых стороной А',
            'scheme' => [
                [
                    'name' => '15E',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'New sequence',
                    'service' => true,
                    'constant' => true,
                    'value' => '',
                    'number' => '38',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '53a',
                    'label' => 'Агент-поставщик',
                    'scheme' => getChoiceScheme('53a', ['A', 'D', 'J']),
                    'number' => '39',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '86a',
                    'label' => 'Intermediary Institution',
                    'scheme' => getChoiceScheme('86a', ['A', 'D', 'J']),
                    'number' => '40',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '56a',
                    'label' => 'Intermediary Institution',
                    'scheme' => getChoiceScheme('56a', ['A', 'D', 'J']),
                    'number' => '41',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_MANDATORY,
                    'name' => '57a',
                    'label' => 'Агент-получатель',
                    'scheme' => getChoiceScheme('57a', ['A', 'D', 'J']),
                    'number' => '42',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '58a',
                    'label' => 'Организация-бенефициар',
                    'scheme' => getChoiceScheme('58a', ['A', 'D', 'J']),
                    'number' => '43',
                ],
            ]
        ], // Окончание последовательности E «Расчетные инструкции для процентов, выплачиваемых стороной А»
        [  // Необязательная последовательность F «Расчетные инструкции для процентов, выплачиваемых стороной B»
            'name' => 'F',
            'type' => 'sequence',
            'status' => Entity::STATUS_OPTIONAL,
            'label' => 'Расчетные инструкции для процентов, выплачиваемых стороной B',
            'scheme' => [
                [
                    'name' => '15F',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'New sequence',
                    'service' => true,
                    'constant' => true,
                    'value' => '',
                    'number' => '44',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '53a',
                    'label' => 'Агент-поставщик',
                    'scheme' => getChoiceScheme('53a', ['A', 'D', 'J']),
                    'number' => '45',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '86a',
                    'label' => 'Intermediary Institution 2',
                    'scheme' => getChoiceScheme('86a', ['A', 'D', 'J']),
                    'number' => '46',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '56a',
                    'label' => 'Intermediary Institution',
                    'scheme' => getChoiceScheme('56a', ['A', 'D', 'J']),
                    'number' => '47',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_MANDATORY,
                    'name' => '57a',
                    'label' => 'Агент-получатель',
                    'scheme' => getChoiceScheme('57a', ['A', 'D', 'J']),
                    'number' => '48',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '58a',
                    'label' => 'Организация-бенефициар',
                    'scheme' => getChoiceScheme('58a', ['A', 'D', 'J']),
                    'number' => '49',
                ],
            ]
        ], // Окончание последовательности F «Расчетные инструкции для процентов, выплачиваемых стороной B»
        [ // Необязательная последовательность G «Налоговая информация»
            'name' => 'G',
            'type' => 'sequence',
            'status' => Entity::STATUS_OPTIONAL,
            'label' => 'Налоговая информация',
            'scheme' => [
                [
                    'name' => '15G',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'New sequence',
                    'service' => true,
                    'constant' => true,
                    'value' => '',
                    'number' => '50',
                ],
                [
                    'name' => '37L',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'Ставка налога',
                    'mask' => '12d',
                    'number' => '51',
                    'scheme' => [
                        [
                            'label' => 'Ставка',
                        ],
                    ]
                ],
                [
                    'name' => '33B',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'Валюта операции и чистая сумма процентов',
                    'mask' => '3!a~15d',
                    'number' => '52',
                    'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper33B',
                    'scheme' => [
                        [
                            'label' => 'Валюта',
                            'name' => 'currency',
                            'strict' => $currency,
                        ],
                        [
                            'label' => 'Сумма',
                            'name' => 'sum',
                        ],
                    ]
                ],
                [
                    'name' => '36',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Курс конвертации',
                    'mask' => '12d',
                    'number' => '53',
                    'scheme' => [
                        [
                            'label' => 'Курс',
                        ],
                    ]
                ],
                [
                    'name' => '33E',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Валюта отчетности и сумма налога',
                    'mask' => '3!a~15d',
                    'number' => '54',
                    'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper33E',
                    'scheme' => [
                        [
                            'label' => 'Валюта',
                            'name' => 'currency',
                            'strict' => $currency,
                        ],
                        [
                            'label' => 'Сумма',
                            'name' => 'sum',
                        ],
                    ]
                ],
            ],
        ], // Окончание последовательности G «Налоговая информация»
        [ // Необязательная последовательность H «Дополнительная информация»
            'name' => 'H',
            'type' => 'sequence',
            'status' => Entity::STATUS_OPTIONAL,
            'label' => 'Дополнительная информация',
            'scheme' => [
                [
                    'name' => '15H',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'New sequence',
                    'service' => true,
                    'constant' => true,
                    'value' => '',
                    'number' => '55',
                ],
                [
                    'name' => '29A',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Контактная информация',
                    'mask' => '4*35x',
                    'number' => '56',
                    'scheme' => [
                        [
                            'label' => 'Свободный текст',
                        /**
                         * Должен быть использован хотя бы один из следующих кодов:
                         */
//						  'strict' => ['/DEPT/', '/FAXT/', '/NAME/', '/PHON/', '/TELX/']
                        ],
                    ]
                ],
                [
                    'name' => '24D',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Способ заключения сделки',
                    'mask' => '4!c[/35x]',
                    'number' => '57',
                    'scheme' => [
                        [
                            'label' => 'Код',
                            'strict' => ['BROK', 'ELEC', 'PHON',]
                        ],
                        [
                            'label' => 'Дополнительная информация',
                        ],
                    ]
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '84a',
                    'label' => 'Заключивший сделку филиал стороны А',
                    'scheme' => getChoiceScheme('84a', ['A', 'B', 'D', 'J']),
                    'number' => '58',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '85a',
                    'label' => 'Заключивший сделку филиал стороны В',
                    'scheme' => getChoiceScheme('85a', ['A', 'D', 'J']),
                    'number' => '59',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '88a',
                    'label' => 'Идентификация брокера',
                    'scheme' => getChoiceScheme('88a', ['A', 'B', 'D', 'J']),
                    'number' => '60',
                ],
                [
                    'name' => '71F',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Комиссия брокера',
                    'mask' => '3!a~15d',
                    'number' => '61',
//                	'scheme' => [
//                        [
//                            'name'   => 'currency',
//                            'label'  => 'Currency',
//                            'strict' => $currency
//                        ],
//                        [
//                            'name'  => 'sum',
//                            'label' => 'Sum',
//                        ],
//                    ],
//                    'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper71F',
                ],
                [
                    'name' => '26H',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Референс контрагента',
                    'mask' => '16x',
                    'number' => '62',
                ],
                [
                    'name' => '21G',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Референс брокера ',
                    'mask' => '16x',
                    'number' => '63',
                ],
                [
                    'name' => '72',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Sender to Receiver Information',
                    'mask' => '6*35x',
                    'number' => '64',
                    'scheme' => [
                        [
                            'label' => 'Свободный текст – структурированный формат',
                        /**
                         * Коды, согласованные на двусторонней основе могут быть использованы в этом поле
                         */
//						  'strict' => ['TIME', 'VENUE',]
                        ],
                    ]
                ],
            ]
        ], // Окончание последовательности H «Дополнительная информация»
        [
            'name' => 'I',
            'type' => 'sequence',
            'status' => Entity::STATUS_OPTIONAL,
            'label' => 'Дополнительные суммы',
            'scheme' => [
                [
                    'name' => '15I',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'New sequence',
                    'service' => true,
                    'constant' => true,
                    'value' => '',
                    'number' => '65',
                ],
                [
                    'name' => '18A',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'Количество повторений',
                    'mask' => '5n',
                    'number' => '66',
                    'scheme' => [
                        [
                            'label' => 'Количество',
                        ],
                    ]
                ],
                [
                    'type' => 'collection',
                    'name' => '30F-32H',
                    'disableLabel' => true,
                    'scheme' => [
                        [
                            'status' => Entity::STATUS_OPTIONAL,
                            'name' => '30F',
                            'label' => 'Дата платежа',
                            'mask' => '8!n',
                            'number' => '67',
                            'scheme' => [
                                [
                                    'label' => 'Дата',
                                    'name' => 'date'
                                ],
                            ]
                        ],
                        [
                            'status' => Entity::STATUS_OPTIONAL,
                            'name' => '32H',
                            'label' => 'Валюта, сумма',
                            'mask' => '[N]~3!a~15d',
                            'number' => '68',
                            'scheme' => [
                                [
                                    'label' => 'Знак',
                                    'strict' => ['+', '-']
                                ],
                                [
                                    'label' => 'Валюта',
                                    'name' => 'currency',
                                    'strict' => $currency,
                                ],
                                [
                                    'label' => 'Сумма',
                                    'name' => 'sum',
                                ],
                            ]
                        ],
                    ],
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '53a',
                    'label' => 'Агент-поставщик',
                    'scheme' => getChoiceScheme('53a', ['A', 'D', 'J']),
                    'number' => '69',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '86a',
                    'label' => 'Intermediary Institution 2',
                    'scheme' => getChoiceScheme('86a', ['A', 'D', 'J']),
                    'number' => '70',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '56a',
                    'label' => 'Intermediary Institution',
                    'scheme' => getChoiceScheme('56a', ['A', 'D', 'J']),
                    'number' => '71',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_MANDATORY,
                    'name' => '57a',
                    'label' => 'Агент-получатель',
                    'scheme' => getChoiceScheme('57a', ['A', 'D', 'J']),
                    'number' => '72',
                ],
            ],
        ],
    ],
];
