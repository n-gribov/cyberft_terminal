<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\DateField;
use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\MoneyField;
use common\models\vtbxml\documents\fields\SmallIntField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class StatementRuDocument
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class StatementRuDocument extends BSDocument
{
    const TYPE = 'StatementRuDocument';
    const TYPE_ID = null;
    const VERSIONS = [1, 2];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        1 => ['ACCEPTDATE', 'ACCEPTDOCDATE', 'ACCEPTNUMBER', 'ACCEPTOPERTYPE', 'ACCEPTPARTNUMBER', 'ACCEPTRESTAMOUNT', 'AMOUNT', 'AMOUNTNAT', 'BANKOFFICIALS', 'CBCCODE', 'CASHSYMBOL', 'CURRCODE', 'DOCDATEPARAM', 'DOCNUMPARAM', 'DOCREF', 'DOCUMENTDATE', 'DOCUMENTNUMBER', 'GROUND', 'LINENUMBER', 'NOTEFROMBANK', 'OKATOCODE', 'OPERTYPE', 'ORDERPAYER', 'ORDERPAYERACCOUNT', 'ORDERRECEIVER', 'ORDERRECEIVERACCOUNT', 'PRZO', 'PAYGRNDPARAM', 'PAYTYPEPARAM', 'PAYUNTIL', 'PAYER', 'PAYERACCOUNT', 'PAYERBIC', 'PAYERBANKNAME', 'PAYERBANKTYPE', 'PAYERCORRACCOUNT', 'PAYERINN', 'PAYERKPP', 'PAYERPLACE', 'PAYERPLACETYPE', 'PAYERPROPERTYTYPE', 'PAYMENTURGENT', 'RECEIVER', 'RECEIVERACCOUNT', 'RECEIVERBIC', 'RECEIVERBANKNAME', 'RECEIVERBANKTYPE', 'RECEIVERCORRACCOUNT', 'RECEIVERINN', 'RECEIVERKPP', 'RECEIVERPLACE', 'RECEIVERPLACETYPE', 'RECEIVERPROPERTYTYPE', 'SAVEDDOCREF', 'SENDTYPE', 'STAT1256', 'TAXPERIODPARAM', 'VALUEDATE', 'VIEWFLAGS', 'KBOPID', 'SENDTYPECODE', 'CODEUIP', 'CORRESPACCOUNT'],
        2 => ['ACCEPTDATE', 'ACCEPTDOCDATE', 'ACCEPTNUMBER', 'ACCEPTOPERTYPE', 'ACCEPTPARTNUMBER', 'ACCEPTRESTAMOUNT', 'AMOUNT', 'AMOUNTNAT', 'BANKOFFICIALS', 'CBCCODE', 'CASHSYMBOL', 'CURRCODE', 'DOCDATEPARAM', 'DOCNUMPARAM', 'DOCREF', 'DOCUMENTDATE', 'DOCUMENTNUMBER', 'GROUND', 'LINENUMBER', 'NOTEFROMBANK', 'OKATOCODE', 'OPERTYPE', 'ORDERPAYER', 'ORDERPAYERACCOUNT', 'ORDERRECEIVER', 'ORDERRECEIVERACCOUNT', 'PRZO', 'PAYGRNDPARAM', 'PAYTYPEPARAM', 'PAYUNTIL', 'PAYER', 'PAYERACCOUNT', 'PAYERBIC', 'PAYERBANKNAME', 'PAYERBANKTYPE', 'PAYERCORRACCOUNT', 'PAYERINN', 'PAYERKPP', 'PAYERPLACE', 'PAYERPLACETYPE', 'PAYERPROPERTYTYPE', 'PAYMENTURGENT', 'RECEIVER', 'RECEIVERACCOUNT', 'RECEIVERBIC', 'RECEIVERBANKNAME', 'RECEIVERBANKTYPE', 'RECEIVERCORRACCOUNT', 'RECEIVERINN', 'RECEIVERKPP', 'RECEIVERPLACE', 'RECEIVERPLACETYPE', 'RECEIVERPROPERTYTYPE', 'SAVEDDOCREF', 'SENDTYPE', 'STAT1256', 'TAXPERIODPARAM', 'VALUEDATE', 'VIEWFLAGS', 'KBOPID', 'CODEUIP', 'SENDTYPECODE', 'CORRESPACCOUNT'],
    ];

    /** @var \DateTime Дата оплачиваемого документа (платежный ордер) **/
    public $ACCEPTDATE;

    /** @var \DateTime Дата поступления документа в банк плательщика. **/
    public $ACCEPTDOCDATE;

    /** @var string Номер оплачиваемого документа (платежный ордер) **/
    public $ACCEPTNUMBER;

    /** @var string Вид операции (шифр) оплачиваемого документа (платежный ордер) **/
    public $ACCEPTOPERTYPE;

    /** @var integer Номер частичного платежа по оплате документа (платежный ордер) **/
    public $ACCEPTPARTNUMBER;

    /** @var float Сумма остатка платежа по оплате документа (платежный ордер) **/
    public $ACCEPTRESTAMOUNT;

    /** @var float Сумма. **/
    public $AMOUNT;

    /** @var float Сумма в нац. Валюте **/
    public $AMOUNTNAT;

    /** @var string Исполнитель документа в банке **/
    public $BANKOFFICIALS;

    /** @var string Код КБК **/
    public $CBCCODE;

    /** @var string Символ кассы **/
    public $CASHSYMBOL;

    /** @var string Код валюты **/
    public $CURRCODE;

    /** @var string Показатель даты документа **/
    public $DOCDATEPARAM;

    /** @var string Показатель номера документа **/
    public $DOCNUMPARAM;

    /** @var string Референс транзакции **/
    public $DOCREF;

    /** @var \DateTime Дата документа **/
    public $DOCUMENTDATE;

    /** @var string Номер документа **/
    public $DOCUMENTNUMBER;

    /** @var string Основание платежа **/
    public $GROUND;

    /** @var string Номер строки в выписке банка **/
    public $LINENUMBER;

    /** @var string Примечание банка **/
    public $NOTEFROMBANK;

    /** @var string Код ОКАТО **/
    public $OKATOCODE;

    /** @var string Вид операции **/
    public $OPERTYPE;

    /** @var string Название реального плательщика **/
    public $ORDERPAYER;

    /** @var string Счет реального плательщика **/
    public $ORDERPAYERACCOUNT;

    /** @var string Название реального получателя **/
    public $ORDERRECEIVER;

    /** @var string Счет реального получателя **/
    public $ORDERRECEIVERACCOUNT;

    /** @var string Признак «заключительные обороты» **/
    public $PRZO;

    /** @var string Показатель основания платежа **/
    public $PAYGRNDPARAM;

    /** @var string Показатель типа платежа; **/
    public $PAYTYPEPARAM;

    /** @var \DateTime Срок платежа **/
    public $PAYUNTIL;

    /** @var string Название плательщика **/
    public $PAYER;

    /** @var string Счет плательщика **/
    public $PAYERACCOUNT;

    /** @var string БИК банка плательщика **/
    public $PAYERBIC;

    /** @var string Название банка плательщика **/
    public $PAYERBANKNAME;

    /** @var string Тип банка плательщика **/
    public $PAYERBANKTYPE;

    /** @var string Корсчет плательщика **/
    public $PAYERCORRACCOUNT;

    /** @var string ИНН плательщика **/
    public $PAYERINN;

    /** @var string КПП плательщика **/
    public $PAYERKPP;

    /** @var string Название населенного пункта Банка плательщика **/
    public $PAYERPLACE;

    /** @var string Краткое название типа населенного пункта Банка плательщика **/
    public $PAYERPLACETYPE;

    /** @var string Форма собственности плательщика **/
    public $PAYERPROPERTYTYPE;

    /** @var string Очередность платежа **/
    public $PAYMENTURGENT;

    /** @var string Название получателя **/
    public $RECEIVER;

    /** @var string Счет получателя **/
    public $RECEIVERACCOUNT;

    /** @var string БИК или Код получателя **/
    public $RECEIVERBIC;

    /** @var string Название банка получателя **/
    public $RECEIVERBANKNAME;

    /** @var string Тип банка получателя **/
    public $RECEIVERBANKTYPE;

    /** @var string Корсчет получателя **/
    public $RECEIVERCORRACCOUNT;

    /** @var string ИНН получателя **/
    public $RECEIVERINN;

    /** @var string КПП получателя **/
    public $RECEIVERKPP;

    /** @var string Название населенного пункта Банка получателя **/
    public $RECEIVERPLACE;

    /** @var string Краткое название типа населенного пункта Банка получателя **/
    public $RECEIVERPLACETYPE;

    /** @var string Форма собственности получателя **/
    public $RECEIVERPROPERTYTYPE;

    /** @var string Референс документа из внешней подсистемы **/
    public $SAVEDDOCREF;

    /** @var string Способ отправки **/
    public $SENDTYPE;

    /** @var string Показатель статуса **/
    public $STAT1256;

    /** @var string Показатель налогового периода **/
    public $TAXPERIODPARAM;

    /** @var \DateTime Дата валютирования **/
    public $VALUEDATE;

    /** @var integer Флаги отображения и печати **/
    public $VIEWFLAGS;

    /** @var integer ID подразделения банка **/
    public $KBOPID;

    /** @var integer код вида платежа **/
    public $SENDTYPECODE;

    /** @var string УИП (поле 22 "Код") **/
    public $CODEUIP;

    /** @var string Номер корреспондирующего счета **/
    public $CORRESPACCOUNT;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'ACCEPTDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата оплачиваемого документа (платежный ордер)',
                    'versions'    => [1, 2],
                ]),
                'ACCEPTDOCDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата поступления документа в банк плательщика.',
                    'versions'    => [1, 2],
                ]),
                'ACCEPTNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер оплачиваемого документа (платежный ордер)',
                    'length'      => 15,
                    'versions'    => [1, 2],
                ]),
                'ACCEPTOPERTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Вид операции (шифр) оплачиваемого документа (платежный ордер)',
                    'length'      => 2,
                    'versions'    => [1, 2],
                ]),
                'ACCEPTPARTNUMBER' => new SmallIntField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер частичного платежа по оплате документа (платежный ордер)',
                    'versions'    => [1, 2],
                ]),
                'ACCEPTRESTAMOUNT' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма остатка платежа по оплате документа (платежный ордер)',
                    'versions'    => [1, 2],
                ]),
                'AMOUNT' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма.',
                    'versions'    => [1, 2],
                ]),
                'AMOUNTNAT' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма в нац. Валюте',
                    'versions'    => [1, 2],
                ]),
                'BANKOFFICIALS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Исполнитель документа в банке',
                    'length'      => 40,
                    'versions'    => [1, 2],
                ]),
                'CBCCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код КБК',
                    'length'      => 20,
                    'versions'    => [1, 2],
                ]),
                'CASHSYMBOL' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Символ кассы',
                    'length'      => 3,
                    'versions'    => [1, 2],
                ]),
                'CURRCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код валюты',
                    'length'      => 3,
                    'versions'    => [1, 2],
                ]),
                'DOCDATEPARAM' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Показатель даты документа',
                    'length'      => 10,
                    'versions'    => [1, 2],
                ]),
                'DOCNUMPARAM' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Показатель номера документа',
                    'length'      => 15,
                    'versions'    => [1, 2],
                ]),
                'DOCREF' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Референс транзакции',
                    'length'      => 32,
                    'versions'    => [1, 2],
                ]),
                'DOCUMENTDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата документа',
                    'versions'    => [1, 2],
                ]),
                'DOCUMENTNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер документа',
                    'length'      => 32,
                    'versions'    => [1, 2],
                ]),
                'GROUND' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Основание платежа',
                    'length'      => 255,
                    'versions'    => [1, 2],
                ]),
                'LINENUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер строки в выписке банка',
                    'length'      => 5,
                    'versions'    => [1, 2],
                ]),
                'NOTEFROMBANK' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Примечание банка',
                    'length'      => 255,
                    'versions'    => [1, 2],
                ]),
                'OKATOCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код ОКАТО',
                    'length'      => 11,
                    'versions'    => [1, 2],
                ]),
                'OPERTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Вид операции',
                    'length'      => 2,
                    'versions'    => [1, 2],
                ]),
                'ORDERPAYER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Название реального плательщика',
                    'length'      => 160,
                    'versions'    => [1, 2],
                ]),
                'ORDERPAYERACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Счет реального плательщика',
                    'length'      => 25,
                    'versions'    => [1, 2],
                ]),
                'ORDERRECEIVER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Название реального получателя',
                    'length'      => 160,
                    'versions'    => [1, 2],
                ]),
                'ORDERRECEIVERACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Счет реального получателя',
                    'length'      => 25,
                    'versions'    => [1, 2],
                ]),
                'PRZO' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Признак «заключительные обороты»',
                    'length'      => 5,
                    'versions'    => [1, 2],
                ]),
                'PAYGRNDPARAM' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Показатель основания платежа',
                    'length'      => 2,
                    'versions'    => [1, 2],
                ]),
                'PAYTYPEPARAM' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Показатель типа платежа;',
                    'length'      => 2,
                    'versions'    => [1, 2],
                ]),
                'PAYUNTIL' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Срок платежа',
                    'versions'    => [1, 2],
                ]),
                'PAYER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Название плательщика',
                    'length'      => 160,
                    'versions'    => [1, 2],
                ]),
                'PAYERACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Счет плательщика',
                    'length'      => 35,
                    'versions'    => [1, 2],
                ]),
                'PAYERBIC' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'БИК банка плательщика',
                    'length'      => 12,
                    'versions'    => [1, 2],
                ]),
                'PAYERBANKNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Название банка плательщика',
                    'length'      => 80,
                    'versions'    => [1, 2],
                ]),
                'PAYERBANKTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип банка плательщика',
                    'length'      => 10,
                    'versions'    => [1, 2],
                ]),
                'PAYERCORRACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Корсчет плательщика',
                    'length'      => 25,
                    'versions'    => [1, 2],
                ]),
                'PAYERINN' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'ИНН плательщика',
                    'length'      => 14,
                    'versions'    => [1, 2],
                ]),
                'PAYERKPP' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'КПП плательщика',
                    'length'      => 9,
                    'versions'    => [1, 2],
                ]),
                'PAYERPLACE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Название населенного пункта Банка плательщика',
                    'length'      => 25,
                    'versions'    => [1, 2],
                ]),
                'PAYERPLACETYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Краткое название типа населенного пункта Банка плательщика',
                    'length'      => 5,
                    'versions'    => [1, 2],
                ]),
                'PAYERPROPERTYTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Форма собственности плательщика',
                    'length'      => 10,
                    'versions'    => [1, 2],
                ]),
                'PAYMENTURGENT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Очередность платежа',
                    'length'      => 2,
                    'versions'    => [1, 2],
                ]),
                'RECEIVER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Название получателя',
                    'length'      => 160,
                    'versions'    => [1, 2],
                ]),
                'RECEIVERACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Счет получателя',
                    'length'      => 35,
                    'versions'    => [1, 2],
                ]),
                'RECEIVERBIC' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'БИК или Код получателя',
                    'length'      => 12,
                    'versions'    => [1, 2],
                ]),
                'RECEIVERBANKNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Название банка получателя',
                    'length'      => 80,
                    'versions'    => [1, 2],
                ]),
                'RECEIVERBANKTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип банка получателя',
                    'length'      => 10,
                    'versions'    => [1, 2],
                ]),
                'RECEIVERCORRACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Корсчет получателя',
                    'length'      => 25,
                    'versions'    => [1, 2],
                ]),
                'RECEIVERINN' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'ИНН получателя',
                    'length'      => 14,
                    'versions'    => [1, 2],
                ]),
                'RECEIVERKPP' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'КПП получателя',
                    'length'      => 9,
                    'versions'    => [1, 2],
                ]),
                'RECEIVERPLACE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Название населенного пункта Банка получателя',
                    'length'      => 25,
                    'versions'    => [1, 2],
                ]),
                'RECEIVERPLACETYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Краткое название типа населенного пункта Банка получателя',
                    'length'      => 5,
                    'versions'    => [1, 2],
                ]),
                'RECEIVERPROPERTYTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Форма собственности получателя',
                    'length'      => 10,
                    'versions'    => [1, 2],
                ]),
                'SAVEDDOCREF' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Референс документа из внешней подсистемы',
                    'length'      => 32,
                    'versions'    => [1, 2],
                ]),
                'SENDTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Способ отправки',
                    'length'      => 15,
                    'versions'    => [1, 2],
                ]),
                'STAT1256' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Показатель статуса',
                    'length'      => 2,
                    'versions'    => [1, 2],
                ]),
                'TAXPERIODPARAM' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Показатель налогового периода',
                    'length'      => 10,
                    'versions'    => [1, 2],
                ]),
                'VALUEDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата валютирования',
                    'versions'    => [1, 2],
                ]),
                'VIEWFLAGS' => new SmallIntField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Флаги отображения и печати',
                    'versions'    => [1, 2],
                ]),
                'KBOPID' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'ID подразделения банка',
                    'versions'    => [1, 2],
                ]),
                'SENDTYPECODE' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'код вида платежа',
                    'versions'    => [1, 2],
                ]),
                'CODEUIP' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'УИП (поле 22 "Код")',
                    'length'      => 50,
                    'versions'    => [1, 2],
                ]),
                'CORRESPACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер корреспондирующего счета',
                    'length'      => 35,
                    'versions'    => [1, 2],
                ]),
            ]
        );
    }
}
