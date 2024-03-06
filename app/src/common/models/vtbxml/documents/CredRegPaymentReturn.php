<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\DateTimeField;
use common\models\vtbxml\documents\fields\DoubleField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class CredRegPaymentReturn
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class CredRegPaymentReturn extends BSDocument
{
    const TYPE = 'CredRegPaymentReturn';
    const TYPE_ID = null;
    const VERSIONS = [5];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        5 => ['PAYMENTDEBTDATE', 'PAYMENTDEBTAMOUNT', 'PAYMENTPERCENTDATE', 'PAYMENTPERCENTAMOUNT', 'SPECIALCONDITIONS'],
    ];

    /** @var \DateTime Дата платежа в счет основного долга **/
    public $PAYMENTDEBTDATE;

    /** @var float Сумма платежа в счет основного долга **/
    public $PAYMENTDEBTAMOUNT;

    /** @var \DateTime Дата платежа в счет процентных платежей **/
    public $PAYMENTPERCENTDATE;

    /** @var float Сумма платежа в счет процентных платежей **/
    public $PAYMENTPERCENTAMOUNT;

    /** @var string Особые условия **/
    public $SPECIALCONDITIONS;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'PAYMENTDEBTDATE' => new DateTimeField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата платежа в счет основного долга',
                    'versions'    => [5],
                ]),
                'PAYMENTDEBTAMOUNT' => new DoubleField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма платежа в счет основного долга',
                    'versions'    => [5],
                ]),
                'PAYMENTPERCENTDATE' => new DateTimeField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата платежа в счет процентных платежей',
                    'versions'    => [5],
                ]),
                'PAYMENTPERCENTAMOUNT' => new DoubleField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма платежа в счет процентных платежей',
                    'versions'    => [5],
                ]),
                'SPECIALCONDITIONS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Особые условия',
                    'length'      => 255,
                    'versions'    => [5],
                ]),
            ]
        );
    }
}
