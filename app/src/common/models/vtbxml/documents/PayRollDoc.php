<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\BlobTableField;
use common\models\vtbxml\documents\fields\DateField;
use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\MoneyField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class PayRollDoc
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class PayRollDoc extends BSDocument
{
    const TYPE = 'PayRollDoc';
    const TYPE_ID = 51;
    const VERSIONS = [5];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        5 => ['CUSTOMERBANKPLACETYPE', 'CUSTOMERBANKPLACE', 'CUSTOMERBANKBIC', 'CUSTOMERPROPERTYTYPE', 'CUSTOMERNAME', 'CUSTOMERINN', 'CUSTOMEROKPO', 'PHONEOFFICIALS', 'SENDEROFFICIALS', 'AMOUNTTOTAL', 'CURRCODE', 'ACCOUNTDEBET', 'ACCOUNTCREDIT', 'PERIODMONTH', 'PERIODYEAR', 'STAFFTOTAL', 'PLATPORNUMBER', 'PLATPORDATE', 'PLATPORGOAL', 'DOCUMENTDATE', 'DOCUMENTNUMBER', 'BANKAGREEMENT', 'CUSTID', 'KBOPID', 'CUSTOMERBANKNAME', 'ACCOUNTDEBETBIC', 'SALARYBLOB', 'ACCOUNTDEBETBANKFLAG', 'ACCOUNTDEBETBANKNAME', 'PLATPORAMOUNT'],
    ];

    /** @var string Не заполняется **/
    public $CUSTOMERBANKPLACETYPE;

    /** @var string Не заполняется **/
    public $CUSTOMERBANKPLACE;

    /** @var string БИК банка плательщика **/
    public $CUSTOMERBANKBIC;

    /** @var string Тип собственности организации-плательщика **/
    public $CUSTOMERPROPERTYTYPE;

    /** @var string Краткое наименование организации-плательщика **/
    public $CUSTOMERNAME;

    /** @var string ИНН организации-плательщика **/
    public $CUSTOMERINN;

    /** @var string ОКПО организации-плательщика **/
    public $CUSTOMEROKPO;

    /** @var string Телефон исполнителя **/
    public $PHONEOFFICIALS;

    /** @var string Ф.И.О. исполнителя **/
    public $SENDEROFFICIALS;

    /** @var float Общая сумма **/
    public $AMOUNTTOTAL;

    /** @var string Цифровой код валюты **/
    public $CURRCODE;

    /** @var string Счет списания средств **/
    public $ACCOUNTDEBET;

    /** @var string Счет заработной платы (внутренний счет банка) **/
    public $ACCOUNTCREDIT;

    /** @var integer Месяц отчетного периода (значение от 1 до 12) **/
    public $PERIODMONTH;

    /** @var integer Год отчетного периода **/
    public $PERIODYEAR;

    /** @var integer Итоговое количество сотрудников, указанных в SALARYBLOB **/
    public $STAFFTOTAL;

    /** @var string Номер платежного поручения **/
    public $PLATPORNUMBER;

    /** @var \DateTime Дата платежного поручения **/
    public $PLATPORDATE;

    /** @var string Назначение платежа **/
    public $PLATPORGOAL;

    /** @var \DateTime Дата Документа **/
    public $DOCUMENTDATE;

    /** @var string Номер Документа **/
    public $DOCUMENTNUMBER;

    /** @var string Не заполняется **/
    public $BANKAGREEMENT;

    /** @var integer ID организации **/
    public $CUSTID;

    /** @var integer ID подразделения банка **/
    public $KBOPID;

    /** @var string Сокращенное наименование банка плательщика **/
    public $CUSTOMERBANKNAME;

    /** @var string БИК банка счета списания средств **/
    public $ACCOUNTDEBETBIC;

    /** @var PayRollDocSalary[] Информация о начислениях заработной платы (Ф.И.О. сотрудника, счет зачисления, сумма) (требуется наличие как минимум одной записи) **/
    public $SALARYBLOB = [];

    /** @var integer Признак принадлежности счета списания: 0 - открыт в нашем банке; 1 - открыт в другой кредитной организации **/
    public $ACCOUNTDEBETBANKFLAG;

    /** @var string Наименование банка, в котором открыт счет организации **/
    public $ACCOUNTDEBETBANKNAME;

    /** @var float Сумма платежного поручения **/
    public $PLATPORAMOUNT;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'CUSTOMERBANKPLACETYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Не заполняется',
                    'length'      => 15,
                    'versions'    => [5],
                ]),
                'CUSTOMERBANKPLACE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Не заполняется',
                    'length'      => 25,
                    'versions'    => [5],
                ]),
                'CUSTOMERBANKBIC' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'БИК банка плательщика',
                    'length'      => 9,
                    'versions'    => [5],
                ]),
                'CUSTOMERPROPERTYTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип собственности организации-плательщика',
                    'length'      => 10,
                    'versions'    => [5],
                ]),
                'CUSTOMERNAME' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Краткое наименование организации-плательщика',
                    'length'      => 160,
                    'versions'    => [5],
                ]),
                'CUSTOMERINN' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'ИНН организации-плательщика',
                    'length'      => 14,
                    'versions'    => [5],
                ]),
                'CUSTOMEROKPO' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'ОКПО организации-плательщика',
                    'length'      => 10,
                    'versions'    => [5],
                ]),
                'PHONEOFFICIALS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Телефон исполнителя',
                    'length'      => 20,
                    'versions'    => [5],
                ]),
                'SENDEROFFICIALS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Ф.И.О. исполнителя',
                    'length'      => 40,
                    'versions'    => [5],
                ]),
                'AMOUNTTOTAL' => new MoneyField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Общая сумма',
                    'versions'    => [5],
                ]),
                'CURRCODE' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Цифровой код валюты',
                    'length'      => 3,
                    'versions'    => [5],
                ]),
                'ACCOUNTDEBET' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Счет списания средств',
                    'length'      => 25,
                    'versions'    => [5],
                ]),
                'ACCOUNTCREDIT' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Счет заработной платы (внутренний счет банка)',
                    'length'      => 25,
                    'versions'    => [5],
                ]),
                'PERIODMONTH' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Месяц отчетного периода (значение от 1 до 12)',
                    'versions'    => [5],
                ]),
                'PERIODYEAR' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Год отчетного периода',
                    'versions'    => [5],
                ]),
                'STAFFTOTAL' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Итоговое количество сотрудников, указанных в SALARYBLOB',
                    'versions'    => [5],
                ]),
                'PLATPORNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер платежного поручения',
                    'length'      => 15,
                    'versions'    => [5],
                ]),
                'PLATPORDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата платежного поручения',
                    'versions'    => [5],
                ]),
                'PLATPORGOAL' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Назначение платежа',
                    'length'      => 255,
                    'versions'    => [5],
                ]),
                'DOCUMENTDATE' => new DateField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Дата Документа',
                    'versions'    => [5],
                ]),
                'DOCUMENTNUMBER' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Номер Документа',
                    'length'      => 15,
                    'versions'    => [5],
                ]),
                'BANKAGREEMENT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Не заполняется',
                    'length'      => null,
                    'versions'    => [5],
                ]),
                'CUSTID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ID организации',
                    'versions'    => [5],
                ]),
                'KBOPID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ID подразделения банка',
                    'versions'    => [5],
                ]),
                'CUSTOMERBANKNAME' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Сокращенное наименование банка плательщика',
                    'length'      => 80,
                    'versions'    => [5],
                ]),
                'ACCOUNTDEBETBIC' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'БИК банка счета списания средств',
                    'length'      => 9,
                    'versions'    => [5],
                ]),
                'SALARYBLOB' => new BlobTableField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Информация о начислениях заработной платы (Ф.И.О. сотрудника, счет зачисления, сумма) (требуется наличие как минимум одной записи)',
                    'recordType'  => 'PayRollDocSalary',
                    'versions'    => [5],
                ]),
                'ACCOUNTDEBETBANKFLAG' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Признак принадлежности счета списания: 0 - открыт в нашем банке; 1 - открыт в другой кредитной организации',
                    'versions'    => [5],
                ]),
                'ACCOUNTDEBETBANKNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Наименование банка, в котором открыт счет организации',
                    'length'      => null,
                    'versions'    => [5],
                ]),
                'PLATPORAMOUNT' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма платежного поручения',
                    'versions'    => [5],
                ]),
            ]
        );
    }
}
