<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\DateTimeField;
use common\models\vtbxml\documents\fields\DoubleField;
use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class CurrDealInquiry181iDealInfo
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class CurrDealInquiry181iDealInfo extends BSDocument
{
    const TYPE = 'CurrDealInquiry181iDealInfo';
    const TYPE_ID = null;
    const VERSIONS = [7];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        7 => ['ADDINFO', 'AMOUNTPSCURRENCY', 'CURRCODEPS', 'DOCUMENTNUMBER', 'OPERCODE', 'PSNUMBER', 'PAYMENTAMOUNT', 'PAYMENTCURRCODE', 'TRANSFERDATE', 'NUM', 'OPERDATE', 'EXPECTDATE', 'REMARK', 'FOPERNUMMODE', 'CONTRACTNUMBER', 'CONTRACTDATE', 'PAYMENTDIRECTION', 'DOCUMENTTYPEID', 'PREPAYRETURN', 'DOCREF'],
    ];

    /** @var string Резервное поле **/
    public $ADDINFO;

    /** @var float Сумма в валюте контракта **/
    public $AMOUNTPSCURRENCY;

    /** @var string Код валюты  контракта **/
    public $CURRCODEPS;

    /** @var string Номер уведомления о зачислении/распоряжения на списание **/
    public $DOCUMENTNUMBER;

    /** @var string Код вида валютной операции **/
    public $OPERCODE;

    /** @var string Уникальный номер контракта (кредитного договора) **/
    public $PSNUMBER;

    /** @var float Сумма платежа (в валюте платежа) **/
    public $PAYMENTAMOUNT;

    /** @var string Код валюты платежа (цифровой) **/
    public $PAYMENTCURRCODE;

    /** @var \DateTime Дата зачисления/расч. документа **/
    public $TRANSFERDATE;

    /** @var integer № п/п (при корректировке - номер корректируемой строки) **/
    public $NUM;

    /** @var \DateTime Дата операции **/
    public $OPERDATE;

    /** @var \DateTime Ожидаемый срок **/
    public $EXPECTDATE;

    /** @var string Примечание **/
    public $REMARK;

    /** @var integer Признак: 0 - Номер контракта (кредитного договора), 1 - Номер-дата контракта **/
    public $FOPERNUMMODE;

    /** @var string Номер контракта **/
    public $CONTRACTNUMBER;

    /** @var \DateTime Дата контракта **/
    public $CONTRACTDATE;

    /** @var integer Тип зачисления **/
    public $PAYMENTDIRECTION;

    /** @var integer Вид документа, для которого оформляются сведения **/
    public $DOCUMENTTYPEID;

    /** @var \DateTime Срок возврата аванса **/
    public $PREPAYRETURN;

    /** @var string Ссылка на документ **/
    public $DOCREF;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'ADDINFO' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Резервное поле',
                    'length'      => 255,
                    'versions'    => [7],
                ]),
                'AMOUNTPSCURRENCY' => new DoubleField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма в валюте контракта',
                    'versions'    => [7],
                ]),
                'CURRCODEPS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код валюты  контракта',
                    'length'      => 3,
                    'versions'    => [7],
                ]),
                'DOCUMENTNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер уведомления о зачислении/распоряжения на списание',
                    'length'      => 50,
                    'versions'    => [7],
                ]),
                'OPERCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код вида валютной операции',
                    'length'      => 6,
                    'versions'    => [7],
                ]),
                'PSNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Уникальный номер контракта (кредитного договора)',
                    'length'      => 50,
                    'versions'    => [7],
                ]),
                'PAYMENTAMOUNT' => new DoubleField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма платежа (в валюте платежа)',
                    'versions'    => [7],
                ]),
                'PAYMENTCURRCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код валюты платежа (цифровой)',
                    'length'      => 3,
                    'versions'    => [7],
                ]),
                'TRANSFERDATE' => new DateTimeField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата зачисления/расч. документа',
                    'versions'    => [7],
                ]),
                'NUM' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => '№ п/п (при корректировке - номер корректируемой строки)',
                    'versions'    => [7],
                ]),
                'OPERDATE' => new DateTimeField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата операции',
                    'versions'    => [7],
                ]),
                'EXPECTDATE' => new DateTimeField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Ожидаемый срок',
                    'versions'    => [7],
                ]),
                'REMARK' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Примечание',
                    'length'      => 255,
                    'versions'    => [7],
                ]),
                'FOPERNUMMODE' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Признак: 0 - Номер контракта (кредитного договора), 1 - Номер-дата контракта',
                    'versions'    => [7],
                ]),
                'CONTRACTNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер контракта',
                    'length'      => 50,
                    'versions'    => [7],
                ]),
                'CONTRACTDATE' => new DateTimeField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата контракта',
                    'versions'    => [7],
                ]),
                'PAYMENTDIRECTION' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип зачисления',
                    'versions'    => [7],
                ]),
                'DOCUMENTTYPEID' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Вид документа, для которого оформляются сведения',
                    'versions'    => [7],
                ]),
                'PREPAYRETURN' => new DateTimeField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Срок возврата аванса',
                    'versions'    => [7],
                ]),
                'DOCREF' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Ссылка на документ',
                    'length'      => 50,
                    'versions'    => [7],
                ]),
            ]
        );
    }
}
