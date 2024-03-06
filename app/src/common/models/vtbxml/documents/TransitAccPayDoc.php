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
 * Class TransitAccPayDoc
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class TransitAccPayDoc extends BSDocument
{
    const TYPE = 'TransitAccPayDoc';
    const TYPE_ID = 49;
    const VERSIONS = [8];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        8 => ['DOCUMENTDATE', 'DOCUMENTNUMBER', 'CUSTID', 'SENDEROFFICIALS', 'KBOPID', 'ACCOUNTTRANSIT', 'ADDINFO', 'AMOUNTCREDIT', 'AMOUNTDEBET', 'CREDITBANKBICCURR', 'CURRCODE', 'CURRDEALINQUIRYDATE', 'CURRDEALINQUIRYNUMBER', 'CUSTOMERBANKBIC', 'AMOUNTSELL', 'AMOUNTSELLCURR', 'BANKAGREEMENT', 'CHARGEACCOUNT', 'CHARGEBANKBIC', 'CHARGETYPE', 'DEALTYPE', 'DEPOINFOCURR', 'ISCREDITINOURBANK', 'ISSELL', 'OPERCODECREDIT', 'OPERCODESELL', 'RECEIVERRURACCOUNT', 'RECEIVERRURBIC', 'RECEIVERRURINFO', 'REQUESTRATE', 'REQUESTRATETYPE', 'SUPPLYCONDITION', 'SUPPLYCONDITIONDATE', 'CUSTOMERBANKNAME', 'CUSTOMERBANKPLACE', 'CUSTOMERBANKPLACETYPE', 'CUSTOMERINN', 'CUSTOMERNAME', 'CUSTOMEROKPO', 'CUSTOMERPROPERTYTYPE', 'GROUNDRECEIPTSBLOB', 'ISCREDIT', 'NOTICEBLOB', 'PHONEOFFICIALS', 'RECEIVERCURRACCOUNT', 'RECEIVERCURRSWIFT', 'TOTALAMOUNT', 'CHARGEBANKINFO', 'DOCATTACHMENT'],
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

    /** @var string Транзитный счет (дебетуемый) **/
    public $ACCOUNTTRANSIT;

    /** @var string Дополнительная информация **/
    public $ADDINFO;

    /** @var float Сумма в валюте для зачисления **/
    public $AMOUNTCREDIT;

    /** @var float Сумма списания с транзитного счета **/
    public $AMOUNTDEBET;

    /** @var string БИК банка зачисления валюты **/
    public $CREDITBANKBICCURR;

    /** @var string Код валюты документа **/
    public $CURRCODE;

    /** @var \DateTime Дата справки о валютных операциях **/
    public $CURRDEALINQUIRYDATE;

    /** @var string Номер справки о валютных операциях **/
    public $CURRDEALINQUIRYNUMBER;

    /** @var string БИК банка организации **/
    public $CUSTOMERBANKBIC;

    /** @var float Сумма в валюте для продажи **/
    public $AMOUNTSELL;

    /** @var float Сумма продажи денежных средств в валюте (факт) **/
    public $AMOUNTSELLCURR;

    /** @var string Соглашения с банком **/
    public $BANKAGREEMENT;

    /** @var string Счет списания комиссии **/
    public $CHARGEACCOUNT;

    /** @var string БИК банка списания комиссии **/
    public $CHARGEBANKBIC;

    /** @var integer Способ списания комиссии (0 – со счета CHARGEACCOUNT; 1- удержать из суммы сделки) **/
    public $CHARGETYPE;

    /** @var string Тип сделки **/
    public $DEALTYPE;

    /** @var string Реквизиты банка зачисления валюты **/
    public $DEPOINFOCURR;

    /** @var integer Признак указания счета зачисления валюты в нашем банке **/
    public $ISCREDITINOURBANK;

    /** @var integer Признак продажи денежных средств (принимает значение 0 или 1) **/
    public $ISSELL;

    /** @var string Код вида операции (признак зачисления – VO61100) **/
    public $OPERCODECREDIT;

    /** @var string Код вида операции (признак продажи – VO01010) **/
    public $OPERCODESELL;

    /** @var string Счет зачисления рублей **/
    public $RECEIVERRURACCOUNT;

    /** @var string БИК банка зачисления рублей **/
    public $RECEIVERRURBIC;

    /** @var string Реквизиты банка зачисления рублей **/
    public $RECEIVERRURINFO;

    /** @var float Минимально допустимый курс продажи **/
    public $REQUESTRATE;

    /** @var integer Курс продажи валюты (курс банка - 0, заданный пользователем – 1, льготный курс - 2) **/
    public $REQUESTRATETYPE;

    /** @var string Условия поставки рублей **/
    public $SUPPLYCONDITION;

    /** @var \DateTime Дата выполнения условий поставки рублей **/
    public $SUPPLYCONDITIONDATE;

    /** @var string Наименование банка организации **/
    public $CUSTOMERBANKNAME;

    /** @var string Населенный пункт банка организации **/
    public $CUSTOMERBANKPLACE;

    /** @var string Тип населенного пункта банка организации **/
    public $CUSTOMERBANKPLACETYPE;

    /** @var string ИНН организации **/
    public $CUSTOMERINN;

    /** @var string Название организации **/
    public $CUSTOMERNAME;

    /** @var string Код организации по ОКПО **/
    public $CUSTOMEROKPO;

    /** @var string Форма собственности организации **/
    public $CUSTOMERPROPERTYTYPE;

    /** @var TransitAccPayDocGroundReceipt[] Блоб-таблица, содержащая информацию об обосновывающих документах **/
    public $GROUNDRECEIPTSBLOB = [];

    /** @var integer Признак зачисления денежных средств на валютный счет (принимает значение 0 или 1) **/
    public $ISCREDIT;

    /** @var TransitAccPayDocNotice[] Блоб-таблица, содержащая информацию об уведомлениях **/
    public $NOTICEBLOB = [];

    /** @var string Номер телефона ответственного лица **/
    public $PHONEOFFICIALS;

    /** @var string Счет зачисления валюты **/
    public $RECEIVERCURRACCOUNT;

    /** @var string SWIFT-код банка зачисления валюты **/
    public $RECEIVERCURRSWIFT;

    /** @var float Общая сумма поступивших средств **/
    public $TOTALAMOUNT;

    /** @var string Реквизиты банка списания комиссии **/
    public $CHARGEBANKINFO;

    /** @var BSDocumentAttachment[] Вложения файлов **/
    public $DOCATTACHMENT = [];

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'DOCUMENTDATE' => new DateField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Дата документа',
                    'versions'    => [8],
                ]),
                'DOCUMENTNUMBER' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Номер документа',
                    'length'      => 15,
                    'versions'    => [8],
                ]),
                'CUSTID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ID организации',
                    'versions'    => [8],
                ]),
                'SENDEROFFICIALS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Ответственный исполнитель',
                    'length'      => 40,
                    'versions'    => [8],
                ]),
                'KBOPID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ID подразделения',
                    'versions'    => [8],
                ]),
                'ACCOUNTTRANSIT' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Транзитный счет (дебетуемый)',
                    'length'      => 25,
                    'versions'    => [8],
                ]),
                'ADDINFO' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дополнительная информация',
                    'length'      => 255,
                    'versions'    => [8],
                ]),
                'AMOUNTCREDIT' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма в валюте для зачисления',
                    'versions'    => [8],
                ]),
                'AMOUNTDEBET' => new MoneyField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Сумма списания с транзитного счета',
                    'versions'    => [8],
                ]),
                'CREDITBANKBICCURR' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'БИК банка зачисления валюты',
                    'length'      => 15,
                    'versions'    => [8],
                ]),
                'CURRCODE' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Код валюты документа',
                    'length'      => 3,
                    'versions'    => [8],
                ]),
                'CURRDEALINQUIRYDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата справки о валютных операциях',
                    'versions'    => [8],
                ]),
                'CURRDEALINQUIRYNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер справки о валютных операциях',
                    'length'      => 15,
                    'versions'    => [8],
                ]),
                'CUSTOMERBANKBIC' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'БИК банка организации',
                    'length'      => 9,
                    'versions'    => [8],
                ]),
                'AMOUNTSELL' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма в валюте для продажи',
                    'versions'    => [8],
                ]),
                'AMOUNTSELLCURR' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма продажи денежных средств в валюте (факт)',
                    'versions'    => [8],
                ]),
                'BANKAGREEMENT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Соглашения с банком',
                    'length'      => null,
                    'versions'    => [8],
                ]),
                'CHARGEACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Счет списания комиссии',
                    'length'      => 23,
                    'versions'    => [8],
                ]),
                'CHARGEBANKBIC' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'БИК банка списания комиссии',
                    'length'      => 15,
                    'versions'    => [8],
                ]),
                'CHARGETYPE' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Способ списания комиссии (0 – со счета CHARGEACCOUNT; 1- удержать из суммы сделки)',
                    'versions'    => [8],
                ]),
                'DEALTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип сделки',
                    'length'      => 40,
                    'versions'    => [8],
                ]),
                'DEPOINFOCURR' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Реквизиты банка зачисления валюты',
                    'length'      => 255,
                    'versions'    => [8],
                ]),
                'ISCREDITINOURBANK' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Признак указания счета зачисления валюты в нашем банке',
                    'versions'    => [8],
                ]),
                'ISSELL' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Признак продажи денежных средств (принимает значение 0 или 1)',
                    'versions'    => [8],
                ]),
                'OPERCODECREDIT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код вида операции (признак зачисления – VO61100)',
                    'length'      => 10,
                    'versions'    => [8],
                ]),
                'OPERCODESELL' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код вида операции (признак продажи – VO01010)',
                    'length'      => 10,
                    'versions'    => [8],
                ]),
                'RECEIVERRURACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Счет зачисления рублей',
                    'length'      => 25,
                    'versions'    => [8],
                ]),
                'RECEIVERRURBIC' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'БИК банка зачисления рублей',
                    'length'      => 15,
                    'versions'    => [8],
                ]),
                'RECEIVERRURINFO' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Реквизиты банка зачисления рублей',
                    'length'      => 255,
                    'versions'    => [8],
                ]),
                'REQUESTRATE' => new DoubleField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Минимально допустимый курс продажи',
                    'versions'    => [8],
                ]),
                'REQUESTRATETYPE' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Курс продажи валюты (курс банка - 0, заданный пользователем – 1, льготный курс - 2)',
                    'versions'    => [8],
                ]),
                'SUPPLYCONDITION' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Условия поставки рублей',
                    'length'      => 150,
                    'versions'    => [8],
                ]),
                'SUPPLYCONDITIONDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата выполнения условий поставки рублей',
                    'versions'    => [8],
                ]),
                'CUSTOMERBANKNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Наименование банка организации',
                    'length'      => 80,
                    'versions'    => [8],
                ]),
                'CUSTOMERBANKPLACE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Населенный пункт банка организации',
                    'length'      => 25,
                    'versions'    => [8],
                ]),
                'CUSTOMERBANKPLACETYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип населенного пункта банка организации',
                    'length'      => 15,
                    'versions'    => [8],
                ]),
                'CUSTOMERINN' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'ИНН организации',
                    'length'      => 14,
                    'versions'    => [8],
                ]),
                'CUSTOMERNAME' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Название организации',
                    'length'      => 160,
                    'versions'    => [8],
                ]),
                'CUSTOMEROKPO' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код организации по ОКПО',
                    'length'      => 20,
                    'versions'    => [8],
                ]),
                'CUSTOMERPROPERTYTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Форма собственности организации',
                    'length'      => 10,
                    'versions'    => [8],
                ]),
                'GROUNDRECEIPTSBLOB' => new BlobTableField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Блоб-таблица, содержащая информацию об обосновывающих документах',
                    'recordType'  => 'TransitAccPayDocGroundReceipt',
                    'versions'    => [8],
                ]),
                'ISCREDIT' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Признак зачисления денежных средств на валютный счет (принимает значение 0 или 1)',
                    'versions'    => [8],
                ]),
                'NOTICEBLOB' => new BlobTableField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Блоб-таблица, содержащая информацию об уведомлениях',
                    'recordType'  => 'TransitAccPayDocNotice',
                    'versions'    => [8],
                ]),
                'PHONEOFFICIALS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер телефона ответственного лица',
                    'length'      => 20,
                    'versions'    => [8],
                ]),
                'RECEIVERCURRACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Счет зачисления валюты',
                    'length'      => 25,
                    'versions'    => [8],
                ]),
                'RECEIVERCURRSWIFT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'SWIFT-код банка зачисления валюты',
                    'length'      => 11,
                    'versions'    => [8],
                ]),
                'TOTALAMOUNT' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Общая сумма поступивших средств',
                    'versions'    => [8],
                ]),
                'CHARGEBANKINFO' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Реквизиты банка списания комиссии',
                    'length'      => 255,
                    'versions'    => [8],
                ]),
                'DOCATTACHMENT' => new AttachmentField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Вложения файлов',
                    'versions'    => [8],
                ]),
            ]
        );
    }
}
