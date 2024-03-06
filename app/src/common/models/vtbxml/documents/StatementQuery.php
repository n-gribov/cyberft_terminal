<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\DateField;
use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class StatementQuery
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class StatementQuery extends BSDocument
{
    const TYPE = 'StatementQuery';
    const TYPE_ID = 11;
    const VERSIONS = [3];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        3 => ['DOCUMENTDATE', 'DOCUMENTNUMBER', 'CUSTID', 'SENDEROFFICIALS', 'STATEMENTTYPE', 'ACCOUNT', 'DATEFROM', 'DATETO', 'KBOPID'],
    ];

    /** @var \DateTime Дата документа **/
    public $DOCUMENTDATE;

    /** @var string Номер документа **/
    public $DOCUMENTNUMBER;

    /** @var integer ID организации **/
    public $CUSTID;

    /** @var string Ответственный исполнитель **/
    public $SENDEROFFICIALS;

    /** @var integer Тип запрашиваемой выписки 0 – выписка 1 – справка **/
    public $STATEMENTTYPE;

    /** @var string Счет по которому запрашивается выписка **/
    public $ACCOUNT;

    /** @var \DateTime Диапазон дат (начало включительно) **/
    public $DATEFROM;

    /** @var \DateTime Диапазон дат (окончание включительно) **/
    public $DATETO;

    /** @var integer ID подразделения **/
    public $KBOPID;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'DOCUMENTDATE' => new DateField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Дата документа',
                    'versions'    => [3],
                ]),
                'DOCUMENTNUMBER' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Номер документа',
                    'length'      => 15,
                    'versions'    => [3],
                ]),
                'CUSTID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ID организации',
                    'versions'    => [3],
                ]),
                'SENDEROFFICIALS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Ответственный исполнитель',
                    'length'      => 40,
                    'versions'    => [3],
                ]),
                'STATEMENTTYPE' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Тип запрашиваемой выписки 0 – выписка 1 – справка',
                    'versions'    => [3],
                ]),
                'ACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Счет по которому запрашивается выписка',
                    'length'      => 25,
                    'versions'    => [3],
                ]),
                'DATEFROM' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Диапазон дат (начало включительно)',
                    'versions'    => [3],
                ]),
                'DATETO' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Диапазон дат (окончание включительно)',
                    'versions'    => [3],
                ]),
                'KBOPID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ID подразделения',
                    'versions'    => [3],
                ]),
            ]
        );
    }
}
