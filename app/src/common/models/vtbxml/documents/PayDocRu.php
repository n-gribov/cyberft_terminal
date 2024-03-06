<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\DateField;
use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\MoneyField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class PayDocRu
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class PayDocRu extends BSDocument
{
    const TYPE = 'PayDocRu';
    const TYPE_ID = 1;
    const VERSIONS = [5, 7, 632];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        5 => ['DOCUMENTDATE', 'DOCUMENTNUMBER', 'CUSTID', 'SENDEROFFICIALS', 'CURRCODE', 'PAYER', 'PAYERPROPERTYTYPE', 'ORDERPAYER', 'PAYERINN', 'PAYERKPP', 'PAYERACCOUNT', 'ORDERPAYERACCOUNT', 'PAYERBIC', 'PAYERCORRACCOUNT', 'PAYERBANKNAME', 'PAYERBANKTYPE', 'PAYERPLACE', 'PAYERPLACETYPE', 'RECEIVER', 'RECEIVERPROPERTYTYPE', 'ORDERRECEIVER', 'RECEIVERINN', 'RECEIVERACCOUNT', 'ORDERRECEIVERACCOUNT', 'RECEIVERBIC', 'RECEIVERCORRACCOUNT', 'RECEIVERBANKNAME', 'RECEIVERBANKTYPE', 'RECEIVERPLACE', 'RECEIVERPLACETYPE', 'AMOUNT', 'NDS', 'GROUND', 'PAYMENTURGENT', 'PAYUNTIL', 'OPERTYPE', 'SENDTYPE', 'KNFCODE', 'RECEIVERKPP', 'STAT1256', 'CBCCODE', 'OKATOCODE', 'PAYGRNDPARAM', 'TAXPERIODPARAM1', 'TAXPERIODPARAM2', 'TAXPERIODPARAM3', 'DOCNUMPARAM1', 'DOCNUMPARAM2', 'DOCDATEPARAM1', 'DOCDATEPARAM2', 'DOCDATEPARAM3', 'PAYTYPEPARAM', 'KBOPID', 'SENDTYPECODE'],
        7 => ['DOCUMENTDATE', 'DOCUMENTNUMBER', 'CUSTID', 'SENDEROFFICIALS', 'CURRCODE', 'PAYER', 'PAYERPROPERTYTYPE', 'ORDERPAYER', 'PAYERINN', 'PAYERKPP', 'PAYERACCOUNT', 'ORDERPAYERACCOUNT', 'PAYERBIC', 'PAYERCORRACCOUNT', 'PAYERBANKNAME', 'PAYERBANKTYPE', 'PAYERPLACE', 'PAYERPLACETYPE', 'RECEIVER', 'RECEIVERPROPERTYTYPE', 'ORDERRECEIVER', 'RECEIVERINN', 'RECEIVERACCOUNT', 'ORDERRECEIVERACCOUNT', 'RECEIVERBIC', 'RECEIVERCORRACCOUNT', 'RECEIVERBANKNAME', 'RECEIVERBANKTYPE', 'RECEIVERPLACE', 'RECEIVERPLACETYPE', 'AMOUNT', 'NDS', 'GROUND', 'PAYMENTURGENT', 'PAYUNTIL', 'OPERTYPE', 'SENDTYPE', 'KNFCODE', 'RECEIVERKPP', 'STAT1256', 'CBCCODE', 'OKATOCODE', 'PAYGRNDPARAM', 'TAXPERIODPARAM1', 'TAXPERIODPARAM2', 'TAXPERIODPARAM3', 'DOCNUMPARAM1', 'DOCNUMPARAM2', 'DOCDATEPARAM1', 'DOCDATEPARAM2', 'DOCDATEPARAM3', 'PAYTYPEPARAM', 'KBOPID', 'SENDTYPECODE', 'CODEMESSAGE', 'MESSAGEFORBANK', 'CODEUIP'],
        632 => ['DOCUMENTDATE', 'DOCUMENTNUMBER', 'CUSTID', 'SENDEROFFICIALS', 'CURRCODE', 'PAYER', 'PAYERPROPERTYTYPE', 'ORDERPAYER', 'PAYERINN', 'PAYERKPP', 'PAYERACCOUNT', 'ORDERPAYERACCOUNT', 'PAYERBIC', 'PAYERCORRACCOUNT', 'PAYERBANKNAME', 'PAYERBANKTYPE', 'PAYERPLACE', 'PAYERPLACETYPE', 'RECEIVER', 'RECEIVERPROPERTYTYPE', 'ORDERRECEIVER', 'RECEIVERINN', 'RECEIVERACCOUNT', 'ORDERRECEIVERACCOUNT', 'RECEIVERBIC', 'RECEIVERCORRACCOUNT', 'RECEIVERBANKNAME', 'RECEIVERBANKTYPE', 'RECEIVERPLACE', 'RECEIVERPLACETYPE', 'AMOUNT', 'NDS', 'GROUND', 'PAYMENTURGENT', 'PAYUNTIL', 'OPERTYPE', 'SENDTYPE', 'KNFCODE', 'RECEIVERKPP', 'STAT1256', 'CBCCODE', 'OKATOCODE', 'PAYGRNDPARAM', 'TAXPERIODPARAM1', 'TAXPERIODPARAM2', 'TAXPERIODPARAM3', 'DOCNUMPARAM1', 'DOCNUMPARAM2', 'DOCDATEPARAM1', 'DOCDATEPARAM2', 'DOCDATEPARAM3', 'PAYTYPEPARAM', 'KBOPID', 'CODEMESSAGE', 'MESSAGEFORBANK', 'MBA_BUDGETNAME', 'MBA_BUDGRECEIVERNAME', 'MBA_CONTRACTDATE', 'MBA_CONTRACTNUMBER', 'MBA_ESTIMATENAME', 'MBA_KESRCODE', 'MBA_CLASSIFIER1ID', 'MBA_CLASSIFIER2ID', 'MBA_PROJECTNUMBER', 'MBA_CLASSIFIER3ID', 'MBA_CLASSIFIER4ID', 'MBA_CLASSIFIER5ID', 'CODEUIP'],
    ];

    /** @var \DateTime Дата документа **/
    public $DOCUMENTDATE;

    /** @var string Номер документа **/
    public $DOCUMENTNUMBER;

    /** @var integer ID организации **/
    public $CUSTID;

    /** @var string Ответственный исполнитель **/
    public $SENDEROFFICIALS;

    /** @var string Цифровой код валюты (810) **/
    public $CURRCODE;

    /** @var string Название плательщика **/
    public $PAYER;

    /** @var string Тип собственности плательщика **/
    public $PAYERPROPERTYTYPE;

    /** @var string Не заполняется **/
    public $ORDERPAYER;

    /** @var string ИНН плательщика **/
    public $PAYERINN;

    /** @var string КПП плательщика **/
    public $PAYERKPP;

    /** @var string Счет плательщика **/
    public $PAYERACCOUNT;

    /** @var string Не заполняется **/
    public $ORDERPAYERACCOUNT;

    /** @var string БИК банка плательщика **/
    public $PAYERBIC;

    /** @var string Кор. счет банка плательщика **/
    public $PAYERCORRACCOUNT;

    /** @var string Название банка плательщика **/
    public $PAYERBANKNAME;

    /** @var string Тип банка плательщика (Не заполняется) **/
    public $PAYERBANKTYPE;

    /** @var string Населенный пункт банка плательщика **/
    public $PAYERPLACE;

    /** @var string Тип населённого пункта банка плательщика **/
    public $PAYERPLACETYPE;

    /** @var string Получатель **/
    public $RECEIVER;

    /** @var string Тип собственности получателя **/
    public $RECEIVERPROPERTYTYPE;

    /** @var string Не заполняется **/
    public $ORDERRECEIVER;

    /** @var string ИНН получателя **/
    public $RECEIVERINN;

    /** @var string Счет получателя **/
    public $RECEIVERACCOUNT;

    /** @var string Не заполняется **/
    public $ORDERRECEIVERACCOUNT;

    /** @var string БИК банка получателя **/
    public $RECEIVERBIC;

    /** @var string Кор. счет банка получателя **/
    public $RECEIVERCORRACCOUNT;

    /** @var string Название банка получателя **/
    public $RECEIVERBANKNAME;

    /** @var string Тип банка получателя (Не заполняется) **/
    public $RECEIVERBANKTYPE;

    /** @var string Населенный пункт банка получателя **/
    public $RECEIVERPLACE;

    /** @var string Тип населённого пункта банка получателя **/
    public $RECEIVERPLACETYPE;

    /** @var float Сумма **/
    public $AMOUNT;

    /** @var float Сумма НДС **/
    public $NDS;

    /** @var string Назначение платежа **/
    public $GROUND;

    /** @var string Очередность платежа **/
    public $PAYMENTURGENT;

    /** @var \DateTime Срок платежа (Не заполняется) **/
    public $PAYUNTIL;

    /** @var string Вид операции (01) **/
    public $OPERTYPE;

    /** @var string Способ отправки (пусто, срочно) **/
    public $SENDTYPE;

    /** @var string Не используется **/
    public $KNFCODE;

    /** @var string КПП получателя **/
    public $RECEIVERKPP;

    /** @var string Статус налогоплательщика (101) **/
    public $STAT1256;

    /** @var string Код КБК (104) **/
    public $CBCCODE;

    /** @var string Код ОКАТО (105) **/
    public $OKATOCODE;

    /** @var string Показатель основания платежа (106) **/
    public $PAYGRNDPARAM;

    /** @var string Показатель налогового периода (107) или код таможни **/
    public $TAXPERIODPARAM1;

    /** @var string Показатель налогового периода (107) **/
    public $TAXPERIODPARAM2;

    /** @var string Показатель налогового периода (107) **/
    public $TAXPERIODPARAM3;

    /** @var string Не заполняется **/
    public $DOCNUMPARAM1;

    /** @var string Показатель номера документа (108) **/
    public $DOCNUMPARAM2;

    /** @var string Показатель даты документа (109) **/
    public $DOCDATEPARAM1;

    /** @var string Показатель даты документа (109) **/
    public $DOCDATEPARAM2;

    /** @var string Показатель даты документа (109) **/
    public $DOCDATEPARAM3;

    /** @var string Показатель типа платежа (110) **/
    public $PAYTYPEPARAM;

    /** @var integer ID подразделения банка **/
    public $KBOPID;

    /** @var integer Код вида платежа **/
    public $SENDTYPECODE;

    /** @var string Код сообщения **/
    public $CODEMESSAGE;

    /** @var string Сообщение для банка **/
    public $MESSAGEFORBANK;

    /** @var string УИП (поле 22 «Код») **/
    public $CODEUIP;

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

    /** @var string Id первого классификатора плюс **/
    public $MBA_CLASSIFIER1ID;

    /** @var string Id второго классификатора плюс **/
    public $MBA_CLASSIFIER2ID;

    /** @var string Проект **/
    public $MBA_PROJECTNUMBER;

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
                    'versions'    => [5, 7, 632],
                ]),
                'DOCUMENTNUMBER' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Номер документа',
                    'length'      => 15,
                    'versions'    => [5, 7, 632],
                ]),
                'CUSTID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ID организации',
                    'versions'    => [5, 7, 632],
                ]),
                'SENDEROFFICIALS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Ответственный исполнитель',
                    'length'      => 40,
                    'versions'    => [5, 7, 632],
                ]),
                'CURRCODE' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Цифровой код валюты (810)',
                    'length'      => 3,
                    'versions'    => [5, 7, 632],
                ]),
                'PAYER' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Название плательщика',
                    'length'      => 160,
                    'versions'    => [5, 7, 632],
                ]),
                'PAYERPROPERTYTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип собственности плательщика',
                    'length'      => 10,
                    'versions'    => [5, 7, 632],
                ]),
                'ORDERPAYER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Не заполняется',
                    'length'      => 160,
                    'versions'    => [5, 7, 632],
                ]),
                'PAYERINN' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'ИНН плательщика',
                    'length'      => 14,
                    'versions'    => [5, 7, 632],
                ]),
                'PAYERKPP' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'КПП плательщика',
                    'length'      => 9,
                    'versions'    => [5, 7, 632],
                ]),
                'PAYERACCOUNT' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Счет плательщика',
                    'length'      => 25,
                    'versions'    => [5, 7, 632],
                ]),
                'ORDERPAYERACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Не заполняется',
                    'length'      => 25,
                    'versions'    => [5, 7, 632],
                ]),
                'PAYERBIC' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'БИК банка плательщика',
                    'length'      => 9,
                    'versions'    => [5, 7, 632],
                ]),
                'PAYERCORRACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Кор. счет банка плательщика',
                    'length'      => 25,
                    'versions'    => [5, 7, 632],
                ]),
                'PAYERBANKNAME' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Название банка плательщика',
                    'length'      => 80,
                    'versions'    => [5, 7, 632],
                ]),
                'PAYERBANKTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип банка плательщика (Не заполняется)',
                    'length'      => 10,
                    'versions'    => [5, 7, 632],
                ]),
                'PAYERPLACE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Населенный пункт банка плательщика',
                    'length'      => 25,
                    'versions'    => [5, 7, 632],
                ]),
                'PAYERPLACETYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип населённого пункта банка плательщика',
                    'length'      => 5,
                    'versions'    => [5, 7, 632],
                ]),
                'RECEIVER' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Получатель',
                    'length'      => 160,
                    'versions'    => [5, 7, 632],
                ]),
                'RECEIVERPROPERTYTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип собственности получателя',
                    'length'      => 10,
                    'versions'    => [5, 7, 632],
                ]),
                'ORDERRECEIVER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Не заполняется',
                    'length'      => 160,
                    'versions'    => [5, 7, 632],
                ]),
                'RECEIVERINN' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'ИНН получателя',
                    'length'      => 14,
                    'versions'    => [5, 7, 632],
                ]),
                'RECEIVERACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Счет получателя',
                    'length'      => 25,
                    'versions'    => [5, 7, 632],
                ]),
                'ORDERRECEIVERACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Не заполняется',
                    'length'      => 25,
                    'versions'    => [5, 7, 632],
                ]),
                'RECEIVERBIC' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'БИК банка получателя',
                    'length'      => 9,
                    'versions'    => [5, 7, 632],
                ]),
                'RECEIVERCORRACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Кор. счет банка получателя',
                    'length'      => 25,
                    'versions'    => [5, 7, 632],
                ]),
                'RECEIVERBANKNAME' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Название банка получателя',
                    'length'      => 80,
                    'versions'    => [5, 7, 632],
                ]),
                'RECEIVERBANKTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип банка получателя (Не заполняется)',
                    'length'      => 10,
                    'versions'    => [5, 7, 632],
                ]),
                'RECEIVERPLACE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Населенный пункт банка получателя',
                    'length'      => 25,
                    'versions'    => [5, 7, 632],
                ]),
                'RECEIVERPLACETYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип населённого пункта банка получателя',
                    'length'      => 5,
                    'versions'    => [5, 7, 632],
                ]),
                'AMOUNT' => new MoneyField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Сумма',
                    'versions'    => [5, 7, 632],
                ]),
                'NDS' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма НДС',
                    'versions'    => [5, 7, 632],
                ]),
                'GROUND' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Назначение платежа',
                    'length'      => 255,
                    'versions'    => [5, 7, 632],
                ]),
                'PAYMENTURGENT' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Очередность платежа',
                    'length'      => 2,
                    'versions'    => [5, 7, 632],
                ]),
                'PAYUNTIL' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Срок платежа (Не заполняется)',
                    'versions'    => [5, 7, 632],
                ]),
                'OPERTYPE' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Вид операции (01)',
                    'length'      => 2,
                    'versions'    => [5, 7, 632],
                ]),
                'SENDTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Способ отправки (пусто, срочно)',
                    'length'      => 15,
                    'versions'    => [5, 7, 632],
                ]),
                'KNFCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Не используется',
                    'length'      => 3,
                    'versions'    => [5, 7, 632],
                ]),
                'RECEIVERKPP' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'КПП получателя',
                    'length'      => 9,
                    'versions'    => [5, 7, 632],
                ]),
                'STAT1256' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Статус налогоплательщика (101)',
                    'length'      => 2,
                    'versions'    => [5, 7, 632],
                ]),
                'CBCCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код КБК (104)',
                    'length'      => 20,
                    'versions'    => [5, 7, 632],
                ]),
                'OKATOCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код ОКАТО (105)',
                    'length'      => 11,
                    'versions'    => [5, 7, 632],
                ]),
                'PAYGRNDPARAM' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Показатель основания платежа (106)',
                    'length'      => 2,
                    'versions'    => [5, 7, 632],
                ]),
                'TAXPERIODPARAM1' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Показатель налогового периода (107) или код таможни',
                    'length'      => 8,
                    'versions'    => [5, 7, 632],
                ]),
                'TAXPERIODPARAM2' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Показатель налогового периода (107)',
                    'length'      => 2,
                    'versions'    => [5, 7, 632],
                ]),
                'TAXPERIODPARAM3' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Показатель налогового периода (107)',
                    'length'      => 4,
                    'versions'    => [5, 7, 632],
                ]),
                'DOCNUMPARAM1' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Не заполняется',
                    'length'      => 2,
                    'versions'    => [5, 7, 632],
                ]),
                'DOCNUMPARAM2' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Показатель номера документа (108)',
                    'length'      => 15,
                    'versions'    => [5, 7, 632],
                ]),
                'DOCDATEPARAM1' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Показатель даты документа (109)',
                    'length'      => 2,
                    'versions'    => [5, 7, 632],
                ]),
                'DOCDATEPARAM2' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Показатель даты документа (109)',
                    'length'      => 2,
                    'versions'    => [5, 7, 632],
                ]),
                'DOCDATEPARAM3' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Показатель даты документа (109)',
                    'length'      => 4,
                    'versions'    => [5, 7, 632],
                ]),
                'PAYTYPEPARAM' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Показатель типа платежа (110)',
                    'length'      => 2,
                    'versions'    => [5, 7, 632],
                ]),
                'KBOPID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ID подразделения банка',
                    'versions'    => [5, 7, 632],
                ]),
                'SENDTYPECODE' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Код вида платежа',
                    'versions'    => [5, 7],
                ]),
                'CODEMESSAGE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код сообщения',
                    'length'      => 50,
                    'versions'    => [7, 632],
                ]),
                'MESSAGEFORBANK' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сообщение для банка',
                    'length'      => 255,
                    'versions'    => [7, 632],
                ]),
                'CODEUIP' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'УИП (поле 22 «Код»)',
                    'length'      => 50,
                    'versions'    => [7, 632],
                ]),
                'MBA_BUDGETNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Структура',
                    'length'      => 255,
                    'versions'    => [632],
                ]),
                'MBA_BUDGRECEIVERNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Структурное подразделение',
                    'length'      => 255,
                    'versions'    => [632],
                ]),
                'MBA_CONTRACTDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата договора',
                    'versions'    => [632],
                ]),
                'MBA_CONTRACTNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер договора',
                    'length'      => 60,
                    'versions'    => [632],
                ]),
                'MBA_ESTIMATENAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Центр ответственности',
                    'length'      => 255,
                    'versions'    => [632],
                ]),
                'MBA_KESRCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Аналитический классификатор',
                    'length'      => 20,
                    'versions'    => [632],
                ]),
                'MBA_CLASSIFIER1ID' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Id первого классификатора плюс',
                    'length'      => 20,
                    'versions'    => [632],
                ]),
                'MBA_CLASSIFIER2ID' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Id второго классификатора плюс',
                    'length'      => 20,
                    'versions'    => [632],
                ]),
                'MBA_PROJECTNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Проект',
                    'length'      => 50,
                    'versions'    => [632],
                ]),
                'MBA_CLASSIFIER3ID' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Id третьего классификатора плюс',
                    'length'      => 20,
                    'versions'    => [632],
                ]),
                'MBA_CLASSIFIER4ID' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Id четвертого классификатора плюс',
                    'length'      => 20,
                    'versions'    => [632],
                ]),
                'MBA_CLASSIFIER5ID' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Id пятого классификатора плюс',
                    'length'      => 20,
                    'versions'    => [632],
                ]),
            ]
        );
    }
}
