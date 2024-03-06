<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\StringField;

/**
 * Class ContractRegNonresidentInfo
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class ContractRegNonresidentInfo extends BSDocument
{
    const TYPE = 'ContractRegNonresidentInfo';
    const TYPE_ID = null;
    const VERSIONS = [1];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        1 => ['NAME', 'COUNTRY', 'COUNTRYCODE'],
    ];

    /** @var string Наименование нерезидента **/
    public $NAME;

    /** @var string Наименование страны нерезидента **/
    public $COUNTRY;

    /** @var string Код страны нерезидента **/
    public $COUNTRYCODE;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'NAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Наименование нерезидента',
                    'length'      => 255,
                    'versions'    => [1],
                ]),
                'COUNTRY' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Наименование страны нерезидента',
                    'length'      => 50,
                    'versions'    => [1],
                ]),
                'COUNTRYCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код страны нерезидента',
                    'length'      => 3,
                    'versions'    => [1],
                ]),
            ]
        );
    }
}
