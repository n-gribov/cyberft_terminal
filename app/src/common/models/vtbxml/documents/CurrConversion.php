<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\BlobTableField;
use common\models\vtbxml\documents\fields\DateField;
use common\models\vtbxml\documents\fields\DoubleField;
use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\MoneyField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class CurrConversion
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class CurrConversion extends BSDocument
{
    const TYPE = 'CurrConversion';
    const TYPE_ID = 14;
    const VERSIONS = [5, 6];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        5 => ['DOCUMENTDATE', 'DOCUMENTNUMBER', 'CUSTID', 'SENDEROFFICIALS', 'CUSTOMERNAME', 'KBOPID', 'CUSTOMERPROPERTYTYPE', 'CUSTOMERINN', 'CUSTOMEROKPO', 'CUSTOMERPLACE', 'CUSTOMERPLACETYPE', 'CUSTOMERADDRESS', 'CUSTOMERCOUNTRY', 'ACCOUNTRUR', 'ACCOUNTCURR', 'CUSTOMERTYPE', 'PHONEOFFICIAL', 'FAXOFFICIAL', 'REQUESTRATE', 'ACCOUNTDEBET', 'CURRCODEDEBET', 'AMOUNTDEBET', 'ACCOUNTCREDIT', 'CURRCODECREDIT', 'AMOUNTCREDIT', 'DEBETBANKBIC', 'DEBETBANKTYPE', 'DEBETBANKNAME', 'DEBETBANKPLACE', 'DEBETBANKPLACETYPE', 'DEBETBANKCORRACC', 'CREDITBANKBIC', 'CREDITBANKTYPE', 'CREDITBANKNAME', 'CREDITBANKPLACE', 'CREDITBANKCORRACC', 'CREDITBANKPLACETYPE', 'VALIDITYPERIOD', 'PAYMENTDETAILS', 'CHARGEACCOUNT', 'VALUEDATETYPE', 'CURRCODECHARGE', 'GROUNDRECEIPTSBLOB', 'OPERCODE', 'OPERCODEDESCRIPTION', 'CURRDEALINQUIRYNUMBER', 'CURRDEALINQUIRYDATE', 'RESERVED', 'RESERVED1', 'DEALTYPE', 'REQUESTRATETYPE', 'CHARGETYPE', 'SUPPLYCONDITION', 'SUPPLYCONDITIONDATE', 'BANKAGREEMENT', 'TRANSFERDOCUMENTNUMBER', 'TRANSFERDOCUMENTDATE', 'DEPOINFO', 'CREDITBANKSWIFT', 'BALANCEACCOUNT', 'BALANCEBANKBIC', 'BALANCEDEPINFO', 'BALANCETO', 'BALANCEBANKSWIFT'],
        6 => ['DOCUMENTDATE', 'DOCUMENTNUMBER', 'CUSTID', 'SENDEROFFICIALS', 'CUSTOMERNAME', 'KBOPID', 'CUSTOMERPROPERTYTYPE', 'CUSTOMERINN', 'CUSTOMEROKPO', 'CUSTOMERPLACE', 'CUSTOMERPLACETYPE', 'CUSTOMERADDRESS', 'CUSTOMERCOUNTRY', 'ACCOUNTRUR', 'ACCOUNTCURR', 'CUSTOMERTYPE', 'PHONEOFFICIAL', 'FAXOFFICIAL', 'REQUESTRATE', 'ACCOUNTDEBET', 'CURRCODEDEBET', 'AMOUNTDEBET', 'ACCOUNTCREDIT', 'CURRCODECREDIT', 'AMOUNTCREDIT', 'DEBETBANKBIC', 'DEBETBANKTYPE', 'DEBETBANKNAME', 'DEBETBANKPLACE', 'DEBETBANKPLACETYPE', 'DEBETBANKCORRACC', 'CREDITBANKBIC', 'CREDITBANKTYPE', 'CREDITBANKNAME', 'CREDITBANKPLACE', 'CREDITBANKCORRACC', 'CREDITBANKPLACETYPE', 'VALIDITYPERIOD', 'PAYMENTDETAILS', 'CHARGEACCOUNT', 'VALUEDATETYPE', 'CURRCODECHARGE', 'GROUNDRECEIPTSBLOB', 'OPERCODE', 'OPERCODEDESCRIPTION', 'RESERVED', 'RESERVED1', 'DEALTYPE', 'REQUESTRATETYPE', 'CHARGETYPE', 'SUPPLYCONDITION', 'SUPPLYCONDITIONDATE', 'BANKAGREEMENT', 'TRANSFERDOCUMENTNUMBER', 'TRANSFERDOCUMENTDATE', 'DEPOINFO', 'CREDITBANKSWIFT', 'BALANCEACCOUNT', 'BALANCEBANKBIC', 'BALANCEDEPINFO', 'BALANCETO', 'BALANCEBANKSWIFT'],
    ];

    /** @var \DateTime Дата документа **/
    public $DOCUMENTDATE;

    /** @var string Номер документа **/
    public $DOCUMENTNUMBER;

    /** @var integer ID организации **/
    public $CUSTID;

    /** @var string Ответственный исполнитель **/
    public $SENDEROFFICIALS;

    /** @var string Название Предприятия **/
    public $CUSTOMERNAME;

    /** @var integer ID подразделения **/
    public $KBOPID;

    /** @var string Форма собственности Предприятия **/
    public $CUSTOMERPROPERTYTYPE;

    /** @var string ИНН Предприятия **/
    public $CUSTOMERINN;

    /** @var string Код ОКПО Предприятия **/
    public $CUSTOMEROKPO;

    /** @var string Населенный пункт  Предприятия **/
    public $CUSTOMERPLACE;

    /** @var string Тип населенного пункта Предприятия **/
    public $CUSTOMERPLACETYPE;

    /** @var string Адрес Предприятия **/
    public $CUSTOMERADDRESS;

    /** @var string Код страны Предприятия **/
    public $CUSTOMERCOUNTRY;

    /** @var string Рублевый счет Предприятия в Банке **/
    public $ACCOUNTRUR;

    /** @var string Валютный расчетный счет Предприятия в Банке **/
    public $ACCOUNTCURR;

    /** @var integer Тип Предприятия: резидент – 0, нерезидент – 1, прочее **/
    public $CUSTOMERTYPE;

    /** @var string Телефон ответственного сотрудника **/
    public $PHONEOFFICIAL;

    /** @var string Номер факса ответственного сотрудника **/
    public $FAXOFFICIAL;

    /** @var float Курс по заявке **/
    public $REQUESTRATE;

    /** @var string Счет по дебету **/
    public $ACCOUNTDEBET;

    /** @var string Код валюты счета по дебету **/
    public $CURRCODEDEBET;

    /** @var float Сумма в валюте счета по дебету **/
    public $AMOUNTDEBET;

    /** @var string Счет по кредиту **/
    public $ACCOUNTCREDIT;

    /** @var string Код валюты счета по кредиту **/
    public $CURRCODECREDIT;

    /** @var float Сумма в валюте счета по кредиту **/
    public $AMOUNTCREDIT;

    /** @var string БИК банка дебета **/
    public $DEBETBANKBIC;

    /** @var string Тип банка дебета **/
    public $DEBETBANKTYPE;

    /** @var string Название банка дебета **/
    public $DEBETBANKNAME;

    /** @var string Город банка дебета **/
    public $DEBETBANKPLACE;

    /** @var string Тип населенного пункта банка дебета **/
    public $DEBETBANKPLACETYPE;

    /** @var string Корсчет банка дебета **/
    public $DEBETBANKCORRACC;

    /** @var string БИК банка кредита **/
    public $CREDITBANKBIC;

    /** @var string Тип банка кредита **/
    public $CREDITBANKTYPE;

    /** @var string Название банка кредита **/
    public $CREDITBANKNAME;

    /** @var string Город банка кредита **/
    public $CREDITBANKPLACE;

    /** @var string Корсчет банка кредита **/
    public $CREDITBANKCORRACC;

    /** @var string Тип населенного пункта банка кредита **/
    public $CREDITBANKPLACETYPE;

    /** @var \DateTime Срок действия заявки **/
    public $VALIDITYPERIOD;

    /** @var string Детали платежа, доп. информация **/
    public $PAYMENTDETAILS;

    /** @var string Счет комиссии **/
    public $CHARGEACCOUNT;

    /** @var string Выбор даты валютирования: TOD (сегодня), TOM (завтра), SPOT (послезавтра) и др. **/
    public $VALUEDATETYPE;

    /** @var string Код валюты комиссии **/
    public $CURRCODECHARGE;

    /** @var CurrConversionGroundReceipt[] Документы, обосновывающие сделку **/
    public $GROUNDRECEIPTSBLOB = [];

    /** @var string Код вида операции **/
    public $OPERCODE;

    /** @var string Описание вида операции **/
    public $OPERCODEDESCRIPTION;

    /** @var string Номер сопровождающей справки о вал. операциях **/
    public $CURRDEALINQUIRYNUMBER;

    /** @var \DateTime Дата справки о вал. операциях **/
    public $CURRDEALINQUIRYDATE;

    /** @var string Резервное поле **/
    public $RESERVED;

    /** @var string Резервное поле **/
    public $RESERVED1;

    /** @var string ID подразделения **/
    public $DEALTYPE;

    /** @var integer Тип курса продажи (курс банка - 0, заданный пользователем – 1, льготный курс - 2) **/
    public $REQUESTRATETYPE;

    /** @var integer Способ списания комиссии **/
    public $CHARGETYPE;

    /** @var string Условия поставки рублей **/
    public $SUPPLYCONDITION;

    /** @var \DateTime Дата поставки рублей **/
    public $SUPPLYCONDITIONDATE;

    /** @var string Соглашения с банком **/
    public $BANKAGREEMENT;

    /** @var string Номер поручения на перевод средств на счет продажи **/
    public $TRANSFERDOCUMENTNUMBER;

    /** @var \DateTime Дата поручения на перевод средств на счет продажи **/
    public $TRANSFERDOCUMENTDATE;

    /** @var string Реквизиты банка зачисления купленной валюты **/
    public $DEPOINFO;

    /** @var string SWIFT-код банка зачисления валюты **/
    public $CREDITBANKSWIFT;

    /** @var string Счет зачисления неиспользованной суммы **/
    public $BALANCEACCOUNT;

    /** @var string БИК банка зачисления неиспользованной суммы **/
    public $BALANCEBANKBIC;

    /** @var string Реквизиты банка для зачисления неиспользованной суммы **/
    public $BALANCEDEPINFO;

    /** @var integer Признак «Неиспользованную валюту вернуть на счет в нашем банке / в другой кредитной организации» **/
    public $BALANCETO;

    /** @var string SWIFT-код банка зачисления **/
    public $BALANCEBANKSWIFT;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'DOCUMENTDATE' => new DateField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Дата документа',
                    'versions'    => [5, 6],
                ]),
                'DOCUMENTNUMBER' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Номер документа',
                    'length'      => 15,
                    'versions'    => [5, 6],
                ]),
                'CUSTID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ID организации',
                    'versions'    => [5, 6],
                ]),
                'SENDEROFFICIALS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Ответственный исполнитель',
                    'length'      => 40,
                    'versions'    => [5, 6],
                ]),
                'CUSTOMERNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Название Предприятия',
                    'length'      => 160,
                    'versions'    => [5, 6],
                ]),
                'KBOPID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ID подразделения',
                    'versions'    => [5, 6],
                ]),
                'CUSTOMERPROPERTYTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Форма собственности Предприятия',
                    'length'      => 10,
                    'versions'    => [5, 6],
                ]),
                'CUSTOMERINN' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'ИНН Предприятия',
                    'length'      => 14,
                    'versions'    => [5, 6],
                ]),
                'CUSTOMEROKPO' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код ОКПО Предприятия',
                    'length'      => 20,
                    'versions'    => [5, 6],
                ]),
                'CUSTOMERPLACE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Населенный пункт  Предприятия',
                    'length'      => 50,
                    'versions'    => [5, 6],
                ]),
                'CUSTOMERPLACETYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип населенного пункта Предприятия',
                    'length'      => 5,
                    'versions'    => [5, 6],
                ]),
                'CUSTOMERADDRESS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Адрес Предприятия',
                    'length'      => 255,
                    'versions'    => [5, 6],
                ]),
                'CUSTOMERCOUNTRY' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код страны Предприятия',
                    'length'      => 3,
                    'versions'    => [5, 6],
                ]),
                'ACCOUNTRUR' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Рублевый счет Предприятия в Банке',
                    'length'      => 25,
                    'versions'    => [5, 6],
                ]),
                'ACCOUNTCURR' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Валютный расчетный счет Предприятия в Банке',
                    'length'      => 25,
                    'versions'    => [5, 6],
                ]),
                'CUSTOMERTYPE' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип Предприятия: резидент – 0, нерезидент – 1, прочее',
                    'versions'    => [5, 6],
                ]),
                'PHONEOFFICIAL' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Телефон ответственного сотрудника',
                    'length'      => 20,
                    'versions'    => [5, 6],
                ]),
                'FAXOFFICIAL' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер факса ответственного сотрудника',
                    'length'      => 20,
                    'versions'    => [5, 6],
                ]),
                'REQUESTRATE' => new DoubleField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Курс по заявке',
                    'versions'    => [5, 6],
                ]),
                'ACCOUNTDEBET' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Счет по дебету',
                    'length'      => 35,
                    'versions'    => [5, 6],
                ]),
                'CURRCODEDEBET' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Код валюты счета по дебету',
                    'length'      => 3,
                    'versions'    => [5, 6],
                ]),
                'AMOUNTDEBET' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма в валюте счета по дебету',
                    'versions'    => [5, 6],
                ]),
                'ACCOUNTCREDIT' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Счет по кредиту',
                    'length'      => 35,
                    'versions'    => [5, 6],
                ]),
                'CURRCODECREDIT' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Код валюты счета по кредиту',
                    'length'      => 3,
                    'versions'    => [5, 6],
                ]),
                'AMOUNTCREDIT' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма в валюте счета по кредиту',
                    'versions'    => [5, 6],
                ]),
                'DEBETBANKBIC' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'БИК банка дебета',
                    'length'      => 15,
                    'versions'    => [5, 6],
                ]),
                'DEBETBANKTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип банка дебета',
                    'length'      => 5,
                    'versions'    => [5, 6],
                ]),
                'DEBETBANKNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Название банка дебета',
                    'length'      => 80,
                    'versions'    => [5, 6],
                ]),
                'DEBETBANKPLACE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Город банка дебета',
                    'length'      => 25,
                    'versions'    => [5, 6],
                ]),
                'DEBETBANKPLACETYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип населенного пункта банка дебета',
                    'length'      => 15,
                    'versions'    => [5, 6],
                ]),
                'DEBETBANKCORRACC' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Корсчет банка дебета',
                    'length'      => 25,
                    'versions'    => [5, 6],
                ]),
                'CREDITBANKBIC' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'БИК банка кредита',
                    'length'      => 15,
                    'versions'    => [5, 6],
                ]),
                'CREDITBANKTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип банка кредита',
                    'length'      => 5,
                    'versions'    => [5, 6],
                ]),
                'CREDITBANKNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Название банка кредита',
                    'length'      => 80,
                    'versions'    => [5, 6],
                ]),
                'CREDITBANKPLACE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Город банка кредита',
                    'length'      => 25,
                    'versions'    => [5, 6],
                ]),
                'CREDITBANKCORRACC' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Корсчет банка кредита',
                    'length'      => 25,
                    'versions'    => [5, 6],
                ]),
                'CREDITBANKPLACETYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип населенного пункта банка кредита',
                    'length'      => 15,
                    'versions'    => [5, 6],
                ]),
                'VALIDITYPERIOD' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Срок действия заявки',
                    'versions'    => [5, 6],
                ]),
                'PAYMENTDETAILS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Детали платежа, доп. информация',
                    'length'      => 255,
                    'versions'    => [5, 6],
                ]),
                'CHARGEACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Счет комиссии',
                    'length'      => 25,
                    'versions'    => [5, 6],
                ]),
                'VALUEDATETYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Выбор даты валютирования: TOD (сегодня), TOM (завтра), SPOT (послезавтра) и др.',
                    'length'      => 40,
                    'versions'    => [5, 6],
                ]),
                'CURRCODECHARGE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код валюты комиссии',
                    'length'      => 3,
                    'versions'    => [5, 6],
                ]),
                'GROUNDRECEIPTSBLOB' => new BlobTableField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Документы, обосновывающие сделку',
                    'recordType'  => 'CurrConversionGroundReceipt',
                    'versions'    => [5, 6],
                ]),
                'OPERCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код вида операции',
                    'length'      => 10,
                    'versions'    => [5, 6],
                ]),
                'OPERCODEDESCRIPTION' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Описание вида операции',
                    'length'      => null,
                    'versions'    => [5, 6],
                ]),
                'CURRDEALINQUIRYNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер сопровождающей справки о вал. операциях',
                    'length'      => 15,
                    'versions'    => [5],
                ]),
                'CURRDEALINQUIRYDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата справки о вал. операциях',
                    'versions'    => [5],
                ]),
                'RESERVED' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Резервное поле',
                    'length'      => 255,
                    'versions'    => [5, 6],
                ]),
                'RESERVED1' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Резервное поле',
                    'length'      => 255,
                    'versions'    => [5, 6],
                ]),
                'DEALTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'ID подразделения',
                    'length'      => null,
                    'versions'    => [5, 6],
                ]),
                'REQUESTRATETYPE' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип курса продажи (курс банка - 0, заданный пользователем – 1, льготный курс - 2)',
                    'versions'    => [5, 6],
                ]),
                'CHARGETYPE' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Способ списания комиссии',
                    'versions'    => [5, 6],
                ]),
                'SUPPLYCONDITION' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Условия поставки рублей',
                    'length'      => 150,
                    'versions'    => [5, 6],
                ]),
                'SUPPLYCONDITIONDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата поставки рублей',
                    'versions'    => [5, 6],
                ]),
                'BANKAGREEMENT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Соглашения с банком',
                    'length'      => null,
                    'versions'    => [5, 6],
                ]),
                'TRANSFERDOCUMENTNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер поручения на перевод средств на счет продажи',
                    'length'      => 15,
                    'versions'    => [5, 6],
                ]),
                'TRANSFERDOCUMENTDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата поручения на перевод средств на счет продажи',
                    'versions'    => [5, 6],
                ]),
                'DEPOINFO' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Реквизиты банка зачисления купленной валюты',
                    'length'      => 255,
                    'versions'    => [5, 6],
                ]),
                'CREDITBANKSWIFT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'SWIFT-код банка зачисления валюты',
                    'length'      => 15,
                    'versions'    => [5, 6],
                ]),
                'BALANCEACCOUNT' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Счет зачисления неиспользованной суммы',
                    'length'      => 25,
                    'versions'    => [5, 6],
                ]),
                'BALANCEBANKBIC' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'БИК банка зачисления неиспользованной суммы',
                    'length'      => 15,
                    'versions'    => [5, 6],
                ]),
                'BALANCEDEPINFO' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Реквизиты банка для зачисления неиспользованной суммы',
                    'length'      => 255,
                    'versions'    => [5, 6],
                ]),
                'BALANCETO' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Признак «Неиспользованную валюту вернуть на счет в нашем банке / в другой кредитной организации»',
                    'versions'    => [5, 6],
                ]),
                'BALANCEBANKSWIFT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'SWIFT-код банка зачисления',
                    'length'      => 15,
                    'versions'    => [5, 6],
                ]),
            ]
        );
    }
}
