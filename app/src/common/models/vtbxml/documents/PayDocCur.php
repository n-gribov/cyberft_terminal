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
 * Class PayDocCur
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class PayDocCur extends BSDocument
{
    const TYPE = 'PayDocCur';
    const TYPE_ID = 9;
    const VERSIONS = [5, 6, 8, 9, 632, 633];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        5 => ['DOCUMENTDATE', 'DOCUMENTNUMBER', 'CUSTID', 'SENDEROFFICIALS', 'ADDITIONALINFO', 'KBOPID', 'AMOUNT', 'BENEFBANKACCOUNT', 'BENEFBANKADDRESS', 'BENEFBANKBIC', 'BENEFBANKBICTYPE', 'BENEFBANKCOUNTRYCODE', 'BENEFBANKNAME', 'BENEFBANKPLACE', 'BENEFICIAR', 'BENEFICIARACCOUNT', 'BENEFICIARADDRESS', 'BENEFICIARCOUNTRYCODE', 'BENEFICIARFISCALCODE', 'BENEFICIARPLACE', 'CHARGESACCOUNT', 'CHARGESBANKACCOUNT', 'CHARGESBANKADDRESS', 'CHARGESBANKBIC', 'CHARGESBANKBICTYPE', 'CHARGESBANKCOUNTRYCODE', 'CHARGESBANKNAME', 'CHARGESBANKPLACE', 'CHARGESCURRCODE', 'CHARGESTYPE', 'CURRCODE', 'GROUNDDOCUMENTS', 'IMEDIABANKACCOUNT', 'IMEDIABANKADDRESS', 'IMEDIABANKBIC', 'IMEDIABANKBICTYPE', 'IMEDIABANKCOUNTRYCODE', 'IMEDIABANKNAME', 'IMEDIABANKPLACE', 'ISADDITIONALINFO', 'ISMULTICURR', 'OFFICIALSFAX', 'OFFICIALSPHONE', 'OPERCODE', 'PAYUNTIL', 'PAYER', 'PAYERACCOUNT', 'PAYERADDRESS', 'PAYERBANKACCOUNT', 'PAYERBANKADDRESS', 'PAYERBANKBIC', 'PAYERBANKBICTYPE', 'PAYERBANKCOUNTRYCODE', 'PAYERBANKNAME', 'PAYERBANKPLACE', 'PAYERCOUNTRYCODE', 'PAYERFISCALCODE', 'PAYERPLACE', 'PAYMENTCODETYPE', 'PAYMENTEXECUTIONTYPE', 'PAYMENTTYPE', 'PAYMENTSDETAILS', 'URGENT', 'PAYEROKPOCODE', 'AMOUNTTRANSFER', 'CURRCODETRANSFER', 'REQUESTCONVRATE', 'CONVCHARGEACCOUNT', 'TAXCONFIRMPAYDOC', 'CURRDEALINQUIRYNUMBER', 'CURRDEALINQUIRYDATE', 'ADDINFOVALCONTROL', 'REQUESTRATETYPE', 'BANKAGREEMENT', 'OPERCODEBLOB', 'CODEMESSAGE', 'MESSAGEFORBANK', 'INDEXOFSERVICE', 'TYPEOFSERVICE'],
        6 => ['DOCUMENTDATE', 'DOCUMENTNUMBER', 'CUSTID', 'SENDEROFFICIALS', 'ADDITIONALINFO', 'KBOPID', 'AMOUNT', 'BENEFBANKACCOUNT', 'BENEFBANKADDRESS', 'BENEFBANKBIC', 'BENEFBANKBICTYPE', 'BENEFBANKCOUNTRYCODE', 'BENEFBANKNAME', 'BENEFBANKPLACE', 'BENEFICIAR', 'BENEFICIARACCOUNT', 'BENEFICIARADDRESS', 'BENEFICIARCOUNTRYCODE', 'BENEFICIARFISCALCODE', 'BENEFICIARPLACE', 'CHARGESACCOUNT', 'CHARGESBANKACCOUNT', 'CHARGESBANKADDRESS', 'CHARGESBANKBIC', 'CHARGESBANKBICTYPE', 'CHARGESBANKCOUNTRYCODE', 'CHARGESBANKNAME', 'CHARGESBANKPLACE', 'CHARGESCURRCODE', 'CHARGESTYPE', 'CURRCODE', 'GROUNDDOCUMENTS', 'IMEDIABANKACCOUNT', 'IMEDIABANKADDRESS', 'IMEDIABANKBIC', 'IMEDIABANKBICTYPE', 'IMEDIABANKCOUNTRYCODE', 'IMEDIABANKNAME', 'IMEDIABANKPLACE', 'ISADDITIONALINFO', 'ISMULTICURR', 'OFFICIALSFAX', 'OFFICIALSPHONE', 'OPERCODE', 'PAYUNTIL', 'PAYER', 'PAYERACCOUNT', 'PAYERADDRESS', 'PAYERBANKACCOUNT', 'PAYERBANKADDRESS', 'PAYERBANKBIC', 'PAYERBANKBICTYPE', 'PAYERBANKCOUNTRYCODE', 'PAYERBANKNAME', 'PAYERBANKPLACE', 'PAYERCOUNTRYCODE', 'PAYERFISCALCODE', 'PAYERPLACE', 'PAYMENTCODETYPE', 'PAYMENTEXECUTIONTYPE', 'PAYMENTTYPE', 'PAYMENTSDETAILS', 'URGENT', 'PAYEROKPOCODE', 'AMOUNTTRANSFER', 'CURRCODETRANSFER', 'REQUESTCONVRATE', 'CONVCHARGEACCOUNT', 'TAXCONFIRMPAYDOC', 'ADDINFOVALCONTROL', 'REQUESTRATETYPE', 'BANKAGREEMENT', 'OPERCODEBLOB', 'CODEMESSAGE', 'MESSAGEFORBANK', 'INDEXOFSERVICE', 'TYPEOFSERVICE', 'DOCATTACHMENT'],
        8 => ['DOCUMENTDATE', 'DOCUMENTNUMBER', 'CUSTID', 'SENDEROFFICIALS', 'ADDITIONALINFO', 'KBOPID', 'AMOUNT', 'BENEFBANKACCOUNT', 'BENEFBANKADDRESS', 'BENEFBANKBIC', 'BENEFBANKBICTYPE', 'BENEFBANKCOUNTRYCODE', 'BENEFBANKNAME', 'BENEFBANKPLACE', 'BENEFICIAR', 'BENEFICIARACCOUNT', 'BENEFICIARADDRESS', 'BENEFICIARCOUNTRYCODE', 'BENEFICIARFISCALCODE', 'BENEFICIARPLACE', 'CHARGESACCOUNT', 'CHARGESBANKACCOUNT', 'CHARGESBANKADDRESS', 'CHARGESBANKBIC', 'CHARGESBANKBICTYPE', 'CHARGESBANKCOUNTRYCODE', 'CHARGESBANKNAME', 'CHARGESBANKPLACE', 'CHARGESCURRCODE', 'CHARGESTYPE', 'CURRCODE', 'GROUNDDOCUMENTS', 'IMEDIABANKACCOUNT', 'IMEDIABANKADDRESS', 'IMEDIABANKBIC', 'IMEDIABANKBICTYPE', 'IMEDIABANKCOUNTRYCODE', 'IMEDIABANKNAME', 'IMEDIABANKPLACE', 'ISADDITIONALINFO', 'ISMULTICURR', 'OFFICIALSFAX', 'OFFICIALSPHONE', 'OPERCODE', 'PAYUNTIL', 'PAYER', 'PAYERACCOUNT', 'PAYERADDRESS', 'PAYERBANKACCOUNT', 'PAYERBANKADDRESS', 'PAYERBANKBIC', 'PAYERBANKBICTYPE', 'PAYERBANKCOUNTRYCODE', 'PAYERBANKNAME', 'PAYERBANKPLACE', 'PAYERCOUNTRYCODE', 'PAYERFISCALCODE', 'PAYERPLACE', 'PAYMENTCODETYPE', 'PAYMENTEXECUTIONTYPE', 'PAYMENTTYPE', 'PAYMENTSDETAILS', 'URGENT', 'PAYEROKPOCODE', 'AMOUNTTRANSFER', 'CURRCODETRANSFER', 'REQUESTCONVRATE', 'CONVCHARGEACCOUNT', 'TAXCONFIRMPAYDOC', 'CURRDEALINQUIRYNUMBER', 'CURRDEALINQUIRYDATE', 'ADDINFOVALCONTROL', 'REQUESTRATETYPE', 'BANKAGREEMENT', 'OPERCODEBLOB', 'CODEMESSAGE', 'MESSAGEFORBANK', 'INDEXOFSERVICE', 'TYPEOFSERVICE', 'DOCATTACHMENT'],
        9 => ['DOCUMENTDATE', 'DOCUMENTNUMBER', 'CUSTID', 'SENDEROFFICIALS', 'ADDITIONALINFO', 'KBOPID', 'AMOUNT', 'BENEFBANKACCOUNT', 'BENEFBANKADDRESS', 'BENEFBANKBIC', 'BENEFBANKBICTYPE', 'BENEFBANKCOUNTRYCODE', 'BENEFBANKNAME', 'BENEFBANKPLACE', 'BENEFICIAR', 'BENEFICIARACCOUNT', 'BENEFICIARADDRESS', 'BENEFICIARCOUNTRYCODE', 'BENEFICIARFISCALCODE', 'BENEFICIARPLACE', 'CHARGESACCOUNT', 'CHARGESBANKACCOUNT', 'CHARGESBANKADDRESS', 'CHARGESBANKBIC', 'CHARGESBANKBICTYPE', 'CHARGESBANKCOUNTRYCODE', 'CHARGESBANKNAME', 'CHARGESBANKPLACE', 'CHARGESCURRCODE', 'CHARGESTYPE', 'CURRCODE', 'GROUNDDOCUMENTS', 'IMEDIABANKACCOUNT', 'IMEDIABANKADDRESS', 'IMEDIABANKBIC', 'IMEDIABANKBICTYPE', 'IMEDIABANKCOUNTRYCODE', 'IMEDIABANKNAME', 'IMEDIABANKPLACE', 'ISADDITIONALINFO', 'ISMULTICURR', 'OFFICIALSFAX', 'OFFICIALSPHONE', 'OPERCODE', 'PAYUNTIL', 'PAYER', 'PAYERACCOUNT', 'PAYERADDRESS', 'PAYERBANKACCOUNT', 'PAYERBANKADDRESS', 'PAYERBANKBIC', 'PAYERBANKBICTYPE', 'PAYERBANKCOUNTRYCODE', 'PAYERBANKNAME', 'PAYERBANKPLACE', 'PAYERCOUNTRYCODE', 'PAYERFISCALCODE', 'PAYERPLACE', 'PAYMENTCODETYPE', 'PAYMENTEXECUTIONTYPE', 'PAYMENTTYPE', 'PAYMENTSDETAILS', 'URGENT', 'PAYEROKPOCODE', 'AMOUNTTRANSFER', 'CURRCODETRANSFER', 'REQUESTCONVRATE', 'CONVCHARGEACCOUNT', 'TAXCONFIRMPAYDOC', 'ADDINFOVALCONTROL', 'REQUESTRATETYPE', 'BANKAGREEMENT', 'OPERCODEBLOB', 'CODEMESSAGE', 'MESSAGEFORBANK', 'INDEXOFSERVICE', 'TYPEOFSERVICE', 'DOCATTACHMENT'],
        632 => ['DOCUMENTDATE', 'DOCUMENTNUMBER', 'CUSTID', 'SENDEROFFICIALS', 'ADDITIONALINFO', 'KBOPID', 'AMOUNT', 'BENEFBANKACCOUNT', 'BENEFBANKADDRESS', 'BENEFBANKBIC', 'BENEFBANKBICTYPE', 'BENEFBANKCOUNTRYCODE', 'BENEFBANKNAME', 'BENEFBANKPLACE', 'BENEFICIAR', 'BENEFICIARACCOUNT', 'BENEFICIARADDRESS', 'BENEFICIARCOUNTRYCODE', 'BENEFICIARFISCALCODE', 'BENEFICIARPLACE', 'CHARGESACCOUNT', 'CHARGESBANKACCOUNT', 'CHARGESBANKADDRESS', 'CHARGESBANKBIC', 'CHARGESBANKBICTYPE', 'CHARGESBANKCOUNTRYCODE', 'CHARGESBANKNAME', 'CHARGESBANKPLACE', 'CHARGESCURRCODE', 'CHARGESTYPE', 'CURRCODE', 'GROUNDDOCUMENTS', 'IMEDIABANKACCOUNT', 'IMEDIABANKADDRESS', 'IMEDIABANKBIC', 'IMEDIABANKBICTYPE', 'IMEDIABANKCOUNTRYCODE', 'IMEDIABANKNAME', 'IMEDIABANKPLACE', 'ISADDITIONALINFO', 'ISMULTICURR', 'OFFICIALSFAX', 'OFFICIALSPHONE', 'OPERCODE', 'PAYUNTIL', 'PAYER', 'PAYERACCOUNT', 'PAYERADDRESS', 'PAYERBANKACCOUNT', 'PAYERBANKADDRESS', 'PAYERBANKBIC', 'PAYERBANKBICTYPE', 'PAYERBANKCOUNTRYCODE', 'PAYERBANKNAME', 'PAYERBANKPLACE', 'PAYERCOUNTRYCODE', 'PAYERFISCALCODE', 'PAYERPLACE', 'PAYMENTCODETYPE', 'PAYMENTEXECUTIONTYPE', 'PAYMENTTYPE', 'PAYMENTSDETAILS', 'URGENT', 'PAYEROKPOCODE', 'AMOUNTTRANSFER', 'CURRCODETRANSFER', 'REQUESTCONVRATE', 'CONVCHARGEACCOUNT', 'TAXCONFIRMPAYDOC', 'CURRDEALINQUIRYNUMBER', 'CURRDEALINQUIRYDATE', 'ADDINFOVALCONTROL', 'REQUESTRATETYPE', 'BANKAGREEMENT', 'OPERCODEBLOB', 'CODEMESSAGE', 'MESSAGEFORBANK', 'MBA_BUDGETNAME', 'MBA_BUDGRECEIVERNAME', 'MBA_CONTRACTDATE', 'MBA_CONTRACTNUMBER', 'MBA_ESTIMATENAME', 'MBA_KESRCODE', 'MBA_PROJECTNUMBER', 'MBA_CLASSIFIER1ID', 'MBA_CLASSIFIER2ID', 'MBA_CLASSIFIER3ID', 'MBA_CLASSIFIER4ID', 'MBA_CLASSIFIER5ID', 'INDEXOFSERVICE', 'TYPEOFSERVICE'],
        633 => ['DOCUMENTDATE', 'DOCUMENTNUMBER', 'CUSTID', 'SENDEROFFICIALS', 'ADDITIONALINFO', 'KBOPID', 'AMOUNT', 'BENEFBANKACCOUNT', 'BENEFBANKADDRESS', 'BENEFBANKBIC', 'BENEFBANKBICTYPE', 'BENEFBANKCOUNTRYCODE', 'BENEFBANKNAME', 'BENEFBANKPLACE', 'BENEFICIAR', 'BENEFICIARACCOUNT', 'BENEFICIARADDRESS', 'BENEFICIARCOUNTRYCODE', 'BENEFICIARFISCALCODE', 'BENEFICIARPLACE', 'CHARGESACCOUNT', 'CHARGESBANKACCOUNT', 'CHARGESBANKADDRESS', 'CHARGESBANKBIC', 'CHARGESBANKBICTYPE', 'CHARGESBANKCOUNTRYCODE', 'CHARGESBANKNAME', 'CHARGESBANKPLACE', 'CHARGESCURRCODE', 'CHARGESTYPE', 'CURRCODE', 'GROUNDDOCUMENTS', 'IMEDIABANKACCOUNT', 'IMEDIABANKADDRESS', 'IMEDIABANKBIC', 'IMEDIABANKBICTYPE', 'IMEDIABANKCOUNTRYCODE', 'IMEDIABANKNAME', 'IMEDIABANKPLACE', 'ISADDITIONALINFO', 'ISMULTICURR', 'OFFICIALSFAX', 'OFFICIALSPHONE', 'OPERCODE', 'PAYUNTIL', 'PAYER', 'PAYERACCOUNT', 'PAYERADDRESS', 'PAYERBANKACCOUNT', 'PAYERBANKADDRESS', 'PAYERBANKBIC', 'PAYERBANKBICTYPE', 'PAYERBANKCOUNTRYCODE', 'PAYERBANKNAME', 'PAYERBANKPLACE', 'PAYERCOUNTRYCODE', 'PAYERFISCALCODE', 'PAYERPLACE', 'PAYMENTCODETYPE', 'PAYMENTEXECUTIONTYPE', 'PAYMENTTYPE', 'PAYMENTSDETAILS', 'URGENT', 'PAYEROKPOCODE', 'AMOUNTTRANSFER', 'CURRCODETRANSFER', 'REQUESTCONVRATE', 'CONVCHARGEACCOUNT', 'TAXCONFIRMPAYDOC', 'ADDINFOVALCONTROL', 'REQUESTRATETYPE', 'BANKAGREEMENT', 'OPERCODEBLOB', 'CODEMESSAGE', 'MESSAGEFORBANK', 'MBA_BUDGETNAME', 'MBA_BUDGRECEIVERNAME', 'MBA_CONTRACTDATE', 'MBA_CONTRACTNUMBER', 'MBA_ESTIMATENAME', 'MBA_KESRCODE', 'MBA_PROJECTNUMBER', 'MBA_CLASSIFIER1ID', 'MBA_CLASSIFIER2ID', 'MBA_CLASSIFIER3ID', 'MBA_CLASSIFIER4ID', 'MBA_CLASSIFIER5ID', 'INDEXOFSERVICE', 'TYPEOFSERVICE', 'DOCATTACHMENT'],
    ];

    /** @var \DateTime Дата документа **/
    public $DOCUMENTDATE;

    /** @var string Номер документа **/
    public $DOCUMENTNUMBER;

    /** @var integer ID организации **/
    public $CUSTID;

    /** @var string Ответственный исполнитель **/
    public $SENDEROFFICIALS;

    /** @var string Дополнительная информация. **/
    public $ADDITIONALINFO;

    /** @var integer ID подразделения банка **/
    public $KBOPID;

    /** @var float Сумма **/
    public $AMOUNT;

    /** @var string Счет банка бенефициара. **/
    public $BENEFBANKACCOUNT;

    /** @var string Адрес банка бенефициара **/
    public $BENEFBANKADDRESS;

    /** @var string КОД банка бенефициара **/
    public $BENEFBANKBIC;

    /** @var string Тип КОД-а банка бенефициара **/
    public $BENEFBANKBICTYPE;

    /** @var string Код страны банка бенефициара **/
    public $BENEFBANKCOUNTRYCODE;

    /** @var string Название банка бенефициара **/
    public $BENEFBANKNAME;

    /** @var string Город банка бенефициара **/
    public $BENEFBANKPLACE;

    /** @var string Бенефициар **/
    public $BENEFICIAR;

    /** @var string Счет бенефициара **/
    public $BENEFICIARACCOUNT;

    /** @var string Адрес бенефициара **/
    public $BENEFICIARADDRESS;

    /** @var string Код страны бенефициара **/
    public $BENEFICIARCOUNTRYCODE;

    /** @var string Код бенефициара **/
    public $BENEFICIARFISCALCODE;

    /** @var string Город бенефициара **/
    public $BENEFICIARPLACE;

    /** @var string Счет комиссии **/
    public $CHARGESACCOUNT;

    /** @var string Счет банка списания комиссии **/
    public $CHARGESBANKACCOUNT;

    /** @var string Адрес банка списания комиссии **/
    public $CHARGESBANKADDRESS;

    /** @var string Код банка списания комиссии **/
    public $CHARGESBANKBIC;

    /** @var string Тип кода банка списания комиссии **/
    public $CHARGESBANKBICTYPE;

    /** @var string Код страны банка списания комиссии **/
    public $CHARGESBANKCOUNTRYCODE;

    /** @var string Название банка списания комиссии **/
    public $CHARGESBANKNAME;

    /** @var string Город банка списания комиссии **/
    public $CHARGESBANKPLACE;

    /** @var string Валюта списания комиссии **/
    public $CHARGESCURRCODE;

    /** @var string Тип списания комиссии **/
    public $CHARGESTYPE;

    /** @var string Валюта суммы **/
    public $CURRCODE;

    /** @var PayDocCurGroundDocument[] Обосновывающие документы **/
    public $GROUNDDOCUMENTS = [];

    /** @var string Счет банка посредника **/
    public $IMEDIABANKACCOUNT;

    /** @var string Адрес банка посредника **/
    public $IMEDIABANKADDRESS;

    /** @var string КОД банка посредника **/
    public $IMEDIABANKBIC;

    /** @var string Тип кода банка посредника **/
    public $IMEDIABANKBICTYPE;

    /** @var string Код страны банка посредника **/
    public $IMEDIABANKCOUNTRYCODE;

    /** @var string Название банка посредника **/
    public $IMEDIABANKNAME;

    /** @var string Город банка посредника **/
    public $IMEDIABANKPLACE;

    /** @var integer Не используется **/
    public $ISADDITIONALINFO;

    /** @var integer Мультивалютный документ 0 – нет 1- да **/
    public $ISMULTICURR;

    /** @var string Факс ответственного исполнителя **/
    public $OFFICIALSFAX;

    /** @var string Телефон ответственного исполнителя **/
    public $OFFICIALSPHONE;

    /** @var string Вид операции **/
    public $OPERCODE;

    /** @var \DateTime Срок платежа **/
    public $PAYUNTIL;

    /** @var string Перевододателя **/
    public $PAYER;

    /** @var string Счет перевододателя **/
    public $PAYERACCOUNT;

    /** @var string Адрес перевододателя **/
    public $PAYERADDRESS;

    /** @var string Счет банка перевододателя **/
    public $PAYERBANKACCOUNT;

    /** @var string Адрес банка перевододателя **/
    public $PAYERBANKADDRESS;

    /** @var string КОД банка перевододателя **/
    public $PAYERBANKBIC;

    /** @var string Тип кода банка перевододателя **/
    public $PAYERBANKBICTYPE;

    /** @var string Код страны банка перевододателя **/
    public $PAYERBANKCOUNTRYCODE;

    /** @var string Название банка перевододателя **/
    public $PAYERBANKNAME;

    /** @var string Город банка перевододателя **/
    public $PAYERBANKPLACE;

    /** @var string Код страны перевододателя **/
    public $PAYERCOUNTRYCODE;

    /** @var string Код перевододателя **/
    public $PAYERFISCALCODE;

    /** @var string Город перевододателя **/
    public $PAYERPLACE;

    /** @var string Код основания валютного платежа. **/
    public $PAYMENTCODETYPE;

    /** @var integer Тип исполнения платежа. **/
    public $PAYMENTEXECUTIONTYPE;

    /** @var string Код вида оплаты **/
    public $PAYMENTTYPE;

    /** @var string Назначения платежа **/
    public $PAYMENTSDETAILS;

    /** @var string Очередность платежа **/
    public $URGENT;

    /** @var string Код ОКПО перевододателя **/
    public $PAYEROKPOCODE;

    /** @var float Сумма перевода **/
    public $AMOUNTTRANSFER;

    /** @var string Код валюты перевода **/
    public $CURRCODETRANSFER;

    /** @var float Кросс-курс конвертации по заявке **/
    public $REQUESTCONVRATE;

    /** @var string Счет списания комиссии за конвертацию **/
    public $CONVCHARGEACCOUNT;

    /** @var string Реквизиты ПП на уплату НДС **/
    public $TAXCONFIRMPAYDOC;

    /** @var string Номер справки о вал. операциях на вал. перевод **/
    public $CURRDEALINQUIRYNUMBER;

    /** @var \DateTime Дата справки о вал. операциях на вал. перевод **/
    public $CURRDEALINQUIRYDATE;

    /** @var string Доп. информация для валютного контроля **/
    public $ADDINFOVALCONTROL;

    /** @var integer Тип курса по поручению (курс банка – 0, заданный пользователем – 1, льготный курс - 2) **/
    public $REQUESTRATETYPE;

    /** @var string Соглашения с банком **/
    public $BANKAGREEMENT;

    /** @var PayDocCurOperCode[] Виды валютной операции **/
    public $OPERCODEBLOB = [];

    /** @var string Код сообщения **/
    public $CODEMESSAGE;

    /** @var string Сообщение для банка **/
    public $MESSAGEFORBANK;

    /** @var string ИД способа исполнения перевода валюты **/
    public $INDEXOFSERVICE;

    /** @var string Описание способа исполнения перевода валюты **/
    public $TYPEOFSERVICE;

    /** @var BSDocumentAttachment[] Вложения файлов (формируется по правилу, приведенному в п. 3.3.8) **/
    public $DOCATTACHMENT = [];

    /** @var string Структура **/
    public $MBA_BUDGETNAME;

    /** @var string Структурное подразделение **/
    public $MBA_BUDGRECEIVERNAME;

    /** @var \DateTime Дата договора **/
    public $MBA_CONTRACTDATE;

    /** @var string Номер договора **/
    public $MBA_CONTRACTNUMBER;

    /** @var string Центр ответственности **/
    public $MBA_ESTIMATENAME;

    /** @var string Аналитический классификатор **/
    public $MBA_KESRCODE;

    /** @var string Проект **/
    public $MBA_PROJECTNUMBER;

    /** @var string Id первого классификатора плюс **/
    public $MBA_CLASSIFIER1ID;

    /** @var string Id второго классификатора плюс **/
    public $MBA_CLASSIFIER2ID;

    /** @var string Id третьего классификатора плюс **/
    public $MBA_CLASSIFIER3ID;

    /** @var string Id четвертого классификатора плюс **/
    public $MBA_CLASSIFIER4ID;

    /** @var string Id пятого классификатора плюс **/
    public $MBA_CLASSIFIER5ID;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'DOCUMENTDATE' => new DateField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Дата документа',
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'DOCUMENTNUMBER' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Номер документа',
                    'length'      => 15,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'CUSTID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ID организации',
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'SENDEROFFICIALS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Ответственный исполнитель',
                    'length'      => 40,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'ADDITIONALINFO' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дополнительная информация.',
                    'length'      => 210,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'KBOPID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ID подразделения банка',
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'AMOUNT' => new MoneyField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Сумма',
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'BENEFBANKACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Счет банка бенефициара.',
                    'length'      => 35,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'BENEFBANKADDRESS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Адрес банка бенефициара',
                    'length'      => 255,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'BENEFBANKBIC' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'КОД банка бенефициара',
                    'length'      => 15,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'BENEFBANKBICTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип КОД-а банка бенефициара',
                    'length'      => 20,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'BENEFBANKCOUNTRYCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код страны банка бенефициара',
                    'length'      => 3,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'BENEFBANKNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Название банка бенефициара',
                    'length'      => 255,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'BENEFBANKPLACE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Город банка бенефициара',
                    'length'      => 35,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'BENEFICIAR' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Бенефициар',
                    'length'      => 255,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'BENEFICIARACCOUNT' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Счет бенефициара',
                    'length'      => 35,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'BENEFICIARADDRESS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Адрес бенефициара',
                    'length'      => 255,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'BENEFICIARCOUNTRYCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код страны бенефициара',
                    'length'      => 3,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'BENEFICIARFISCALCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код бенефициара',
                    'length'      => 15,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'BENEFICIARPLACE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Город бенефициара',
                    'length'      => 35,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'CHARGESACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Счет комиссии',
                    'length'      => 35,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'CHARGESBANKACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Счет банка списания комиссии',
                    'length'      => 35,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'CHARGESBANKADDRESS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Адрес банка списания комиссии',
                    'length'      => 255,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'CHARGESBANKBIC' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код банка списания комиссии',
                    'length'      => 15,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'CHARGESBANKBICTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип кода банка списания комиссии',
                    'length'      => 20,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'CHARGESBANKCOUNTRYCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код страны банка списания комиссии',
                    'length'      => 3,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'CHARGESBANKNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Название банка списания комиссии',
                    'length'      => 255,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'CHARGESBANKPLACE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Город банка списания комиссии',
                    'length'      => 35,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'CHARGESCURRCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Валюта списания комиссии',
                    'length'      => 3,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'CHARGESTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип списания комиссии',
                    'length'      => 10,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'CURRCODE' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Валюта суммы',
                    'length'      => 3,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'GROUNDDOCUMENTS' => new BlobTableField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Обосновывающие документы',
                    'recordType'  => 'PayDocCurGroundDocument',
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'IMEDIABANKACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Счет банка посредника',
                    'length'      => 35,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'IMEDIABANKADDRESS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Адрес банка посредника',
                    'length'      => 255,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'IMEDIABANKBIC' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'КОД банка посредника',
                    'length'      => 15,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'IMEDIABANKBICTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип кода банка посредника',
                    'length'      => 20,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'IMEDIABANKCOUNTRYCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код страны банка посредника',
                    'length'      => 3,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'IMEDIABANKNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Название банка посредника',
                    'length'      => 255,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'IMEDIABANKPLACE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Город банка посредника',
                    'length'      => 35,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'ISADDITIONALINFO' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Не используется',
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'ISMULTICURR' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Мультивалютный документ 0 – нет 1- да',
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'OFFICIALSFAX' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Факс ответственного исполнителя',
                    'length'      => 20,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'OFFICIALSPHONE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Телефон ответственного исполнителя',
                    'length'      => 20,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'OPERCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Вид операции',
                    'length'      => 10,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'PAYUNTIL' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Срок платежа',
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'PAYER' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Перевододателя',
                    'length'      => 255,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'PAYERACCOUNT' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Счет перевододателя',
                    'length'      => 35,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'PAYERADDRESS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Адрес перевододателя',
                    'length'      => 255,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'PAYERBANKACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Счет банка перевододателя',
                    'length'      => 35,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'PAYERBANKADDRESS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Адрес банка перевододателя',
                    'length'      => 255,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'PAYERBANKBIC' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'КОД банка перевододателя',
                    'length'      => 15,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'PAYERBANKBICTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип кода банка перевододателя',
                    'length'      => 20,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'PAYERBANKCOUNTRYCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код страны банка перевододателя',
                    'length'      => 3,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'PAYERBANKNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Название банка перевододателя',
                    'length'      => 255,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'PAYERBANKPLACE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Город банка перевододателя',
                    'length'      => 35,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'PAYERCOUNTRYCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код страны перевододателя',
                    'length'      => 3,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'PAYERFISCALCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код перевододателя',
                    'length'      => 15,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'PAYERPLACE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Город перевододателя',
                    'length'      => 35,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'PAYMENTCODETYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код основания валютного платежа.',
                    'length'      => 2,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'PAYMENTEXECUTIONTYPE' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип исполнения платежа.',
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'PAYMENTTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код вида оплаты',
                    'length'      => 2,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'PAYMENTSDETAILS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Назначения платежа',
                    'length'      => 210,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'URGENT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Очередность платежа',
                    'length'      => 2,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'PAYEROKPOCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код ОКПО перевододателя',
                    'length'      => 20,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'AMOUNTTRANSFER' => new MoneyField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Сумма перевода',
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'CURRCODETRANSFER' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Код валюты перевода',
                    'length'      => 3,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'REQUESTCONVRATE' => new DoubleField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Кросс-курс конвертации по заявке',
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'CONVCHARGEACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Счет списания комиссии за конвертацию',
                    'length'      => 25,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'TAXCONFIRMPAYDOC' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Реквизиты ПП на уплату НДС',
                    'length'      => 255,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'CURRDEALINQUIRYNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер справки о вал. операциях на вал. перевод',
                    'length'      => 15,
                    'versions'    => [5, 8, 632],
                ]),
                'CURRDEALINQUIRYDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата справки о вал. операциях на вал. перевод',
                    'versions'    => [5, 8, 632],
                ]),
                'ADDINFOVALCONTROL' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Доп. информация для валютного контроля',
                    'length'      => 255,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'REQUESTRATETYPE' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип курса по поручению (курс банка – 0, заданный пользователем – 1, льготный курс - 2)',
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'BANKAGREEMENT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Соглашения с банком',
                    'length'      => null,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'OPERCODEBLOB' => new BlobTableField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Виды валютной операции',
                    'recordType'  => 'PayDocCurOperCode',
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'CODEMESSAGE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код сообщения',
                    'length'      => 50,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'MESSAGEFORBANK' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сообщение для банка',
                    'length'      => 255,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'INDEXOFSERVICE' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ИД способа исполнения перевода валюты',
                    'length'      => 3,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'TYPEOFSERVICE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Описание способа исполнения перевода валюты',
                    'length'      => 255,
                    'versions'    => [5, 6, 8, 9, 632, 633],
                ]),
                'DOCATTACHMENT' => new AttachmentField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Вложения файлов (формируется по правилу, приведенному в п. 3.3.8)',
                    'versions'    => [6, 8, 9, 633],
                ]),
                'MBA_BUDGETNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Структура',
                    'length'      => 255,
                    'versions'    => [632, 633],
                ]),
                'MBA_BUDGRECEIVERNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Структурное подразделение',
                    'length'      => 255,
                    'versions'    => [632, 633],
                ]),
                'MBA_CONTRACTDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата договора',
                    'versions'    => [632, 633],
                ]),
                'MBA_CONTRACTNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер договора',
                    'length'      => 60,
                    'versions'    => [632, 633],
                ]),
                'MBA_ESTIMATENAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Центр ответственности',
                    'length'      => 255,
                    'versions'    => [632, 633],
                ]),
                'MBA_KESRCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Аналитический классификатор',
                    'length'      => 20,
                    'versions'    => [632, 633],
                ]),
                'MBA_PROJECTNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Проект',
                    'length'      => 50,
                    'versions'    => [632, 633],
                ]),
                'MBA_CLASSIFIER1ID' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Id первого классификатора плюс',
                    'length'      => 20,
                    'versions'    => [632, 633],
                ]),
                'MBA_CLASSIFIER2ID' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Id второго классификатора плюс',
                    'length'      => 20,
                    'versions'    => [632, 633],
                ]),
                'MBA_CLASSIFIER3ID' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Id третьего классификатора плюс',
                    'length'      => 20,
                    'versions'    => [632, 633],
                ]),
                'MBA_CLASSIFIER4ID' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Id четвертого классификатора плюс',
                    'length'      => 20,
                    'versions'    => [632, 633],
                ]),
                'MBA_CLASSIFIER5ID' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Id пятого классификатора плюс',
                    'length'      => 20,
                    'versions'    => [632, 633],
                ]),
            ]
        );
    }
}
