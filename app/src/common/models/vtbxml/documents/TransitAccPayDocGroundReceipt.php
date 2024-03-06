<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\DateTimeField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class TransitAccPayDocGroundReceipt
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class TransitAccPayDocGroundReceipt extends BSDocument
{
    const TYPE = 'TransitAccPayDocGroundReceipt';
    const TYPE_ID = null;
    const VERSIONS = [8];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        8 => ['DOCUMENTTYPE', 'DOCUMENTNUMBER', 'DOCUMENTDATE', 'DESCRIPTION', 'DOCTYPECODE'],
    ];

    /** @var string Тип обосновывающего документа **/
    public $DOCUMENTTYPE;

    /** @var string Номер обосновывающего документа **/
    public $DOCUMENTNUMBER;

    /** @var \DateTime Дата обосновывающего документа **/
    public $DOCUMENTDATE;

    /** @var string Примечание к обосновывающему документу **/
    public $DESCRIPTION;

    /** @var string Код типа обосновывающего документа **/
    public $DOCTYPECODE;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'DOCUMENTTYPE' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Тип обосновывающего документа',
                    'length'      => 255,
                    'versions'    => [8],
                ]),
                'DOCUMENTNUMBER' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Номер обосновывающего документа',
                    'length'      => 50,
                    'versions'    => [8],
                ]),
                'DOCUMENTDATE' => new DateTimeField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Дата обосновывающего документа',
                    'versions'    => [8],
                ]),
                'DESCRIPTION' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Примечание к обосновывающему документу',
                    'length'      => 255,
                    'versions'    => [8],
                ]),
                'DOCTYPECODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код типа обосновывающего документа',
                    'length'      => 10,
                    'versions'    => [8],
                ]),
            ]
        );
    }
}
