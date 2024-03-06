<?php

namespace addons\swiftfin\config\mt3xx;

use addons\swiftfin\models\documents\mt\mtUniversal\Entity;
use yii\helpers\Url;

function getChoiceScheme($tag, $keys) {
    $options = [
        'A' => [
            'name' => 'A',
            'mask' => "[/1!a]~[/34x]~" . Entity::INLINE_BREAK . "4!a2!a2!c[3!c]",
            'wrapperClass' => 'addons\swiftfin\models\documents\mt\tagwrapper\TagWrapper5xA',
            'scheme' => [
                [
                    'label' => 'Опция идентификации стороны',
                ],
                [
                    'label' => 'Идентификация стороны',
                    'name' => 'account'
                ],
                [
                    'label' => 'Идентификационный код',
                    'type' => 'select2',
                    'dataUrl' => Url::toRoute(['/swiftfin/dict-bank/list'])
                ],
            ]
        ],
        'B' => [
            'name' => 'B',
            'mask' => "[/1!a]~[/34x]~" . Entity::INLINE_BREAK . "[35x]",
            'scheme' => [
                [
                    'label' => 'Опция идентификации стороны',
                ],
                [
                    'label' => 'Идентификация стороны',
                    'name' => 'account'
                ],
                [
                    'label' => 'Местонахождение',
                ],
            ]
        ],
        'D' => [
            'name' => 'D',
            'mask' => "[/1!a]~[/34x]~" . Entity::INLINE_BREAK . "4*35x",
            'scheme' => [
                [
                    'label' => 'Опция идентификации стороны',
                ],
                [
                    'label' => 'Идентификация стороны',
                    'name' => 'account'
                ],
                [
                    'label' => 'Наименование и адрес',
                ]
            ]
        ],
        'J' => [
            'name' => 'J',
            'mask' => "5*40x",
            'scheme' => [
                [
                    'label' => 'Определение стороны',
                ],
            ]
        ]
    ];
    $tagScheme = [
        '53a' => ['A', 'D', 'J'],
        '56a' => ['A', 'D', 'J'],
        '57a' => ['A', 'D', 'J'],
        '58a' => ['A', 'D', 'J'],
        '81a' => ['A', 'D', 'J'],
        '82a' => ['A', 'D', 'J'],
        '83a' => ['A', 'D', 'J'],
        '84a' => ['A', 'B', 'D', 'J'],
        '85a' => ['A', 'B', 'D', 'J'],
        '86a' => ['A', 'D', 'J'],
        '87a' => ['A', 'D', 'J'],
        '88a' => ['A', 'D', 'J'],
        '91a' => ['A', 'D', 'J'],
        '96a' => ['A', 'D', 'J'],
    ];

    if (!isset($tagScheme[$tag])) {
        $scheme = [
            $keys[0] => [
                'name' => $keys[0],
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

    /**
     * Производим пересечение запрошенных опций и реально доступных для заданного поля
     * после чего проивзодим пересечение уже самих опций с валидным набором
     */
    return array_intersect_key(
        $options,
        array_fill_keys(
            array_intersect($tagScheme[$tag], $keys), null
        )
    );
}
