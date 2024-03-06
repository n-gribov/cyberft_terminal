<?php

namespace addons\swiftfin\config\mt3xx;

include_once(__DIR__.'/choiceScheme.php');
include(__DIR__.'/../base/currency.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;
use \Yii;

return [
    'class' => 'addons\swiftfin\models\documents\mt\MtUniversal300',
    'view' => '/wizard/mtFields/mtUniversal.php',
    'type' => '300',
    'formable' => true,
    'aliases' => [
        'sum' => ['B', 'B2', '33B', 'sum'],
        'currency' => ['B', 'B2', '33B', 'currency'],
    ],
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
                            'strict' => ['AMND', 'CANC', 'DUPL', 'NEWT', 'EXOP']
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
                    'name' => '22C',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'Общий референс',
                    'mask' => '4!a~2!c~4!n~4!a~2!c',
                    'number' => '6',
                    'scheme' => [
                        [
                            'name' => 'bank1Code',
                            'label' => 'Код банка 1',
                        ],
                        [
                            'name' => 'bank1Place',
                            'label' => 'Код местонахождения 1',
                        ],
                        [
                            'name' => 'referenceCode',
                            'label' => 'Код референса',
                        ],
                        [
                            'name' => 'bank2Code',
                            'label' => 'Код банка 2',
                        ],
                        [
                            'name' => 'bank2Place',
                            'label' => 'Код местонахождения 2',
                        ],
                    ]
                ],
                [
                    'name' => '17T',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Признак пакета сделок',
                    'mask' => '1!a',
                    'number' => '7',
                    'scheme' => [
                        [
                            'label' => 'Признак',
                            'strict' => ['Y', 'N']
                        ],
                    ]
                ],
                [
                    'name' => '17U',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Признак расчетов частями',
                    'mask' => '1!a',
                    'number' => '8',
                    'scheme' => [
                        [
                            'label' => 'Признак',
                            'strict' => ['Y', 'N']
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
                    'status' => Entity::STATUS_MANDATORY,
                    'name' => '83a',
                    'label' => 'Фонд или клиент-бенефициар',
                    'scheme' => getChoiceScheme('83a', ['A', 'D', 'J']),
                    'number' => '11',
                ],
                [
                    'name' => '77H',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Тип, дата, и версия соглашения',
                    'mask' => '6a[/8!n][//4!n]',
                    'number' => '12',
                    'scheme' => [
                        [
                            'label' => 'Тип соглашения',
                            'strict' => ['AFB', 'DERV', 'FBF', 'FEOMA', 'ICOM', 'IFEMA', 'ISDA', 'OTHER']
                        ],
                        [
                            'label' => 'Дата',
                            'name' => 'date'
                        ],
                        [
                            'label' => 'Версия',
                        ],
                    ]
                ],
                [
                    'name' => '77D',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Условия контракта',
                    'mask' => '6*35x',
                    'number' => '13',
                    'scheme' => [
                        [
                            'label' => 'Свободный текст',
                        /**
                         * Один из следующих кодов или коды, согласованные на двусторонней основе,
                         * могут использоваться в этом поле
                         */
                        //						  'strict' => ['FIX', 'SETC', 'SRCE', 'VALD']
                        ],
                    ]
                ],
                [
                    'name' => '14C',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Год версии Определений',
                    'mask' => '4!n',
                    'number' => '14',
                    'scheme' => [
                        [
                            'label' => 'Год',
                        ],
                    ]
                ],
            ]
        ], // Окончание последовательности А «Общая информация»
        [ // Обязательная последовательность B «Детали операции»
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
                    'number' => '15',
                ],
                [
                    'name' => '30T',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'Дата сделки',
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
                    'name' => '30V',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'Дата валютирования',
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
                    'name' => '36',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'Курс конвертации',
                    'mask' => '12d',
                    'number' => '18',
                    'scheme' => [
                        [
                            'label' => 'Курс',
                        ],
                    ]
                ],
                [ // Обязательная подпоследовательность B1 «Купленная сумма»
                    'name' => 'B1',
                    'type' => 'sequence',
                    'label' => 'Купленная сумма',
                    'status' => Entity::STATUS_MANDATORY,
                    'scheme' => [
                        [
                            'name' => '32B',
                            'status' => Entity::STATUS_MANDATORY,
                            'label' => 'Валюта и основная сумма',
                            'mask' => '3!a~15d',
                            'number' => '19',
                            'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper32B',
                            'scheme' => [
                                [
                                    'label' => 'Валюта',
                                    'strict' => $currency,
                                    'name' => 'currency'
                                ],
                                [
                                    'label' => 'Сумма',
                                    'name' => 'sum'
                                ],
                            ],
                        ],
                        [
                            'type' => 'choice',
                            'status' => Entity::STATUS_OPTIONAL,
                            'name' => '53a',
                            'label' => 'Агент-поставщик',
                            'scheme' => getChoiceScheme('53a', ['A', 'D', 'J']),
                            'number' => '20',
                        ],
                        [
                            'type' => 'choice',
                            'status' => Entity::STATUS_OPTIONAL,
                            'name' => '56a',
                            'label' => 'Intermediary Institution',
                            'scheme' => getChoiceScheme('56a', ['A', 'D', 'J']),
                            'number' => '21',
                        ],
                        [
                            'type' => 'choice',
                            'status' => Entity::STATUS_MANDATORY,
                            'name' => '57a',
                            'label' => 'Агент-получатель',
                            'scheme' => getChoiceScheme('57a', ['A', 'D', 'J']),
                            'number' => '22',
                        ],
                    ]
                ], // Окончание подпоследовательности B1 «Купленная сумма»
                [ // Обязательная подпоследовательность B2 «Проданная сумма»
                    'name' => 'B2',
                    'type' => 'sequence',
                    'label' => 'Проданная сумма',
                    'status' => Entity::STATUS_MANDATORY,
                    'scheme' => [
                        [
                            'name' => '33B',
                            'status' => Entity::STATUS_MANDATORY,
                            'label' => 'Валюта, сумма',
                            'mask' => '3!a~15d',
                            'number' => '23',
                            'scheme' => [
                                [
                                    'name' => 'currency',
                                    'label' => 'Валюта',
                                    'strict' => $currency
                                ],
                                [
                                    'name' => 'sum',
                                    'label' => 'Сумма',
                                ],
                            ],
                            'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper33B',
                        ],
                        [
                            'type' => 'choice',
                            'status' => Entity::STATUS_OPTIONAL,
                            'name' => '53a',
                            'label' => 'Агент-поставщик',
                            'scheme' => getChoiceScheme('53a', ['A', 'D', 'J']),
                            'number' => '24',
                        ],
                        [
                            'type' => 'choice',
                            'status' => Entity::STATUS_OPTIONAL,
                            'name' => '56a',
                            'label' => 'Intermediary Institution',
                            'scheme' => getChoiceScheme('56a', ['A', 'D', 'J']),
                            'number' => '25',
                        ],
                        [
                            'type' => 'choice',
                            'status' => Entity::STATUS_MANDATORY,
                            'name' => '57a',
                            'label' => 'Агент-получатель',
                            'scheme' => getChoiceScheme('57a', ['A', 'D', 'J']),
                            'number' => '26',
                        ],
                        [
                            'type' => 'choice',
                            'status' => Entity::STATUS_OPTIONAL,
                            'name' => '58a',
                            'label' => 'Организация-бенефициар',
                            'scheme' => getChoiceScheme('58a', ['A', 'D', 'J']),
                            'number' => '27',
                        ],
                    ]
                ], // Окончание подпоследовательности B2 «Проданная сумма»
            ],
        ], // Окончание последовательности B «Детали операции»
        [ // Необязательная последовательность С «Дополнительная общая информация»
            'name' => 'C',
            'type' => 'sequence',
            'status' => Entity::STATUS_OPTIONAL,
            'label' => 'Дополнительная общая информация',
            'scheme' => [
                [
                    'name' => '15C',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'New sequence',
                    'service' => true,
                    'constant' => true,
                    'value' => '',
                    'number' => '28',
                ],
                [
                    'name' => '29A',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Контактная информация',
                    'mask' => '4*35x',
                    'number' => '29',
                    'scheme' => [
                        [
                            'label' => 'Свободный текст – структурированный формат',
                        //						  'strict' => ['/DEPT/', '/FAXT/', '/NAME/', '/PHON/', '/TELX/']
                        ],
                    ]
                ],
                [
                    'name' => '24D',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Способ заключения сделки',
                    'mask' => '4!c[/35x]',
                    'number' => '30',
                    'scheme' => [
                        [
                            'label' => 'Способ',
                            'strict' => ['BROK', 'ELEC', 'FAXT', 'PHON', 'TELX',]
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
                    'number' => '31',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '85a',
                    'label' => 'Заключивший сделку филиал стороны В',
                    'scheme' => getChoiceScheme('85a', ['A', 'D', 'J']),
                    'number' => '32',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '88a',
                    'label' => 'Идентификация брокера',
                    'scheme' => getChoiceScheme('88a', ['A', 'D', 'J']),
                    'number' => '33',
                ],
                [
                    'name' => '71F',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Комиссия брокера',
                    'mask' => '3!a~15d',
                    'number' => '34',
//                    'scheme' => [
//                        [
//                            'name' => 'currency',
//                            'label' => 'Currency',
//                            'strict' => $currency
//                        ],
//                        [
//                            'name' => 'sum',
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
                    'number' => '35',
                ],
                [
                    'name' => '21G',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Референс брокера ',
                    'mask' => '16x',
                    'number' => '36',
                ],
                [
                    'name' => '72',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Sender to Receiver Information',
                    'mask' => '6*35x',
                    'number' => '37',
                    'scheme' => [
                        [
                            'label' => 'Свободный текст',
                        ],
                    ]
                ],
            ]
        ], // Окончание последовательности С «Дополнительная общая информация»
        [ // Необязательная последовательность D «Детали расчетов по сделке частями»
            'name' => 'D',
            'type' => 'sequence',
            'status' => Entity::STATUS_OPTIONAL,
            'label' => 'Детали расчетов по сделке частями',
            'scheme' => [
                [
                    'name' => '15D',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'New sequence',
                    'service' => true,
                    'constant' => true,
                    'value' => '',
                    'number' => '38',
                ],
                [
                    'name' => '17A-58a',
                    'status' => Entity::STATUS_MANDATORY,
                    'type' => 'collection',
                    'scheme' => [
                        [
                            'name' => '17A',
                            'status' => Entity::STATUS_MANDATORY,
                            'label' => 'Признак покупки (продажи)',
                            'mask' => '1!a',
                            'number' => '39',
                            'scheme' => [
                                [
                                    'label' => 'Признак',
                                    'strict' => ['N', 'Y']
                                ],
                            ]
                        ],
                        [
                            'name' => '32B',
                            'status' => Entity::STATUS_MANDATORY,
                            'label' => 'Валюта и сумма процентов',
                            'mask' => '3!a~15d',
                            'number' => '40',
                            'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper32B',
                            'scheme' => [
                                [
                                    'label' => 'Валюта',
                                    'name' => 'currency',
                                    'strict' => $currency
                                ],
                                [
                                    'label' => 'Сумма',
                                    'name' => 'sum',
                                ],
                            ],
                        ],
                        [
                            'type' => 'choice',
                            'status' => Entity::STATUS_OPTIONAL,
                            'name' => '53a',
                            'label' => 'Агент-поставщик',
                            'scheme' => getChoiceScheme('53a', ['A', 'D', 'J']),
                            'number' => '41',
                        ],
                        [
                            'type' => 'choice',
                            'status' => Entity::STATUS_OPTIONAL,
                            'name' => '56a',
                            'label' => 'Intermediary Institution',
                            'scheme' => getChoiceScheme('56a', ['A', 'D', 'J']),
                            'number' => '42',
                        ],
                        [
                            'type' => 'choice',
                            'status' => Entity::STATUS_MANDATORY,
                            'name' => '57a',
                            'label' => 'Агент-получатель',
                            'scheme' => getChoiceScheme('57a', ['A', 'D', 'J']),
                            'number' => '43',
                        ],
                        [
                            'type' => 'choice',
                            'status' => Entity::STATUS_OPTIONAL,
                            'name' => '58a',
                            'label' => 'Организация-бенефициар',
                            'scheme' => getChoiceScheme('58a', ['A', 'D', 'J']),
                            'number' => '44',
                        ],
                    ]
                ], // Конец 17A-58a
                [
                    'name' => '16A',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'Количество частей при расчетах',
                    'mask' => '5n',
                    'number' => '45',
                    'scheme' => [
                        [
                            'label' => 'Количество',
                        ],
                    ]
                ],
            ]
        ], // Окончание последовательности D «Детали расчетов по сделке частями»
        [ // Необязательная последовательность E «Отчетная информация»
            'name' => 'E',
            'type' => 'sequence',
            'status' => Entity::STATUS_OPTIONAL,
            'label' => 'Отчетная информация',
            'scheme' => [
                [
                    'name' => '15E',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'New sequence',
                    'service' => true,
                    'constant' => true,
                    'value' => '',
                    'number' => '46',
                ],
                [
                    'name' => 'E1',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Отчетные стороны',
                    'type' => 'collection',
                    'scheme' => [
                        [
                            'name' => '22L',
                            'status' => Entity::STATUS_MANDATORY,
                            'label' => 'Отчетная юрисдикция',
                            'mask' => '35x',
                            'number' => '47',
                            'scheme' => [
                                [
                                    'label' => 'Отчетная юрисдикция',
                                /**
                                 * Подполе «Отчетная юрисдикция» должно содержать один из следующих кодов
                                 */
                                //								  'strict' => ['CFTC', 'ESMA', 'HKMA', 'OTHR', 'SEC']
                                ],
                            ],
                        ],
                        [
                            'type' => 'choice',
                            'name' => '91a',
                            'status' => Entity::STATUS_OPTIONAL,
                            'label' => 'Организация-бенефициар',
                            'scheme' => getChoiceScheme('91a', ['A', 'D', 'J']),
                            'number' => '48',
                        ],
                        [
                            'name' => 'E1a',
                            'status' => Entity::STATUS_OPTIONAL,
                            'label' => 'Уникальный идентификатор транзакции UTI',
                            'type' => 'collection',
                            'scheme' => [
                                [
                                    'name' => '22M',
                                    'status' => Entity::STATUS_MANDATORY,
                                    'label' => 'UTI область имен/код эмитента',
                                    'mask' => '10x',
                                    'number' => '49',
                                    'scheme' => [
                                        [
                                            'label' => 'Область имен',
                                        ],
                                    ],
                                ],
                                [
                                    'name' => '22N',
                                    'status' => Entity::STATUS_MANDATORY,
                                    'label' => 'Идентификатор транзакции',
                                    'mask' => '32x',
                                    'number' => '50',
                                    'scheme' => [
                                        [
                                            'label' => 'Идентификатор транзакции',
                                        ],
                                    ],
                                ],
                                [
                                    'name' => 'E1a1',
                                    'status' => Entity::STATUS_OPTIONAL,
                                    'label' => 'Отчетные стороны',
                                    'type' => 'collection',
                                    'scheme' => [
                                        [
                                            'name' => '22P',
                                            'status' => Entity::STATUS_MANDATORY,
                                            'label' => 'PUTI область имен/код эмитента',
                                            'mask' => '10x',
                                            'number' => '51',
                                            'scheme' => [
                                                [
                                                    'label' => 'область имен',
                                                ],
                                            ],
                                        ],
                                        [
                                            'name' => '22R',
                                            'status' => Entity::STATUS_MANDATORY,
                                            'label' => 'Прежний идентификатор транзакции',
                                            'mask' => '32x',
                                            'number' => '52',
                                            'scheme' => [
                                                [
                                                    'label' => 'Прежний идентификатор транзакции',
                                                ],
                                            ],
                                        ],
                                    ]
                                ] // Конец E1a1
                            ]
                        ] // Конец E1A
                    ]
                ], // Конец E1
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '81a',
                    'label' => 'Центральный контрагент – клиринговая палата',
                    'scheme' => getChoiceScheme('81a', ['A', 'D', 'J']),
                    'number' => '53',
                ],
                [
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'name' => '96a',
                    'label' => 'Сторона клиринга - исключение',
                    'scheme' => getChoiceScheme('96a', ['A', 'D', 'J']),
                    'number' => '54',
                ],
                [
                    'name' => '22S',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Идентификация клирингового брокера',
                    'mask' => '1!a~35x',
                    'number' => '55',
                    'scheme' => [
                        [
                            'label' => 'Индикатор стороны',
                            'strict' => ['C', 'P',]
                        ],
                        [
                            'label' => 'Идентификация',
                        ],
                    ],
                ],
                [
                    'name' => '22T',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Идентификация продукта',
                    'mask' => '35x',
                    'number' => '56',
                    'scheme' => [
                        [
                            'label' => 'Идентификация',
                        ],
                    ],
                ],
                [
                    'name' => '17E',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Индикатор клирингового лимита',
                    'mask' => '1!a',
                    'number' => '57',
                    'scheme' => [
                        [
                            'label' => 'Индикатор',
                            'strict' => ['N', 'Y',]
                        ],
                    ],
                ],
                [
                    'name' => '22U',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Идентификация продукта',
                    'mask' => '6a',
                    'number' => '58',
                    'scheme' => [
                        [
                            'label' => 'Идентификатор продукта',
                        ],
                    ],
                ],
                [
                    'name' => '17H',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Индикатор распределения',
                    'mask' => '1!a',
                    'number' => '59',
                    'scheme' => [
                        [
                            'label' => 'Индикатор',
                            'strict' => ['A', 'P', 'U',]
                        ],
                    ],
                ],
                [
                    'name' => '17P',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Индикатор залогообразования',
                    'mask' => '1!a',
                    'number' => '60',
                    'scheme' => [
                        [
                            'label' => 'Индикатор',
                            'strict' => ['F', 'P', 'U', 'O',]
                        ],
                    ],
                ],
                [
                    'name' => '22V',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Исполнение - место',
                    'mask' => '35x',
                    'number' => '61',
                    'scheme' => [
                        [
                            'label' => 'Исполнение - место',
                        ],
                    ],
                ],
                [
                    'name' => '98D',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Исполнение - время',
                    'mask' => '8!n~6!n~[,3n]~[/[N]2!n[2!n]]',
                    'number' => '62',
                    'scheme' => [
                        [
                            'label' => 'Дата',
                            'name' => 'date'
                        ],
                        [
                            'label' => 'Время',
                        ],
                        [
                            'label' => 'Десятичные знаки',
                        ],
                        [
                            'label' => 'Индикатор UTC',
                        ],
                    ],
                ],
                [
                    'name' => '17W',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Нестандартная метка',
                    'mask' => '1!a',
                    'number' => '63',
                    'scheme' => [
                        [
                            'label' => 'Метка',
                            'strict' => ['Y',]
                        ],
                    ],
                ],
                [
                    'name' => '22W',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Идентификация связи свопа',
                    'mask' => '42x',
                    'number' => '64',
                    'scheme' => [
                        [
                            'label' => 'Идентификация',
                        ],
                    ],
                ],
                [
                    'name' => '77A',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Дополнительная отчетная информация',
                    'mask' => '20*35x',
                    'number' => '64',
                    'scheme' => [
                        [
                            'label' => 'Свободное поле',
                        ],
                    ],
                ],
            ]
        ], // Окончание Необязательная последовательность E «Отчетная информация»
    ],
    'networkRules' => [
        'D70' => [
            'message' => "В последовательности А присутствие поля 21 зависит от значения поля 22А и определяется следующим образом:"
            ."\nЕсли поле 22А равно значению AMND, то поле 21 Обязательное"
            ."\nЕсли поле 22А равно значению CANC, то поле 21 Обязательное"
            ."\nЕсли поле 22А равно значению DUPL, то поле 21 Необязательное"
            ."\nЕсли поле 22А равно значению EXOP, то поле 21 Необязательное"
            ."\nЕсли поле 22А равно значению NEWT, то поле 21 Необязательное",
        ],
        'D74' => [
            'message' => 'Использование полей 88а и 71F в последовательности C - а тем самым использование самой последовательности C - зависит от значения поля 94А',
        ],
        'D76' => [
            'message' => 'Использование последовательности D зависит от значения поля 17U в последовательности А',
        ],
        'C58' => [
            'message' => 'Если в последовательности А присутствует поле 77D, и если первые 6 знаков первой строки этого поля имеют значение /VALD/, то следующие 8 знаков должны содержать определение даты, выраженное в формате YYYYMMDD (год, месяц, день), за которым должен следовать признак окончания строки CrLf',
        ],
        'C59' => [
            'message' => "Если в последовательности А присутствует поле 77D, то:"
            ."\n если первые шесть знаков первой строки этого поля имеют значение /VALD/, то вторая строка должна быть представлена и должна содержать код /SETC/, за которым следует указывать код валюты (ISO 4217) и признак окончания строки (/SWTC/currencyCrLf)"
            ."\n если первые шесть знаков второй строки этого поля имеют значение /SETC/, то первые шесть знаков первой строки должны содержать код /VALD/"
            ."\n код /SETC/ используется только в первых шести знаках второй строки"
            ."\n если первые шесть знаков третьей строки этого поля имеют значение /SRCE/, то первые шесть знаков второй строки должны содержать код /SETC/"
            ."\n код / SRCE / используется только в первых шести знаках третьей строки"
        ,
        ],
        'C32' => [
            'message' => 'Во всех необязательных последовательностях, если существует последовательность, поля со статусом М должны присутствовать, другие не используются',
        ],
        'C08' => [
            'message' => "В полях, указанных ниже, коды XAU, XAG, XPD и XPT не используются, поскольку эти коды относятся к товарам, для которых должна использоваться 6 категоря сообщений"
            ."\n Подпоследовательность B1 Купленная Сумма, поле 32B Валюта, Сумма."
            ."\n Подпоследовательность B2 Проданная Сумма, поле 33B Валюта, Сумма."
            ."\n Последовательность С Дополнительная общая информация, поле 71F Комиссия брокера."
            ."\n Последовательность D Детали расчетов по сделке частями, поле 32B Валюта, Сумма."
        ,
        ],
        'C98' => [
            'message' => 'Если в последовательности E присутствует поле 15E, хотя бы одно из других полей последовательности E должно присутствовать',
        ],
    ],
];
