<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\BlobTableField;
use common\models\vtbxml\documents\fields\DateField;
use common\models\vtbxml\documents\fields\DoubleField;
use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\MoneyField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class CurrBuy
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class CurrBuy extends BSDocument
{
    const TYPE = 'CurrBuy';
    const TYPE_ID = 8;
    const VERSIONS = [5, 6];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        5 => ['DEALTYPE', 'DOCUMENTDATE', 'GROUNDCODE', 'DOCUMENTNUMBER', 'GROUNDNAME', 'CUSTID', 'GROUNDDOCUMENT', 'SENDEROFFICIALS', 'ACCOUNTCOVER', 'CUSTOMERNAME', 'BANKCOVERBIC', 'KBOPID', 'CUSTOMERPROPERTYTYPE', 'DEPOSITAGREEMENTNUMBER', 'CUSTOMERINN', 'DEPOSITAGREEMENTDATE', 'GTDNUMBER', 'CUSTOMEROKPO', 'CUSTOMERPLACE', 'GTDDATE', 'GTDAMOUNT', 'CUSTOMERPLACETYPE', 'CUSTOMERADDRESS', 'LICENCENUMBER', 'CUSTOMERCOUNTRY', 'LICENCEDATE', 'DEALPASSPORTNUMBER', 'ACCOUNTRUR', 'DEALPASSPORTDATE', 'ACCOUNTCURR', 'CUSTOMERTYPE', 'CONTRACTNUMBER', 'PHONEOFFICIAL', 'CONTRACTDATE', 'FAXOFFICIAL', 'ANOTHERDOCUMENTDATE', 'REQUESTRATE', 'ANOTHERDOCUMENTTYPE', 'ACCOUNTDEBET', 'ANOTHERDOCUMENTNUMBER', 'CURRCODEDEBET', 'CBAGREEMENTNUMBER', 'AMOUNTDEBET', 'CBAGREEMENTDATE', 'ACCOUNTCREDIT', 'BUYTYPE', 'CURRCODECREDIT', 'DEPOINFO', 'AMOUNTCREDIT', 'TRANSFERDOCUMENTNUMBER', 'DEBETBANKBIC', 'TRANSFERDOCUMENTDATE', 'DEBETBANKTYPE', 'AGREEMENTINFONUMBER', 'DEBETBANKNAME', 'AMOUNTCOVER', 'DEBETBANKPLACE', 'REQUESTRATETYPE', 'DEBETBANKPLACETYPE', 'CHARGETYPE', 'DEBETBANKCORRACC', 'SUPPLYCONDITION', 'CREDITBANKBIC', 'SUPPLYCONDITIONDATE', 'CREDITBANKTYPE', 'BANKAGREEMENT', 'CREDITBANKNAME', 'CREDITBANKSWIFT', 'CREDITBANKPLACE', 'CREDITBANKCORRACC', 'CREDITBANKPLACETYPE', 'VALIDITYPERIOD', 'PAYMENTDETAILS', 'CHARGEACCOUNT', 'VALUEDATETYPE', 'GROUNDRECEIPTSBLOB', 'CODEMESSAGE', 'MESSAGEFORBANK', 'NONACCEPTAGREEDATE', 'NONACCEPTAGREENUMBER', 'BALANCEACCOUNT', 'BALANCEBANKBIC', 'BALANCEDEPINFO', 'BALANCETO'],
        6 => ['DEALTYPE', 'DOCUMENTDATE', 'GROUNDCODE', 'DOCUMENTNUMBER', 'GROUNDNAME', 'CUSTID', 'GROUNDDOCUMENT', 'SENDEROFFICIALS', 'ACCOUNTCOVER', 'CUSTOMERNAME', 'BANKCOVERBIC', 'KBOPID', 'CUSTOMERPROPERTYTYPE', 'CUSTOMERINN', 'GTDNUMBER', 'CUSTOMEROKPO', 'CUSTOMERPLACE', 'GTDDATE', 'GTDAMOUNT', 'CUSTOMERPLACETYPE', 'CUSTOMERADDRESS', 'LICENCENUMBER', 'CUSTOMERCOUNTRY', 'LICENCEDATE', 'DEALPASSPORTNUMBER', 'ACCOUNTRUR', 'DEALPASSPORTDATE', 'ACCOUNTCURR', 'CUSTOMERTYPE', 'CONTRACTNUMBER', 'PHONEOFFICIAL', 'CONTRACTDATE', 'FAXOFFICIAL', 'ANOTHERDOCUMENTDATE', 'REQUESTRATE', 'ANOTHERDOCUMENTTYPE', 'ACCOUNTDEBET', 'ANOTHERDOCUMENTNUMBER', 'CURRCODEDEBET', 'CBAGREEMENTNUMBER', 'AMOUNTDEBET', 'CBAGREEMENTDATE', 'ACCOUNTCREDIT', 'BUYTYPE', 'CURRCODECREDIT', 'DEPOINFO', 'AMOUNTCREDIT', 'TRANSFERDOCUMENTNUMBER', 'DEBETBANKBIC', 'TRANSFERDOCUMENTDATE', 'DEBETBANKTYPE', 'AGREEMENTINFONUMBER', 'DEBETBANKNAME', 'AMOUNTCOVER', 'DEBETBANKPLACE', 'REQUESTRATETYPE', 'DEBETBANKPLACETYPE', 'CHARGETYPE', 'DEBETBANKCORRACC', 'SUPPLYCONDITION', 'CREDITBANKBIC', 'SUPPLYCONDITIONDATE', 'CREDITBANKTYPE', 'BANKAGREEMENT', 'CREDITBANKNAME', 'CREDITBANKSWIFT', 'CREDITBANKPLACE', 'CREDITBANKCORRACC', 'CREDITBANKPLACETYPE', 'VALIDITYPERIOD', 'PAYMENTDETAILS', 'CHARGEACCOUNT', 'VALUEDATETYPE', 'GROUNDRECEIPTSBLOB', 'CODEMESSAGE', 'MESSAGEFORBANK', 'NONACCEPTAGREEDATE', 'NONACCEPTAGREENUMBER', 'BALANCEACCOUNT', 'BALANCEBANKBIC', 'BALANCEDEPINFO', 'BALANCETO'],
    ];

    /** @var string Тип покупки **/
    public $DEALTYPE;

    /** @var \DateTime Дата документа **/
    public $DOCUMENTDATE;

    /** @var string Код вида операции **/
    public $GROUNDCODE;

    /** @var string Номер документа **/
    public $DOCUMENTNUMBER;

    /** @var string Описание вида операции **/
    public $GROUNDNAME;

    /** @var integer ID организации **/
    public $CUSTID;

    /** @var string Доп. информация для вал. контроля **/
    public $GROUNDDOCUMENT;

    /** @var string Ответственный исполнитель **/
    public $SENDEROFFICIALS;

    /** @var string Счет зачисления суммы рублевого покрытия **/
    public $ACCOUNTCOVER;

    /** @var string Название Предприятия **/
    public $CUSTOMERNAME;

    /** @var string БИК банка, в котором открыт счет зачисления руб. покрытия **/
    public $BANKCOVERBIC;

    /** @var integer ID подразделения **/
    public $KBOPID;

    /** @var string Форма собственности Предприятия **/
    public $CUSTOMERPROPERTYTYPE;

    /** @var string Номер сопровождающей справки о вал. операциях currdealinquirynumber **/
    public $DEPOSITAGREEMENTNUMBER;

    /** @var string ИНН Предприятия **/
    public $CUSTOMERINN;

    /** @var \DateTime Дата справки о вал. операциях currdealinquirydate **/
    public $DEPOSITAGREEMENTDATE;

    /** @var string Номер заявления на резервирование **/
    public $GTDNUMBER;

    /** @var string Код ОКПО Предприятия **/
    public $CUSTOMEROKPO;

    /** @var string Населенный пункт  Предприятия **/
    public $CUSTOMERPLACE;

    /** @var \DateTime Дата заявления на резервирование **/
    public $GTDDATE;

    /** @var float Сумма в заявлении на резервирование **/
    public $GTDAMOUNT;

    /** @var string Тип населенного пункта Предприятия **/
    public $CUSTOMERPLACETYPE;

    /** @var string Адрес Предприятия **/
    public $CUSTOMERADDRESS;

    /** @var string Номер лицензии ЦБ на покупку валюты **/
    public $LICENCENUMBER;

    /** @var string Код страны Предприятия **/
    public $CUSTOMERCOUNTRY;

    /** @var \DateTime Дата лицензии ЦБ на покупку валюты **/
    public $LICENCEDATE;

    /** @var string Номер паспорта сделки **/
    public $DEALPASSPORTNUMBER;

    /** @var string Рублевый счет Предприятия в Банке **/
    public $ACCOUNTRUR;

    /** @var \DateTime Дата паспорта сделки **/
    public $DEALPASSPORTDATE;

    /** @var string Валютный расчетный счет Предприятия в Банке **/
    public $ACCOUNTCURR;

    /** @var integer Тип Предприятия: резидент – 0, нерезидент – 1, прочее **/
    public $CUSTOMERTYPE;

    /** @var string Номер контракта, обосновывающего  покупку валюты **/
    public $CONTRACTNUMBER;

    /** @var string Телефон ответственного сотрудника **/
    public $PHONEOFFICIAL;

    /** @var \DateTime Дата контракта, обосновывающего  покупку валюты **/
    public $CONTRACTDATE;

    /** @var string Факс ответственного сотрудника **/
    public $FAXOFFICIAL;

    /** @var \DateTime Дата представленного документа **/
    public $ANOTHERDOCUMENTDATE;

    /** @var float Курс по заявке **/
    public $REQUESTRATE;

    /** @var string Тип представленного документа **/
    public $ANOTHERDOCUMENTTYPE;

    /** @var string Счет по дебету **/
    public $ACCOUNTDEBET;

    /** @var string Номер представленного документа **/
    public $ANOTHERDOCUMENTNUMBER;

    /** @var string Код валюты счета по дебету **/
    public $CURRCODEDEBET;

    /** @var string Номер документа, регистрирующего в ЦБ валютную операцию **/
    public $CBAGREEMENTNUMBER;

    /** @var float Сумма в валюте счета по дебету **/
    public $AMOUNTDEBET;

    /** @var \DateTime Дата документа, регистрирующего в ЦБ валютную операцию **/
    public $CBAGREEMENTDATE;

    /** @var string Счет по кредиту **/
    public $ACCOUNTCREDIT;

    /** @var string Тип покупки: «на всю сумму рублей», «валюты не более», «ровно» **/
    public $BUYTYPE;

    /** @var string Код валюты счета по Кредиту **/
    public $CURRCODECREDIT;

    /** @var string Реквизиты банка зачисления купленной валюты **/
    public $DEPOINFO;

    /** @var float Сумма в валюте счета по кредиту **/
    public $AMOUNTCREDIT;

    /** @var string Номер поручения о переводе средств на покупку банку **/
    public $TRANSFERDOCUMENTNUMBER;

    /** @var string БИК банка дебета **/
    public $DEBETBANKBIC;

    /** @var \DateTime Дата поручения о переводе средств на покупку банку **/
    public $TRANSFERDOCUMENTDATE;

    /** @var string Тип банка дебета **/
    public $DEBETBANKTYPE;

    /** @var string Сведения о договоре **/
    public $AGREEMENTINFONUMBER;

    /** @var string Название банка дебета **/
    public $DEBETBANKNAME;

    /** @var float Сумма рублевого покрытия, перечисляемого банку **/
    public $AMOUNTCOVER;

    /** @var string Город банка дебета **/
    public $DEBETBANKPLACE;

    /** @var integer Тип курса покупки (курс банка - 0, заданный пользователем – 1, льготный курс - 2) **/
    public $REQUESTRATETYPE;

    /** @var string Тип населенного пункта банка дебета **/
    public $DEBETBANKPLACETYPE;

    /** @var integer Способ списания комиссии **/
    public $CHARGETYPE;

    /** @var string Корсчет банка дебета **/
    public $DEBETBANKCORRACC;

    /** @var string Условия поставки покупаемой валюты **/
    public $SUPPLYCONDITION;

    /** @var string БИК банка кредита **/
    public $CREDITBANKBIC;

    /** @var \DateTime Дата поставки покупаемой валюты **/
    public $SUPPLYCONDITIONDATE;

    /** @var string Тип банка кредита **/
    public $CREDITBANKTYPE;

    /** @var string Соглашения с банком **/
    public $BANKAGREEMENT;

    /** @var string Название банка кредита **/
    public $CREDITBANKNAME;

    /** @var string SWIFT-код банка зачисления валюты **/
    public $CREDITBANKSWIFT;

    /** @var string Город банка кредита **/
    public $CREDITBANKPLACE;

    /** @var string Кор. счет банка кредита **/
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

    /** @var CurrBuyGroundReceipt[] Документы, обосновывающие сделку **/
    public $GROUNDRECEIPTSBLOB = [];

    /** @var string Код сообщения **/
    public $CODEMESSAGE;

    /** @var string Сообщение для банка **/
    public $MESSAGEFORBANK;

    /** @var \DateTime Дата соглашения **/
    public $NONACCEPTAGREEDATE;

    /** @var string Номер соглашения **/
    public $NONACCEPTAGREENUMBER;

    /** @var string Счет зачисления неиспользованной суммы **/
    public $BALANCEACCOUNT;

    /** @var string БИК банка зачисления неиспользованной суммы **/
    public $BALANCEBANKBIC;

    /** @var string Реквизиты банка для зачисления неиспользованной суммы **/
    public $BALANCEDEPINFO;

    /** @var integer Признак «Неиспользованную валюту вернуть на счет в нашем банке / в другой кредитной организации» **/
    public $BALANCETO;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'DEALTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип покупки',
                    'length'      => 40,
                    'versions'    => [5, 6],
                ]),
                'DOCUMENTDATE' => new DateField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Дата документа',
                    'versions'    => [5, 6],
                ]),
                'GROUNDCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код вида операции',
                    'length'      => 10,
                    'versions'    => [5, 6],
                ]),
                'DOCUMENTNUMBER' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Номер документа',
                    'length'      => 15,
                    'versions'    => [5, 6],
                ]),
                'GROUNDNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Описание вида операции',
                    'length'      => null,
                    'versions'    => [5, 6],
                ]),
                'CUSTID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ID организации',
                    'versions'    => [5, 6],
                ]),
                'GROUNDDOCUMENT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Доп. информация для вал. контроля',
                    'length'      => 255,
                    'versions'    => [5, 6],
                ]),
                'SENDEROFFICIALS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Ответственный исполнитель',
                    'length'      => 40,
                    'versions'    => [5, 6],
                ]),
                'ACCOUNTCOVER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Счет зачисления суммы рублевого покрытия',
                    'length'      => 25,
                    'versions'    => [5, 6],
                ]),
                'CUSTOMERNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Название Предприятия',
                    'length'      => 160,
                    'versions'    => [5, 6],
                ]),
                'BANKCOVERBIC' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'БИК банка, в котором открыт счет зачисления руб. покрытия',
                    'length'      => 15,
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
                'DEPOSITAGREEMENTNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер сопровождающей справки о вал. операциях currdealinquirynumber',
                    'length'      => 50,
                    'versions'    => [5],
                ]),
                'CUSTOMERINN' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'ИНН Предприятия',
                    'length'      => 14,
                    'versions'    => [5, 6],
                ]),
                'DEPOSITAGREEMENTDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата справки о вал. операциях currdealinquirydate',
                    'versions'    => [5],
                ]),
                'GTDNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер заявления на резервирование',
                    'length'      => 50,
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
                'GTDDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата заявления на резервирование',
                    'versions'    => [5, 6],
                ]),
                'GTDAMOUNT' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма в заявлении на резервирование',
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
                'LICENCENUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер лицензии ЦБ на покупку валюты',
                    'length'      => 50,
                    'versions'    => [5, 6],
                ]),
                'CUSTOMERCOUNTRY' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код страны Предприятия',
                    'length'      => 3,
                    'versions'    => [5, 6],
                ]),
                'LICENCEDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата лицензии ЦБ на покупку валюты',
                    'versions'    => [5, 6],
                ]),
                'DEALPASSPORTNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер паспорта сделки',
                    'length'      => 50,
                    'versions'    => [5, 6],
                ]),
                'ACCOUNTRUR' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Рублевый счет Предприятия в Банке',
                    'length'      => 25,
                    'versions'    => [5, 6],
                ]),
                'DEALPASSPORTDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата паспорта сделки',
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
                'CONTRACTNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер контракта, обосновывающего  покупку валюты',
                    'length'      => 50,
                    'versions'    => [5, 6],
                ]),
                'PHONEOFFICIAL' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Телефон ответственного сотрудника',
                    'length'      => 20,
                    'versions'    => [5, 6],
                ]),
                'CONTRACTDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата контракта, обосновывающего  покупку валюты',
                    'versions'    => [5, 6],
                ]),
                'FAXOFFICIAL' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Факс ответственного сотрудника',
                    'length'      => 20,
                    'versions'    => [5, 6],
                ]),
                'ANOTHERDOCUMENTDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата представленного документа',
                    'versions'    => [5, 6],
                ]),
                'REQUESTRATE' => new DoubleField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Курс по заявке',
                    'versions'    => [5, 6],
                ]),
                'ANOTHERDOCUMENTTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип представленного документа',
                    'length'      => 100,
                    'versions'    => [5, 6],
                ]),
                'ACCOUNTDEBET' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Счет по дебету',
                    'length'      => 35,
                    'versions'    => [5, 6],
                ]),
                'ANOTHERDOCUMENTNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер представленного документа',
                    'length'      => 50,
                    'versions'    => [5, 6],
                ]),
                'CURRCODEDEBET' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Код валюты счета по дебету',
                    'length'      => 3,
                    'versions'    => [5, 6],
                ]),
                'CBAGREEMENTNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер документа, регистрирующего в ЦБ валютную операцию',
                    'length'      => 50,
                    'versions'    => [5, 6],
                ]),
                'AMOUNTDEBET' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма в валюте счета по дебету',
                    'versions'    => [5, 6],
                ]),
                'CBAGREEMENTDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата документа, регистрирующего в ЦБ валютную операцию',
                    'versions'    => [5, 6],
                ]),
                'ACCOUNTCREDIT' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Счет по кредиту',
                    'length'      => 35,
                    'versions'    => [5, 6],
                ]),
                'BUYTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип покупки: «на всю сумму рублей», «валюты не более», «ровно»',
                    'length'      => 255,
                    'versions'    => [5, 6],
                ]),
                'CURRCODECREDIT' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Код валюты счета по Кредиту',
                    'length'      => 3,
                    'versions'    => [5, 6],
                ]),
                'DEPOINFO' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Реквизиты банка зачисления купленной валюты',
                    'length'      => 255,
                    'versions'    => [5, 6],
                ]),
                'AMOUNTCREDIT' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма в валюте счета по кредиту',
                    'versions'    => [5, 6],
                ]),
                'TRANSFERDOCUMENTNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер поручения о переводе средств на покупку банку',
                    'length'      => 50,
                    'versions'    => [5, 6],
                ]),
                'DEBETBANKBIC' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'БИК банка дебета',
                    'length'      => 15,
                    'versions'    => [5, 6],
                ]),
                'TRANSFERDOCUMENTDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата поручения о переводе средств на покупку банку',
                    'versions'    => [5, 6],
                ]),
                'DEBETBANKTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип банка дебета',
                    'length'      => 5,
                    'versions'    => [5, 6],
                ]),
                'AGREEMENTINFONUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сведения о договоре',
                    'length'      => 18,
                    'versions'    => [5, 6],
                ]),
                'DEBETBANKNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Название банка дебета',
                    'length'      => 80,
                    'versions'    => [5, 6],
                ]),
                'AMOUNTCOVER' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма рублевого покрытия, перечисляемого банку',
                    'versions'    => [5, 6],
                ]),
                'DEBETBANKPLACE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Город банка дебета',
                    'length'      => 25,
                    'versions'    => [5, 6],
                ]),
                'REQUESTRATETYPE' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип курса покупки (курс банка - 0, заданный пользователем – 1, льготный курс - 2)',
                    'versions'    => [5, 6],
                ]),
                'DEBETBANKPLACETYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип населенного пункта банка дебета',
                    'length'      => 15,
                    'versions'    => [5, 6],
                ]),
                'CHARGETYPE' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Способ списания комиссии',
                    'versions'    => [5, 6],
                ]),
                'DEBETBANKCORRACC' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Корсчет банка дебета',
                    'length'      => 25,
                    'versions'    => [5, 6],
                ]),
                'SUPPLYCONDITION' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Условия поставки покупаемой валюты',
                    'length'      => 150,
                    'versions'    => [5, 6],
                ]),
                'CREDITBANKBIC' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'БИК банка кредита',
                    'length'      => 15,
                    'versions'    => [5, 6],
                ]),
                'SUPPLYCONDITIONDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата поставки покупаемой валюты',
                    'versions'    => [5, 6],
                ]),
                'CREDITBANKTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип банка кредита',
                    'length'      => 5,
                    'versions'    => [5, 6],
                ]),
                'BANKAGREEMENT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Соглашения с банком',
                    'length'      => null,
                    'versions'    => [5, 6],
                ]),
                'CREDITBANKNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Название банка кредита',
                    'length'      => 80,
                    'versions'    => [5, 6],
                ]),
                'CREDITBANKSWIFT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'SWIFT-код банка зачисления валюты',
                    'length'      => 15,
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
                    'description' => 'Кор. счет банка кредита',
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
                'GROUNDRECEIPTSBLOB' => new BlobTableField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Документы, обосновывающие сделку',
                    'recordType'  => 'CurrBuyGroundReceipt',
                    'versions'    => [5, 6],
                ]),
                'CODEMESSAGE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код сообщения',
                    'length'      => 50,
                    'versions'    => [5, 6],
                ]),
                'MESSAGEFORBANK' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сообщение для банка',
                    'length'      => 255,
                    'versions'    => [5, 6],
                ]),
                'NONACCEPTAGREEDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата соглашения',
                    'versions'    => [5, 6],
                ]),
                'NONACCEPTAGREENUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер соглашения',
                    'length'      => 25,
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
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Признак «Неиспользованную валюту вернуть на счет в нашем банке / в другой кредитной организации»',
                    'versions'    => [5, 6],
                ]),
            ]
        );
    }
}
