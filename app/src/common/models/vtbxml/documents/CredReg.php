<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\AttachmentField;
use common\models\vtbxml\documents\fields\BlobTableField;
use common\models\vtbxml\documents\fields\DateField;
use common\models\vtbxml\documents\fields\DoubleField;
use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\MoneyField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class CredReg
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class CredReg extends BSDocument
{
    const TYPE = 'CredReg';
    const TYPE_ID = 1044;
    const VERSIONS = [5];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        5 => ['DOCUMENTDATE', 'DOCUMENTNUMBER', 'CUSTID', 'SENDEROFFICIALS', 'KBOPID', 'CUSTOMERBANKNAME', 'CUSTOMERBANKBIC', 'CUSTOMERPROPERTYTYPE', 'CUSTOMERNAME', 'CUSTOMEROGRN', 'CUSTOMERINN', 'CUSTOMERKPP', 'CUSTOMERTYPE', 'LAWSTATE', 'LAWDISTRICT', 'LAWCITY', 'LAWPLACETYPE', 'LAWPLACE', 'LAWSTREET', 'LAWBUILDING', 'LAWBLOCK', 'LAWOFFICE', 'PHONEOFFICIALS', 'DPDATE', 'DPNUM1', 'DPNUM2', 'DPNUM3', 'DPNUM4', 'DPNUM5', 'CONNUMBER', 'ISCONNUMBER', 'CONDATE', 'CONCURRCODE', 'CONCURRNAME', 'CONAMOUNT', 'ISCONAMOUNT', 'CONENDDATE', 'CONAMOUNTTRANSFER', 'CREDAMOUNTCURR', 'CREDPAYPERIODCODE', 'CREDTRANCHEBLOB', 'DPNUMBEROTHERBANK', 'FIXRATEPERCENT', 'LIBORRATE', 'OTHERRATEMETHOD', 'INCREASERATEPERCENT', 'DEBTSAMOUNT', 'PAYMENTRETURNBLOB', 'ISDIRECTINVESTING', 'DEPOSITAMOUNT', 'CREDRECEIPTINFOBLOB', 'BANKVKFULLNAME', 'NONRESIDENTINFO', 'OTHERPAYMENTS', 'FLAGPAYMENTRETURN', 'DOCATTACHMENT', 'SENDDATEBEFOREISSUECLIENT', 'SENDTYPEBEFOREISSUECLIENT', 'DATEOGRN'],
    ];

    /** @var \DateTime Дата документа **/
    public $DOCUMENTDATE;

    /** @var string Номер документа **/
    public $DOCUMENTNUMBER;

    /** @var integer ID организации **/
    public $CUSTID;

    /** @var string Ответственный исполнитель **/
    public $SENDEROFFICIALS;

    /** @var integer ID подразделения **/
    public $KBOPID;

    /** @var string Наименование уполномоченного банка **/
    public $CUSTOMERBANKNAME;

    /** @var string БИК банка клиента **/
    public $CUSTOMERBANKBIC;

    /** @var string Форма собственности организации-резидента **/
    public $CUSTOMERPROPERTYTYPE;

    /** @var string Наименование организации-резидента **/
    public $CUSTOMERNAME;

    /** @var string ОГРН организации-резидента **/
    public $CUSTOMEROGRN;

    /** @var string ИНН организации-резидента **/
    public $CUSTOMERINN;

    /** @var string КПП организации-резидента **/
    public $CUSTOMERKPP;

    /** @var integer Тип организации- резидента 0 – резидент 1 – нерезидент **/
    public $CUSTOMERTYPE;

    /** @var string Субъект РФ **/
    public $LAWSTATE;

    /** @var string Район **/
    public $LAWDISTRICT;

    /** @var string Город **/
    public $LAWCITY;

    /** @var string Тип населенного пункта **/
    public $LAWPLACETYPE;

    /** @var string Населенный пункт **/
    public $LAWPLACE;

    /** @var string Улица **/
    public $LAWSTREET;

    /** @var string Номер дома **/
    public $LAWBUILDING;

    /** @var string Корпус/строение **/
    public $LAWBLOCK;

    /** @var string Офис/квартира **/
    public $LAWOFFICE;

    /** @var string Телефон ответственного исполнителя **/
    public $PHONEOFFICIALS;

    /** @var \DateTime Дата присвоения уникального номера кредитного договора **/
    public $DPDATE;

    /** @var string Первая часть уникального номера кредитного договора **/
    public $DPNUM1;

    /** @var string Вторая часть уникального номера кредитного договора **/
    public $DPNUM2;

    /** @var string Третья часть уникального номера кредитного договора **/
    public $DPNUM3;

    /** @var string Четвертая часть уникального номера кредитного договора **/
    public $DPNUM4;

    /** @var string Пятая часть уникального номера кредитного договора **/
    public $DPNUM5;

    /** @var string Номер кредитного договора **/
    public $CONNUMBER;

    /** @var integer Признак номера кредитного договора (с номером – 0/без номера – 1) **/
    public $ISCONNUMBER;

    /** @var \DateTime Дата кредитного договора **/
    public $CONDATE;

    /** @var string Код валюты цены кредитного договора **/
    public $CONCURRCODE;

    /** @var string Наименование валюты цены  кредитного договора **/
    public $CONCURRNAME;

    /** @var float Сумма кредитного договора **/
    public $CONAMOUNT;

    /** @var integer Признак суммы кредитного договора (с суммой – 0/без суммы – 1) **/
    public $ISCONAMOUNT;

    /** @var \DateTime Дата завершения исполнения обязательств по кредитному договору **/
    public $CONENDDATE;

    /** @var float Сумма, подлежащая зачислению на счета за рубежом **/
    public $CONAMOUNTTRANSFER;

    /** @var float Сумма, подлежащая погашению за счет валютной выручки **/
    public $CREDAMOUNTCURR;

    /** @var string Код срока погашения суммы кредитного договора **/
    public $CREDPAYPERIODCODE;

    /** @var CredRegCredTranche[] Блоб-таблица, содержащая информацию о предоставляемых траншах по кредитному договору **/
    public $CREDTRANCHEBLOB = [];

    /** @var string Сведения о ранее присвоенном кредитному договору уникальном номере **/
    public $DPNUMBEROTHERBANK;

    /** @var float Фиксированный размер процентной ставки (% годовых) **/
    public $FIXRATEPERCENT;

    /** @var string Код ставки ЛИБОР **/
    public $LIBORRATE;

    /** @var string Другие методы определения процентной ставки **/
    public $OTHERRATEMETHOD;

    /** @var string Размер процентной надбавки (% годовых) **/
    public $INCREASERATEPERCENT;

    /** @var float Сумма фактической задолженности по основному долгу **/
    public $DEBTSAMOUNT;

    /** @var CredRegPaymentReturn[] Блоб-таблица, содержащая информацию о графике платежей по возврату заемных средств **/
    public $PAYMENTRETURNBLOB = [];

    /** @var integer Признак наличия отношений прямого инвестирования **/
    public $ISDIRECTINVESTING;

    /** @var float Сумма залогового обеспечения **/
    public $DEPOSITAMOUNT;

    /** @var CredRegCredReceiptInfo[] Блоб-таблица, содержащая информацию о получении резидентом кредита, предоставленного на синдицированной основе **/
    public $CREDRECEIPTINFOBLOB = [];

    /** @var string Наименование банка УК **/
    public $BANKVKFULLNAME;

    /** @var CredRegNonresidentInfo[] БЛОБ-таблица: Реквизиты нерезидента (нерезидентов) **/
    public $NONRESIDENTINFO = [];

    /** @var string Иные платежи, предусмотренные договором **/
    public $OTHERPAYMENTS;

    /** @var integer Признак заполнения п. 9.2.: 1-на основании сведений из кредитного договора; 2-на основании оценочных данных **/
    public $FLAGPAYMENTRETURN;

    /** @var BSDocumentAttachment[] Вложения файлов. (формируется по правилу, приведенному в п. 3.3.8) **/
    public $DOCATTACHMENT = [];

    /** @var \DateTime Дата представления документов для постановки кредитного договора на учет **/
    public $SENDDATEBEFOREISSUECLIENT;

    /** @var string Способ представления документов для постановки кредитного договора на учет **/
    public $SENDTYPEBEFOREISSUECLIENT;

    /** @var \DateTime Дата государственной регистрации **/
    public $DATEOGRN;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'DOCUMENTDATE' => new DateField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Дата документа',
                    'versions'    => [5],
                ]),
                'DOCUMENTNUMBER' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Номер документа',
                    'length'      => 15,
                    'versions'    => [5],
                ]),
                'CUSTID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ID организации',
                    'versions'    => [5],
                ]),
                'SENDEROFFICIALS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Ответственный исполнитель',
                    'length'      => 40,
                    'versions'    => [5],
                ]),
                'KBOPID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ID подразделения',
                    'versions'    => [5],
                ]),
                'CUSTOMERBANKNAME' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Наименование уполномоченного банка',
                    'length'      => 255,
                    'versions'    => [5],
                ]),
                'CUSTOMERBANKBIC' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'БИК банка клиента',
                    'length'      => 9,
                    'versions'    => [5],
                ]),
                'CUSTOMERPROPERTYTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Форма собственности организации-резидента',
                    'length'      => 10,
                    'versions'    => [5],
                ]),
                'CUSTOMERNAME' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Наименование организации-резидента',
                    'length'      => 255,
                    'versions'    => [5],
                ]),
                'CUSTOMEROGRN' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'ОГРН организации-резидента',
                    'length'      => 30,
                    'versions'    => [5],
                ]),
                'CUSTOMERINN' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'ИНН организации-резидента',
                    'length'      => 12,
                    'versions'    => [5],
                ]),
                'CUSTOMERKPP' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'КПП организации-резидента',
                    'length'      => 9,
                    'versions'    => [5],
                ]),
                'CUSTOMERTYPE' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип организации- резидента 0 – резидент 1 – нерезидент',
                    'versions'    => [5],
                ]),
                'LAWSTATE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Субъект РФ',
                    'length'      => 40,
                    'versions'    => [5],
                ]),
                'LAWDISTRICT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Район',
                    'length'      => 255,
                    'versions'    => [5],
                ]),
                'LAWCITY' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Город',
                    'length'      => 100,
                    'versions'    => [5],
                ]),
                'LAWPLACETYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип населенного пункта',
                    'length'      => 5,
                    'versions'    => [5],
                ]),
                'LAWPLACE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Населенный пункт',
                    'length'      => 35,
                    'versions'    => [5],
                ]),
                'LAWSTREET' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Улица',
                    'length'      => 100,
                    'versions'    => [5],
                ]),
                'LAWBUILDING' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер дома',
                    'length'      => 50,
                    'versions'    => [5],
                ]),
                'LAWBLOCK' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Корпус/строение',
                    'length'      => 20,
                    'versions'    => [5],
                ]),
                'LAWOFFICE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Офис/квартира',
                    'length'      => 20,
                    'versions'    => [5],
                ]),
                'PHONEOFFICIALS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Телефон ответственного исполнителя',
                    'length'      => 20,
                    'versions'    => [5],
                ]),
                'DPDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата присвоения уникального номера кредитного договора',
                    'versions'    => [5],
                ]),
                'DPNUM1' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Первая часть уникального номера кредитного договора',
                    'length'      => 8,
                    'versions'    => [5],
                ]),
                'DPNUM2' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Вторая часть уникального номера кредитного договора',
                    'length'      => 4,
                    'versions'    => [5],
                ]),
                'DPNUM3' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Третья часть уникального номера кредитного договора',
                    'length'      => 4,
                    'versions'    => [5],
                ]),
                'DPNUM4' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Четвертая часть уникального номера кредитного договора',
                    'length'      => 1,
                    'versions'    => [5],
                ]),
                'DPNUM5' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Пятая часть уникального номера кредитного договора',
                    'length'      => 1,
                    'versions'    => [5],
                ]),
                'CONNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер кредитного договора',
                    'length'      => 50,
                    'versions'    => [5],
                ]),
                'ISCONNUMBER' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Признак номера кредитного договора (с номером – 0/без номера – 1)',
                    'versions'    => [5],
                ]),
                'CONDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата кредитного договора',
                    'versions'    => [5],
                ]),
                'CONCURRCODE' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Код валюты цены кредитного договора',
                    'length'      => 3,
                    'versions'    => [5],
                ]),
                'CONCURRNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Наименование валюты цены  кредитного договора',
                    'length'      => 100,
                    'versions'    => [5],
                ]),
                'CONAMOUNT' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма кредитного договора',
                    'versions'    => [5],
                ]),
                'ISCONAMOUNT' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Признак суммы кредитного договора (с суммой – 0/без суммы – 1)',
                    'versions'    => [5],
                ]),
                'CONENDDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата завершения исполнения обязательств по кредитному договору',
                    'versions'    => [5],
                ]),
                'CONAMOUNTTRANSFER' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма, подлежащая зачислению на счета за рубежом',
                    'versions'    => [5],
                ]),
                'CREDAMOUNTCURR' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма, подлежащая погашению за счет валютной выручки',
                    'versions'    => [5],
                ]),
                'CREDPAYPERIODCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код срока погашения суммы кредитного договора',
                    'length'      => 5,
                    'versions'    => [5],
                ]),
                'CREDTRANCHEBLOB' => new BlobTableField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Блоб-таблица, содержащая информацию о предоставляемых траншах по кредитному договору',
                    'recordType'  => 'CredRegCredTranche',
                    'versions'    => [5],
                ]),
                'DPNUMBEROTHERBANK' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сведения о ранее присвоенном кредитному договору уникальном номере',
                    'length'      => 25,
                    'versions'    => [5],
                ]),
                'FIXRATEPERCENT' => new DoubleField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Фиксированный размер процентной ставки (% годовых)',
                    'versions'    => [5],
                ]),
                'LIBORRATE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код ставки ЛИБОР',
                    'length'      => 10,
                    'versions'    => [5],
                ]),
                'OTHERRATEMETHOD' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Другие методы определения процентной ставки',
                    'length'      => null,
                    'versions'    => [5],
                ]),
                'INCREASERATEPERCENT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Размер процентной надбавки (% годовых)',
                    'length'      => null,
                    'versions'    => [5],
                ]),
                'DEBTSAMOUNT' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма фактической задолженности по основному долгу',
                    'versions'    => [5],
                ]),
                'PAYMENTRETURNBLOB' => new BlobTableField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Блоб-таблица, содержащая информацию о графике платежей по возврату заемных средств',
                    'recordType'  => 'CredRegPaymentReturn',
                    'versions'    => [5],
                ]),
                'ISDIRECTINVESTING' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Признак наличия отношений прямого инвестирования',
                    'versions'    => [5],
                ]),
                'DEPOSITAMOUNT' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма залогового обеспечения',
                    'versions'    => [5],
                ]),
                'CREDRECEIPTINFOBLOB' => new BlobTableField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Блоб-таблица, содержащая информацию о получении резидентом кредита, предоставленного на синдицированной основе',
                    'recordType'  => 'CredRegCredReceiptInfo',
                    'versions'    => [5],
                ]),
                'BANKVKFULLNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Наименование банка УК',
                    'length'      => 255,
                    'versions'    => [5],
                ]),
                'NONRESIDENTINFO' => new BlobTableField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'БЛОБ-таблица: Реквизиты нерезидента (нерезидентов)',
                    'recordType'  => 'CredRegNonresidentInfo',
                    'versions'    => [5],
                ]),
                'OTHERPAYMENTS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Иные платежи, предусмотренные договором',
                    'length'      => 255,
                    'versions'    => [5],
                ]),
                'FLAGPAYMENTRETURN' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Признак заполнения п. 9.2.: 1-на основании сведений из кредитного договора; 2-на основании оценочных данных',
                    'versions'    => [5],
                ]),
                'DOCATTACHMENT' => new AttachmentField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Вложения файлов. (формируется по правилу, приведенному в п. 3.3.8)',
                    'versions'    => [5],
                ]),
                'SENDDATEBEFOREISSUECLIENT' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата представления документов для постановки кредитного договора на учет',
                    'versions'    => [5],
                ]),
                'SENDTYPEBEFOREISSUECLIENT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Способ представления документов для постановки кредитного договора на учет',
                    'length'      => 2,
                    'versions'    => [5],
                ]),
                'DATEOGRN' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата государственной регистрации',
                    'versions'    => [5],
                ]),
            ]
        );
    }
}
