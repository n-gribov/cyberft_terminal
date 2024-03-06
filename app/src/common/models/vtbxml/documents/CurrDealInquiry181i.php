<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\BlobTableField;
use common\models\vtbxml\documents\fields\DateField;
use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class CurrDealInquiry181i
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class CurrDealInquiry181i extends BSDocument
{
    const TYPE = 'CurrDealInquiry181i';
    const TYPE_ID = 1050;
    const VERSIONS = [7];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        7 => ['DOCUMENTDATE', 'DOCUMENTNUMBER', 'CUSTID', 'SENDEROFFICIALS', 'CUSTOMERNAME', 'CUSTOMERPROPERTYTYPE', 'CUSTOMERINN', 'CUSTOMERTYPE', 'PHONEOFFICIAL', 'FAXOFFICIAL', 'CUSTOMERBANKBIC', 'CUSTOMERBANKNAME', 'CUSTOMERBANKPLACETYPE', 'CUSTOMERBANKPLACE', 'CUSTOMERBANKCORRACCOUNT', 'DEALINFOBLOB', 'ADDINFO', 'RESERV1', 'KBOPID', 'BANKVKFULLNAME', 'ACCOUNT', 'BANKCOUNTRYCODE', 'FCORRECTION', 'ATTACHMENT', 'CODEMESSAGE', 'MESSAGEFORBANK'],
    ];

    /** @var \DateTime Дата документа **/
    public $DOCUMENTDATE;

    /** @var string Номер документа **/
    public $DOCUMENTNUMBER;

    /** @var integer ID организации **/
    public $CUSTID;

    /** @var string Ответственный исполнитель **/
    public $SENDEROFFICIALS;

    /** @var string Наименование организации **/
    public $CUSTOMERNAME;

    /** @var string Форма собственности организации **/
    public $CUSTOMERPROPERTYTYPE;

    /** @var string ИНН организации **/
    public $CUSTOMERINN;

    /** @var integer Тип организации **/
    public $CUSTOMERTYPE;

    /** @var string Тел. Ответственного лица **/
    public $PHONEOFFICIAL;

    /** @var string Факс ответственного лица **/
    public $FAXOFFICIAL;

    /** @var string БИК банка клиента **/
    public $CUSTOMERBANKBIC;

    /** @var string Наименование банка клиента **/
    public $CUSTOMERBANKNAME;

    /** @var string Тип нас. пункта банка клиента **/
    public $CUSTOMERBANKPLACETYPE;

    /** @var string Нас. пункт банка клиента **/
    public $CUSTOMERBANKPLACE;

    /** @var string Кор. счет банка клиента **/
    public $CUSTOMERBANKCORRACCOUNT;

    /** @var CurrDealInquiry181iDealInfo[] BLOB таблица записей о вал. операциях **/
    public $DEALINFOBLOB = [];

    /** @var string Резервное поле **/
    public $ADDINFO;

    /** @var string Резервное поле **/
    public $RESERV1;

    /** @var integer Идентификатор принадлежности к подразделению банка **/
    public $KBOPID;

    /** @var string Наименование уполномоченного банка **/
    public $BANKVKFULLNAME;

    /** @var string Номер счета резидента **/
    public $ACCOUNT;

    /** @var string Код страны банка-нерезидента **/
    public $BANKCOUNTRYCODE;

    /** @var integer Признак корректировки **/
    public $FCORRECTION;

    /** @var string Вложение **/
    public $ATTACHMENT;

    /** @var string Код сообщения **/
    public $CODEMESSAGE;

    /** @var string Сообщение для банка **/
    public $MESSAGEFORBANK;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'DOCUMENTDATE' => new DateField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Дата документа',
                    'versions'    => [7],
                ]),
                'DOCUMENTNUMBER' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Номер документа',
                    'length'      => 15,
                    'versions'    => [7],
                ]),
                'CUSTID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ID организации',
                    'versions'    => [7],
                ]),
                'SENDEROFFICIALS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Ответственный исполнитель',
                    'length'      => 40,
                    'versions'    => [7],
                ]),
                'CUSTOMERNAME' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Наименование организации',
                    'length'      => 255,
                    'versions'    => [7],
                ]),
                'CUSTOMERPROPERTYTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Форма собственности организации',
                    'length'      => 10,
                    'versions'    => [7],
                ]),
                'CUSTOMERINN' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'ИНН организации',
                    'length'      => 35,
                    'versions'    => [7],
                ]),
                'CUSTOMERTYPE' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип организации',
                    'versions'    => [7],
                ]),
                'PHONEOFFICIAL' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тел. Ответственного лица',
                    'length'      => 20,
                    'versions'    => [7],
                ]),
                'FAXOFFICIAL' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Факс ответственного лица',
                    'length'      => 20,
                    'versions'    => [7],
                ]),
                'CUSTOMERBANKBIC' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'БИК банка клиента',
                    'length'      => 9,
                    'versions'    => [7],
                ]),
                'CUSTOMERBANKNAME' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Наименование банка клиента',
                    'length'      => 255,
                    'versions'    => [7],
                ]),
                'CUSTOMERBANKPLACETYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип нас. пункта банка клиента',
                    'length'      => 5,
                    'versions'    => [7],
                ]),
                'CUSTOMERBANKPLACE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Нас. пункт банка клиента',
                    'length'      => 25,
                    'versions'    => [7],
                ]),
                'CUSTOMERBANKCORRACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Кор. счет банка клиента',
                    'length'      => 25,
                    'versions'    => [7],
                ]),
                'DEALINFOBLOB' => new BlobTableField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'BLOB таблица записей о вал. операциях',
                    'recordType'  => 'CurrDealInquiry181iDealInfo',
                    'versions'    => [7],
                ]),
                'ADDINFO' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Резервное поле',
                    'length'      => 255,
                    'versions'    => [7],
                ]),
                'RESERV1' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Резервное поле',
                    'length'      => 255,
                    'versions'    => [7],
                ]),
                'KBOPID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Идентификатор принадлежности к подразделению банка',
                    'versions'    => [7],
                ]),
                'BANKVKFULLNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Наименование уполномоченного банка',
                    'length'      => 255,
                    'versions'    => [7],
                ]),
                'ACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер счета резидента',
                    'length'      => 32,
                    'versions'    => [7],
                ]),
                'BANKCOUNTRYCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код страны банка-нерезидента',
                    'length'      => 3,
                    'versions'    => [7],
                ]),
                'FCORRECTION' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Признак корректировки',
                    'versions'    => [7],
                ]),
                'ATTACHMENT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Вложение',
                    'length'      => null,
                    'versions'    => [7],
                ]),
                'CODEMESSAGE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код сообщения',
                    'length'      => 50,
                    'versions'    => [7],
                ]),
                'MESSAGEFORBANK' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сообщение для банка',
                    'length'      => 255,
                    'versions'    => [7],
                ]),
            ]
        );
    }
}
