<?php

namespace addons\swiftfin\config\mt1xx;

include(__DIR__ . '/../base/currency.php');
include_once(__DIR__ . '/choiceScheme.php');

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

return [
    'class' => 'addons\swiftfin\models\documents\mt\MtUniversalDocument',
    'view' => '/wizard/mtFields/mtUniversal.php',
    'formable' => true,
    'type' => '110',
    'scheme' => [
        [
            'name' => '20',
            'status' => Entity::STATUS_MANDATORY,
            'label' => 'Sender\'s reference',
            'mask' => '16x',
            'number' => '1',
        ],
        [
            'name' => '53a',
            'type' => 'choice',
            'status' => Entity::STATUS_OPTIONAL,
            'label' => 'Sender\'s Correspondent',
            'scheme' => getChoiceScheme('53a', ['A', 'B', 'D']),
            'number' => '2',
        ],
        [
            'name' => '54a',
            'type' => 'choice',
            'status' => Entity::STATUS_OPTIONAL,
            'label' => 'Receiver\'s Correspondent',
            'scheme' => getChoiceScheme('54a', ['A', 'B', 'D']),
            'number' => '3',
        ],
        [
            'name' => '72',
            'status' => Entity::STATUS_OPTIONAL,
            'label' => 'Sender to Receiver Information',
            'mask' => '6*35x',
            'number' => '4',
            'scheme' => [
                [
                    'label' => 'Свободный текст',
                    'name' => 'value'
                ],
            ],
        ],
        [
            'type' => 'collection',
            'name' => '21-59',
            'disableLabel' => true,
            'scheme' => [
                [
                    'name' => '21',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'Номер чека',
                    'mask' => '16x',
                    'number' => '5',
                    'scheme' => [
                        [
                            'label' => 'Номер чека',
                            'name' => 'idCheque'
                        ],
                    ],
                ],
                [
                    'name' => '30',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'Дата выпуска',
                    'mask' => '6!n',
                    'number' => '6',
                    'scheme' => [
                        [
                            'label' => 'Дата выпуска',
                            'name' => 'date'
                        ],
                    ],
                ],
                [
                    'name' => '32a',
                    'type' => 'choice',
                    'status' => Entity::STATUS_MANDATORY,
                    'label' => 'Сумма',
                    'scheme' => getChoiceScheme('32a', ['A', 'B']),
                    'number' => '7',
                ],
                [
                    'name' => '52a',
                    'type' => 'choice',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Банк-трассант',
                    'scheme' => getChoiceScheme('52a', ['A', 'B', 'D']),
                    'number' => '8',
                ],
                [
                    'name' => '59',
                    'status' => Entity::STATUS_OPTIONAL,
                    'label' => 'Получатель средств',
                    'mask' => '34x',
                    'number' => '9',
                    'scheme' => [
                        [
                            'label' => 'Получатель средств',
                            'name' => 'receiver'
                        ],
                    ],
                ],
            ],
        ],
    ],
];
