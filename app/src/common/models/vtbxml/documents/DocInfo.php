<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\BlobTableField;
use common\models\vtbxml\documents\fields\DateField;
use common\models\vtbxml\documents\fields\DoubleField;
use common\models\vtbxml\documents\fields\MoneyField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class DocInfo
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class DocInfo extends BSDocument
{
    const TYPE = 'DocInfo';
    const TYPE_ID = null;
    const VERSIONS = [1];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        1 => ['VALUEDATE', 'RECEIVEROFFICIALS', 'NOTEFROMBANK', 'DOCREF', 'ACCEPTDATE', 'CURRRATE', 'CHARGEAMOUNT', 'CHARGEPERCENT', 'RATEPERCENT', 'AMOUNTRURTOTAL', 'AMOUNTCURTOTAL', 'AMOUNTCOVER', 'AMOUNTDEBETTOTAL', 'AMOUNTCREDITTOTAL', 'AMOUNTCHARGE', 'CURRCODECHARGE', 'CURRCODETRANSFERTOTAL', 'AMOUNTTRANSFERTOTAL', 'AMOUNTSELLRUR', 'AMOUNTTOTALFACT', 'CURRCODEFACT', 'SALARYCHECK', 'DPDATEBANK', 'DPNUM1BANK', 'DPNUM4BANK', 'RETURNDATE'],
    ];

    /** @var \DateTime Дата валютирования **/
    public $VALUEDATE;

    /** @var string Ответственный исполнитель обработчика **/
    public $RECEIVEROFFICIALS;

    /** @var string Сообщение принимающей стороны **/
    public $NOTEFROMBANK;

    /** @var string Референс документа **/
    public $DOCREF;

    /** @var \DateTime Дата приема документа к исполнению **/
    public $ACCEPTDATE;

    /** @var float Курс сделки **/
    public $CURRRATE;

    /** @var float Сумма комиссии **/
    public $CHARGEAMOUNT;

    /** @var float Процент комиссии **/
    public $CHARGEPERCENT;

    /** @var float Курсовая разница в процентах **/
    public $RATEPERCENT;

    /** @var float Сумма списанных рублей **/
    public $AMOUNTRURTOTAL;

    /** @var float Сумма купленной валюты **/
    public $AMOUNTCURTOTAL;

    /** @var float Сумма рублевого покрытия, перечисляемого банку **/
    public $AMOUNTCOVER;

    /** @var float Сумма списанной валюты **/
    public $AMOUNTDEBETTOTAL;

    /** @var float Сумма купленной валюты / Сумма зачисления на валютный счет (факт) **/
    public $AMOUNTCREDITTOTAL;

    /** @var float Сумма комиссии (факт) **/
    public $AMOUNTCHARGE;

    /** @var string Код валюты суммы комиссии (факт) **/
    public $CURRCODECHARGE;

    /** @var string Код валюты сумма списания (факт) **/
    public $CURRCODETRANSFERTOTAL;

    /** @var float Общая сумма списания денежных средств с транзитного счета(факт) **/
    public $AMOUNTTRANSFERTOTAL;

    /** @var float Сумма продажи денежных средств в рублях (факт) **/
    public $AMOUNTSELLRUR;

    /** @var float Сумма зачисления на зарплатный счет (факт) **/
    public $AMOUNTTOTALFACT;

    /** @var string Код валюты суммы зачисления (факт) **/
    public $CURRCODEFACT;

    /** @var DocInfoSalaryCheck[] Информация о проверке начислений **/
    public $SALARYCHECK = [];

    /** @var \DateTime Дата ПС, заполняемая банком **/
    public $DPDATEBANK;

    /** @var string Первая часть номера ПС, заполняемая банком **/
    public $DPNUM1BANK;

    /** @var string Четвертая часть номера ПС, заполняемая банком **/
    public $DPNUM4BANK;

    /** @var \DateTime Дата возврата **/
    public $RETURNDATE;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'VALUEDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата валютирования',
                    'versions'    => [1],
                ]),
                'RECEIVEROFFICIALS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Ответственный исполнитель обработчика',
                    'length'      => 40,
                    'versions'    => [1],
                ]),
                'NOTEFROMBANK' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сообщение принимающей стороны',
                    'length'      => 255,
                    'versions'    => [1],
                ]),
                'DOCREF' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Референс документа',
                    'length'      => 32,
                    'versions'    => [1],
                ]),
                'ACCEPTDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата приема документа к исполнению',
                    'versions'    => [1],
                ]),
                'CURRRATE' => new DoubleField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Курс сделки',
                    'versions'    => [1],
                ]),
                'CHARGEAMOUNT' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма комиссии',
                    'versions'    => [1],
                ]),
                'CHARGEPERCENT' => new DoubleField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Процент комиссии',
                    'versions'    => [1],
                ]),
                'RATEPERCENT' => new DoubleField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Курсовая разница в процентах',
                    'versions'    => [1],
                ]),
                'AMOUNTRURTOTAL' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма списанных рублей',
                    'versions'    => [1],
                ]),
                'AMOUNTCURTOTAL' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма купленной валюты',
                    'versions'    => [1],
                ]),
                'AMOUNTCOVER' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма рублевого покрытия, перечисляемого банку',
                    'versions'    => [1],
                ]),
                'AMOUNTDEBETTOTAL' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма списанной валюты',
                    'versions'    => [1],
                ]),
                'AMOUNTCREDITTOTAL' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма купленной валюты / Сумма зачисления на валютный счет (факт)',
                    'versions'    => [1],
                ]),
                'AMOUNTCHARGE' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма комиссии (факт)',
                    'versions'    => [1],
                ]),
                'CURRCODECHARGE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код валюты суммы комиссии (факт)',
                    'length'      => 3,
                    'versions'    => [1],
                ]),
                'CURRCODETRANSFERTOTAL' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код валюты сумма списания (факт)',
                    'length'      => 3,
                    'versions'    => [1],
                ]),
                'AMOUNTTRANSFERTOTAL' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Общая сумма списания денежных средств с транзитного счета(факт)',
                    'versions'    => [1],
                ]),
                'AMOUNTSELLRUR' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма продажи денежных средств в рублях (факт)',
                    'versions'    => [1],
                ]),
                'AMOUNTTOTALFACT' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма зачисления на зарплатный счет (факт)',
                    'versions'    => [1],
                ]),
                'CURRCODEFACT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код валюты суммы зачисления (факт)',
                    'length'      => 3,
                    'versions'    => [1],
                ]),
                'SALARYCHECK' => new BlobTableField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Информация о проверке начислений',
                    'recordType'  => 'DocInfoSalaryCheck',
                    'versions'    => [1],
                ]),
                'DPDATEBANK' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата ПС, заполняемая банком',
                    'versions'    => [1],
                ]),
                'DPNUM1BANK' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Первая часть номера ПС, заполняемая банком',
                    'length'      => 8,
                    'versions'    => [1],
                ]),
                'DPNUM4BANK' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Четвертая часть номера ПС, заполняемая банком',
                    'length'      => 1,
                    'versions'    => [1],
                ]),
                'RETURNDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата возврата',
                    'versions'    => [1],
                ]),
            ]
        );
    }
}
