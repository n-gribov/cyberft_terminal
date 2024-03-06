<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\DateTimeField;
use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class ContractChangePSDoc
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class ContractChangePSDoc extends BSDocument
{
    const TYPE = 'ContractChangePSDoc';
    const TYPE_ID = null;
    const VERSIONS = [1];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        1 => ['ID', 'DOCTYPE', 'DOCNUM', 'DOCDATE', 'REMARK'],
    ];

    /** @var integer Ссылка на ID номера записи о ПС **/
    public $ID;

    /** @var string Вид документа **/
    public $DOCTYPE;

    /** @var string Номер документа-основания **/
    public $DOCNUM;

    /** @var \DateTime Дата документа-основания **/
    public $DOCDATE;

    /** @var string Примечание **/
    public $REMARK;

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
                'DOCTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Вид документа',
                    'length'      => 255,
                    'versions'    => [1],
                ]),
                'DOCNUM' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер документа-основания',
                    'length'      => 50,
                    'versions'    => [1],
                ]),
                'DOCDATE' => new DateTimeField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата документа-основания',
                    'versions'    => [1],
                ]),
                'REMARK' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Примечание',
                    'length'      => 255,
                    'versions'    => [1],
                ]),
            ]
        );
    }
}
