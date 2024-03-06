<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\DateTimeField;
use common\models\vtbxml\documents\fields\DoubleField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class CredRegCredTranche
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class CredRegCredTranche extends BSDocument
{
    const TYPE = 'CredRegCredTranche';
    const TYPE_ID = null;
    const VERSIONS = [5];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        5 => ['TRANCHEAMOUNT', 'TRANCHEPAYMENTPERIODCODE', 'RECEIPTDATE'],
    ];

    /** @var float Сумма транша **/
    public $TRANCHEAMOUNT;

    /** @var string Код срока привлечения транша **/
    public $TRANCHEPAYMENTPERIODCODE;

    /** @var \DateTime Ожидаемая дата поступления **/
    public $RECEIPTDATE;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'TRANCHEAMOUNT' => new DoubleField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма транша',
                    'versions'    => [5],
                ]),
                'TRANCHEPAYMENTPERIODCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код срока привлечения транша',
                    'length'      => 5,
                    'versions'    => [5],
                ]),
                'RECEIPTDATE' => new DateTimeField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Ожидаемая дата поступления',
                    'versions'    => [5],
                ]),
            ]
        );
    }
}
