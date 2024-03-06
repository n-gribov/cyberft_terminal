<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\DateTimeField;
use common\models\vtbxml\documents\fields\DoubleField;
use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class ConfDocInquiry138IConfDocPS
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class ConfDocInquiry138IConfDocPS extends BSDocument
{
    const TYPE = 'ConfDocInquiry138IConfDocPS';
    const TYPE_ID = null;
    const VERSIONS = [7];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        7 => ['DOCDATE', 'DOCCODE', 'CURRCODE1', 'AMOUNTCURRENCY1', 'CURRCODE2', 'AMOUNTCURRENCY2', 'ADDINFO', 'NUM', 'DOCUMENTNUMBER', 'FDELIVERY', 'EXPECTDATE', 'COUNTRYCODE', 'REMARK', 'AMOUNTCURRENCY11', 'AMOUNTCURRENCY21', 'CORRECTION'],
    ];

    /** @var \DateTime Дата документа **/
    public $DOCDATE;

    /** @var string Код вида документа **/
    public $DOCCODE;

    /** @var string Код валюты документа **/
    public $CURRCODE1;

    /** @var float Сумма документа **/
    public $AMOUNTCURRENCY1;

    /** @var string Код валюты цены контракта **/
    public $CURRCODE2;

    /** @var float Сумма в валюте цены контракта **/
    public $AMOUNTCURRENCY2;

    /** @var string Дополнительная информация **/
    public $ADDINFO;

    /** @var integer № строки **/
    public $NUM;

    /** @var string № документа **/
    public $DOCUMENTNUMBER;

    /** @var integer Признак поставки **/
    public $FDELIVERY;

    /** @var \DateTime Ожидаемый срок **/
    public $EXPECTDATE;

    /** @var string Код страны грузоотправителя (грузополучателя) **/
    public $COUNTRYCODE;

    /** @var string Примечание **/
    public $REMARK;

    /** @var float Сумма документа (часть 2) **/
    public $AMOUNTCURRENCY11;

    /** @var float Сумма в валюте цены контракта (часть 2) **/
    public $AMOUNTCURRENCY21;

    /** @var \DateTime Признак корректировки (в формате даты) **/
    public $CORRECTION;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'DOCDATE' => new DateTimeField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата документа',
                    'versions'    => [7],
                ]),
                'DOCCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код вида документа',
                    'length'      => 5,
                    'versions'    => [7],
                ]),
                'CURRCODE1' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код валюты документа',
                    'length'      => 3,
                    'versions'    => [7],
                ]),
                'AMOUNTCURRENCY1' => new DoubleField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма документа',
                    'versions'    => [7],
                ]),
                'CURRCODE2' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код валюты цены контракта',
                    'length'      => 3,
                    'versions'    => [7],
                ]),
                'AMOUNTCURRENCY2' => new DoubleField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма в валюте цены контракта',
                    'versions'    => [7],
                ]),
                'ADDINFO' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дополнительная информация',
                    'length'      => 255,
                    'versions'    => [7],
                ]),
                'NUM' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => '№ строки',
                    'versions'    => [7],
                ]),
                'DOCUMENTNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => '№ документа',
                    'length'      => 50,
                    'versions'    => [7],
                ]),
                'FDELIVERY' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Признак поставки',
                    'versions'    => [7],
                ]),
                'EXPECTDATE' => new DateTimeField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Ожидаемый срок',
                    'versions'    => [7],
                ]),
                'COUNTRYCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код страны грузоотправителя (грузополучателя)',
                    'length'      => 3,
                    'versions'    => [7],
                ]),
                'REMARK' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Примечание',
                    'length'      => 255,
                    'versions'    => [7],
                ]),
                'AMOUNTCURRENCY11' => new DoubleField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма документа (часть 2)',
                    'versions'    => [7],
                ]),
                'AMOUNTCURRENCY21' => new DoubleField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма в валюте цены контракта (часть 2)',
                    'versions'    => [7],
                ]),
                'CORRECTION' => new DateTimeField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Признак корректировки (в формате даты)',
                    'versions'    => [7],
                ]),
            ]
        );
    }
}
