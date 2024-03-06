<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\DateTimeField;
use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class ContractChangePSRow
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class ContractChangePSRow extends BSDocument
{
    const TYPE = 'ContractChangePSRow';
    const TYPE_ID = null;
    const VERSIONS = [1];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        1 => ['ID', 'PSNUMBER', 'PSDATE'],
    ];

    /** @var integer ID записи о ПС **/
    public $ID;

    /** @var string Номер ПС **/
    public $PSNUMBER;

    /** @var \DateTime Дата ПС **/
    public $PSDATE;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'ID' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'ID записи о ПС',
                    'versions'    => [1],
                ]),
                'PSNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер ПС',
                    'length'      => 25,
                    'versions'    => [1],
                ]),
                'PSDATE' => new DateTimeField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата ПС',
                    'versions'    => [1],
                ]),
            ]
        );
    }
}
