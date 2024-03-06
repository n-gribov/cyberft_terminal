<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\StringField;

/**
 * Class CredRegNonresidentInfo
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class CredRegNonresidentInfo extends BSDocument
{
    const TYPE = 'CredRegNonresidentInfo';
    const TYPE_ID = null;
    const VERSIONS = [5];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        5 => ['NAME', 'COUNTRY', 'COUNTRYCODE'],
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
                    'versions'    => [5],
                ]),
                'COUNTRY' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Наименование страны нерезидента',
                    'length'      => 50,
                    'versions'    => [5],
                ]),
                'COUNTRYCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код страны нерезидента',
                    'length'      => 3,
                    'versions'    => [5],
                ]),
            ]
        );
    }
}
