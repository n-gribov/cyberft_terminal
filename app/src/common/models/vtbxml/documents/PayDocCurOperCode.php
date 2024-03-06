<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\DoubleField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class PayDocCurOperCode
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class PayDocCurOperCode extends BSDocument
{
    const TYPE = 'PayDocCurOperCode';
    const TYPE_ID = null;
    const VERSIONS = [5, 6, 8, 9, 632, 633];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        5 => ['OPERCODE', 'OPERCODEDESCRIPTION', 'AMOUNT', 'CURRCODE'],
        6 => ['OPERCODE', 'OPERCODEDESCRIPTION', 'AMOUNT', 'CURRCODE'],
        8 => ['AMOUNT', 'CURRCODE', 'OPERCODE', 'OPERCODEDESCRIPTION'],
        9 => ['AMOUNT', 'CURRCODE', 'OPERCODE', 'OPERCODEDESCRIPTION'],
        632 => ['OPERCODE', 'OPERCODEDESCRIPTION', 'AMOUNT', 'CURRCODE'],
        633 => ['OPERCODE', 'OPERCODEDESCRIPTION', 'AMOUNT', 'CURRCODE'],
    ];

    /** @var string Код вида операции **/
    public $OPERCODE;

    /** @var string Описание вида валютной операции **/
    public $OPERCODEDESCRIPTION;

    /** @var float Сумма по виду операции **/
    public $AMOUNT;

    /** @var string Код валюты суммы по виду операции **/
    public $CURRCODE;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'OPERCODE' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Код вида операции',
                    'length'      => 10,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'OPERCODEDESCRIPTION' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Описание вида валютной операции',
                    'length'      => 255,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'AMOUNT' => new DoubleField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма по виду операции',
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'CURRCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код валюты суммы по виду операции',
                    'length'      => 3,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
            ]
        );
    }
}
