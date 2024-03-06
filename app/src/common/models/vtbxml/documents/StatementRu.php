<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\BlobTableField;
use common\models\vtbxml\documents\fields\DateField;
use common\models\vtbxml\documents\fields\DateTimeField;
use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\MoneyField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class StatementRu
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class StatementRu extends BSDocument
{
    const TYPE = 'StatementRu';
    const TYPE_ID = 3;
    const VERSIONS = [1, 2];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        1 => ['CUSTID', 'DESTCUSTID', 'SENDEROFFICIALS', 'DOCUMENTDATE', 'DOCUMENTNUMBER', 'DATAACTUALITY', 'STATEMENTTYPE', 'CURRCODE', 'ACCOUNT', 'SEQUENCENUMBER', 'STATEMENTDATE', 'OPENINGBALANCE', 'CLOSINGBALANCE', 'CLOSINGAVAILABLEBALANCE', 'DEBETDOCUMENTS', 'DEBETDOCUMENTSNET', 'DEBETTURNOVER', 'CREDITDOCUMENTS', 'CREDITDOCUMENTSNET', 'CREDITTURNOVER'],
        2 => ['CUSTID', 'DESTCUSTID', 'SENDEROFFICIALS', 'DOCUMENTDATE', 'DOCUMENTNUMBER', 'DATAACTUALITY', 'STATEMENTTYPE', 'CURRCODE', 'ACCOUNT', 'SEQUENCENUMBER', 'STATEMENTDATE', 'OPENINGBALANCE', 'CLOSINGBALANCE', 'CLOSINGAVAILABLEBALANCE', 'DEBETDOCUMENTS', 'DEBETDOCUMENTSNET', 'DEBETTURNOVER', 'CREDITDOCUMENTS', 'CREDITDOCUMENTSNET', 'CREDITTURNOVER', 'CREDITDOCSWIFT'],
    ];

    /** @var integer 0 **/
    public $CUSTID;

    /** @var integer ID Клиента в СДБО **/
    public $DESTCUSTID;

    /** @var string Ответственный исполнитель **/
    public $SENDEROFFICIALS;

    /** @var \DateTime Дата документа **/
    public $DOCUMENTDATE;

    /** @var string Номер документа **/
    public $DOCUMENTNUMBER;

    /** @var \DateTime Дата и время подготовки выписки **/
    public $DATAACTUALITY;

    /** @var integer Тип выписки 0-выписка, 1-справка **/
    public $STATEMENTTYPE;

    /** @var string Код валюты **/
    public $CURRCODE;

    /** @var string Счет **/
    public $ACCOUNT;

    /** @var integer Порядковый номер выписки в течении дня по счету **/
    public $SEQUENCENUMBER;

    /** @var \DateTime Дата выписки **/
    public $STATEMENTDATE;

    /** @var float Входящий остаток **/
    public $OPENINGBALANCE;

    /** @var float Исходящий остаток **/
    public $CLOSINGBALANCE;

    /** @var float Доступный исходящий остаток **/
    public $CLOSINGAVAILABLEBALANCE;

    /** @var StatementRuDocument[] Дебетовые документы **/
    public $DEBETDOCUMENTS = [];

    /** @var integer Количество дебетовых документов **/
    public $DEBETDOCUMENTSNET;

    /** @var float Обороты по дебету **/
    public $DEBETTURNOVER;

    /** @var StatementRuDocument[] Кредитовые документы **/
    public $CREDITDOCUMENTS = [];

    /** @var integer Количество кредитовых документов **/
    public $CREDITDOCUMENTSNET;

    /** @var float Обороты по кредиту **/
    public $CREDITTURNOVER;

    /** @var StatementRuCreditDocSwift[] Параметры свифтовых приложений к выписке **/
    public $CREDITDOCSWIFT = [];

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'CUSTID' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => '0',
                    'versions'    => [1, 2],
                ]),
                'DESTCUSTID' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'ID Клиента в СДБО',
                    'versions'    => [1, 2],
                ]),
                'SENDEROFFICIALS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Ответственный исполнитель',
                    'length'      => null,
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
                    'length'      => null,
                    'versions'    => [1, 2],
                ]),
                'DATAACTUALITY' => new DateTimeField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата и время подготовки выписки',
                    'versions'    => [1, 2],
                ]),
                'STATEMENTTYPE' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип выписки 0-выписка, 1-справка',
                    'versions'    => [1, 2],
                ]),
                'CURRCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код валюты',
                    'length'      => null,
                    'versions'    => [1, 2],
                ]),
                'ACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Счет',
                    'length'      => null,
                    'versions'    => [1, 2],
                ]),
                'SEQUENCENUMBER' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Порядковый номер выписки в течении дня по счету',
                    'versions'    => [1, 2],
                ]),
                'STATEMENTDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата выписки',
                    'versions'    => [1, 2],
                ]),
                'OPENINGBALANCE' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Входящий остаток',
                    'versions'    => [1, 2],
                ]),
                'CLOSINGBALANCE' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Исходящий остаток',
                    'versions'    => [1, 2],
                ]),
                'CLOSINGAVAILABLEBALANCE' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Доступный исходящий остаток',
                    'versions'    => [1, 2],
                ]),
                'DEBETDOCUMENTS' => new BlobTableField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дебетовые документы',
                    'recordType'  => 'StatementRuDocument',
                    'versions'    => [1, 2],
                ]),
                'DEBETDOCUMENTSNET' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Количество дебетовых документов',
                    'versions'    => [1, 2],
                ]),
                'DEBETTURNOVER' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Обороты по дебету',
                    'versions'    => [1, 2],
                ]),
                'CREDITDOCUMENTS' => new BlobTableField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Кредитовые документы',
                    'recordType'  => 'StatementRuDocument',
                    'versions'    => [1, 2],
                ]),
                'CREDITDOCUMENTSNET' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Количество кредитовых документов',
                    'versions'    => [1, 2],
                ]),
                'CREDITTURNOVER' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Обороты по кредиту',
                    'versions'    => [1, 2],
                ]),
                'CREDITDOCSWIFT' => new BlobTableField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Параметры свифтовых приложений к выписке',
                    'recordType'  => 'StatementRuCreditDocSwift',
                    'versions'    => [2],
                ]),
            ]
        );
    }
}
