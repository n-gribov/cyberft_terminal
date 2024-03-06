<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\DateField;
use common\models\vtbxml\documents\fields\MoneyField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class CurrConversionGroundReceipt
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class CurrConversionGroundReceipt extends BSDocument
{
    const TYPE = 'CurrConversionGroundReceipt';
    const TYPE_ID = null;
    const VERSIONS = [5, 6];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        5 => ['AMOUNT', 'DESCRIPTION', 'DOCUMENTDATE', 'DOCUMENTNUMBER', 'DOCUMENTTYPE'],
        6 => ['AMOUNT', 'DESCRIPTION', 'DOCUMENTDATE', 'DOCUMENTNUMBER', 'DOCUMENTTYPE', 'DOCTYPECODE'],
    ];

    /** @var float Сумма представленного документа **/
    public $AMOUNT;

    /** @var string Примечание **/
    public $DESCRIPTION;

    /** @var \DateTime Дата представленного документа **/
    public $DOCUMENTDATE;

    /** @var string Номер представленного документа **/
    public $DOCUMENTNUMBER;

    /** @var string Тип представленного документа **/
    public $DOCUMENTTYPE;

    /** @var string Код типа обосновывающего документа **/
    public $DOCTYPECODE;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'AMOUNT' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма представленного документа',
                    'versions'    => [5, 6],
                ]),
                'DESCRIPTION' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Примечание',
                    'length'      => 255,
                    'versions'    => [5, 6],
                ]),
                'DOCUMENTDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата представленного документа',
                    'versions'    => [5, 6],
                ]),
                'DOCUMENTNUMBER' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Номер представленного документа',
                    'length'      => 50,
                    'versions'    => [5, 6],
                ]),
                'DOCUMENTTYPE' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Тип представленного документа',
                    'length'      => 255,
                    'versions'    => [5, 6],
                ]),
                'DOCTYPECODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код типа обосновывающего документа',
                    'length'      => 10,
                    'versions'    => [6],
                ]),
            ]
        );
    }
}
