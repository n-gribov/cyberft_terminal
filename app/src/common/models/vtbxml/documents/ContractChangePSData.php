<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class ContractChangePSData
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class ContractChangePSData extends BSDocument
{
    const TYPE = 'ContractChangePSData';
    const TYPE_ID = null;
    const VERSIONS = [1];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        1 => ['ID', 'PARTNUM', 'RENEWDATA'],
    ];

    /** @var integer Ссылка на ID номера записи о ПС **/
    public $ID;

    /** @var string Номер раздела **/
    public $PARTNUM;

    /** @var string Содержание изменений **/
    public $RENEWDATA;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'ID' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Ссылка на ID номера записи о ПС',
                    'versions'    => [1],
                ]),
                'PARTNUM' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер раздела',
                    'length'      => 10,
                    'versions'    => [1],
                ]),
                'RENEWDATA' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Содержание изменений',
                    'length'      => 255,
                    'versions'    => [1],
                ]),
            ]
        );
    }
}
