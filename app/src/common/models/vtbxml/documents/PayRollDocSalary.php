<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\MoneyField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class PayRollDocSalary
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class PayRollDocSalary extends BSDocument
{
    const TYPE = 'PayRollDocSalary';
    const TYPE_ID = null;
    const VERSIONS = [5];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        5 => ['NOTICENUMBER', 'EMPLOYEENAME', 'EMPLOYEEINN', 'EMPLOYEEACCOUNT', 'EMPLOYEEBANKBIC', 'EMPLOYEEAMOUNT', 'DESCRIPTION'],
    ];

    /** @var integer Порядковый номер  Целочисленное, нумерация без разрывов. **/
    public $NOTICENUMBER;

    /** @var string Ф.И.О. сотрудника **/
    public $EMPLOYEENAME;

    /** @var string ИНН сотрудника **/
    public $EMPLOYEEINN;

    /** @var string Счет, на который производится перечисление средств **/
    public $EMPLOYEEACCOUNT;

    /** @var string БИК банка, в котором открыт счет сотрудника **/
    public $EMPLOYEEBANKBIC;

    /** @var float Сумма, подлежащая перечислению **/
    public $EMPLOYEEAMOUNT;

    /** @var string Комментарий **/
    public $DESCRIPTION;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'NOTICENUMBER' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Порядковый номер  Целочисленное, нумерация без разрывов.',
                    'versions'    => [5],
                ]),
                'EMPLOYEENAME' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Ф.И.О. сотрудника',
                    'length'      => 255,
                    'versions'    => [5],
                ]),
                'EMPLOYEEINN' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'ИНН сотрудника',
                    'length'      => 12,
                    'versions'    => [5],
                ]),
                'EMPLOYEEACCOUNT' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Счет, на который производится перечисление средств',
                    'length'      => 25,
                    'versions'    => [5],
                ]),
                'EMPLOYEEBANKBIC' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'БИК банка, в котором открыт счет сотрудника',
                    'length'      => 9,
                    'versions'    => [5],
                ]),
                'EMPLOYEEAMOUNT' => new MoneyField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Сумма, подлежащая перечислению',
                    'versions'    => [5],
                ]),
                'DESCRIPTION' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Комментарий',
                    'length'      => 255,
                    'versions'    => [5],
                ]),
            ]
        );
    }
}
