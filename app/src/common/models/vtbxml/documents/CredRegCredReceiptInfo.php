<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\DoubleField;
use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class CredRegCredReceiptInfo
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class CredRegCredReceiptInfo extends BSDocument
{
    const TYPE = 'CredRegCredReceiptInfo';
    const TYPE_ID = null;
    const VERSIONS = [5];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        5 => ['BENEFICIAR', 'BENEFICIARCOUNTRYCODE', 'ISCREDAMOUNT', 'CREDAMOUNT', 'CREDPERCENT'],
    ];

    /** @var string Наименование иностранного контрагента **/
    public $BENEFICIAR;

    /** @var string Код страны иностранного контрагента **/
    public $BENEFICIARCOUNTRYCODE;

    /** @var integer Признак определения суммы кредита **/
    public $ISCREDAMOUNT;

    /** @var float Сумма кредитного договора **/
    public $CREDAMOUNT;

    /** @var float Доля в общей сумме кредита, % **/
    public $CREDPERCENT;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'BENEFICIAR' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Наименование иностранного контрагента',
                    'length'      => 255,
                    'versions'    => [5],
                ]),
                'BENEFICIARCOUNTRYCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код страны иностранного контрагента',
                    'length'      => 3,
                    'versions'    => [5],
                ]),
                'ISCREDAMOUNT' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Признак определения суммы кредита',
                    'versions'    => [5],
                ]),
                'CREDAMOUNT' => new DoubleField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма кредитного договора',
                    'versions'    => [5],
                ]),
                'CREDPERCENT' => new DoubleField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Доля в общей сумме кредита, %',
                    'versions'    => [5],
                ]),
            ]
        );
    }
}
