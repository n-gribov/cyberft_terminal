<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\AttachmentField;
use common\models\vtbxml\documents\fields\BlobTableField;
use common\models\vtbxml\documents\fields\DateField;
use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\MoneyField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class ContractReg
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class ContractReg extends BSDocument
{
    const TYPE = 'ContractReg';
    const TYPE_ID = 1042;
    const VERSIONS = [1];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        1 => ['DOCUMENTDATE', 'DOCUMENTNUMBER', 'CUSTID', 'SENDEROFFICIALS', 'KBOPID', 'CUSTOMERBANKNAME', 'CUSTOMERBANKBIC', 'CUSTOMERPROPERTYTYPE', 'CUSTOMERNAME', 'CUSTOMEROGRN', 'CUSTOMERINN', 'CUSTOMERKPP', 'CUSTOMERTYPE', 'LAWSTATE', 'LAWDISTRICT', 'LAWCITY', 'LAWPLACETYPE', 'LAWPLACE', 'LAWSTREET', 'LAWBUILDING', 'LAWBLOCK', 'LAWOFFICE', 'PHONEOFFICIALS', 'DPDATE', 'DPNUM1', 'DPNUM2', 'DPNUM3', 'DPNUM4', 'DPNUM5', 'CONNUMBER', 'ISCONNUMBER', 'CONDATE', 'CONCURRCODE', 'CONCURRNAME', 'CONAMOUNT', 'ISCONAMOUNT', 'CONENDDATE', 'DPNUMBEROTHERBANK', 'BANKVKFULLNAME', 'NONRESIDENTINFO', 'DOCATTACHMENT'],
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

    /** @var \DateTime Дата присвоения уникального номера контракта **/
    public $DPDATE;

    /** @var string Первая часть уникального  номера контракта **/
    public $DPNUM1;

    /** @var string Вторая часть уникального номера контракта **/
    public $DPNUM2;

    /** @var string Третья часть уникального номера контракта **/
    public $DPNUM3;

    /** @var string Четвертая часть уникального номера контракта **/
    public $DPNUM4;

    /** @var string Пятая часть уникального номера контракта **/
    public $DPNUM5;

    /** @var string Номер контракта **/
    public $CONNUMBER;

    /** @var integer Признак номера контракта (с номером – 0/без номера – 1) **/
    public $ISCONNUMBER;

    /** @var \DateTime Дата контракта **/
    public $CONDATE;

    /** @var string Код валюты цены контракта **/
    public $CONCURRCODE;

    /** @var string Наименование валюты цены контракта **/
    public $CONCURRNAME;

    /** @var float Сумма контракта **/
    public $CONAMOUNT;

    /** @var integer Признак суммы контракта (с суммой – 0/без суммы – 1) **/
    public $ISCONAMOUNT;

    /** @var \DateTime Дата завершения исполнения обязательств по контракту **/
    public $CONENDDATE;

    /** @var string Сведения о ранее присвоенном контракту уникальном номере **/
    public $DPNUMBEROTHERBANK;

    /** @var string Наименование банка УК **/
    public $BANKVKFULLNAME;

    /** @var ContractRegNonresidentInfo[] БЛОБ таблица: Реквизиты нерезидента (нерезидентов) **/
    public $NONRESIDENTINFO = [];

    /** @var BSDocumentAttachment[] Вложения файлов (формируется по правилу, приведенному в п. 3.3.8) **/
    public $DOCATTACHMENT = [];

    /** @var \DateTime Дата представления документов для постановки контракта на учет **/
    public $SENDDATEBEFOREISSUECLIENT;

    /** @var string Способ представления документов для постановки контракта на учет **/
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
                    'versions'    => [1],
                ]),
                'DOCUMENTNUMBER' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Номер документа',
                    'length'      => 15,
                    'versions'    => [1],
                ]),
                'CUSTID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ID организации',
                    'versions'    => [1],
                ]),
                'SENDEROFFICIALS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Ответственный исполнитель',
                    'length'      => 40,
                    'versions'    => [1],
                ]),
                'KBOPID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ID подразделения',
                    'versions'    => [1],
                ]),
                'CUSTOMERBANKNAME' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Наименование уполномоченного банка',
                    'length'      => 255,
                    'versions'    => [1],
                ]),
                'CUSTOMERBANKBIC' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'БИК банка клиента',
                    'length'      => 9,
                    'versions'    => [1],
                ]),
                'CUSTOMERPROPERTYTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Форма собственности организации-резидента',
                    'length'      => 10,
                    'versions'    => [1],
                ]),
                'CUSTOMERNAME' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Наименование организации-резидента',
                    'length'      => 255,
                    'versions'    => [1],
                ]),
                'CUSTOMEROGRN' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'ОГРН организации-резидента',
                    'length'      => 30,
                    'versions'    => [1],
                ]),
                'CUSTOMERINN' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'ИНН организации-резидента',
                    'length'      => 12,
                    'versions'    => [1],
                ]),
                'CUSTOMERKPP' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'КПП организации-резидента',
                    'length'      => 9,
                    'versions'    => [1],
                ]),
                'CUSTOMERTYPE' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип организации- резидента 0 – резидент 1 – нерезидент',
                    'versions'    => [1],
                ]),
                'LAWSTATE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Субъект РФ',
                    'length'      => 40,
                    'versions'    => [1],
                ]),
                'LAWDISTRICT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Район',
                    'length'      => 255,
                    'versions'    => [1],
                ]),
                'LAWCITY' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Город',
                    'length'      => 100,
                    'versions'    => [1],
                ]),
                'LAWPLACETYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип населенного пункта',
                    'length'      => 5,
                    'versions'    => [1],
                ]),
                'LAWPLACE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Населенный пункт',
                    'length'      => 35,
                    'versions'    => [1],
                ]),
                'LAWSTREET' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Улица',
                    'length'      => 100,
                    'versions'    => [1],
                ]),
                'LAWBUILDING' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер дома',
                    'length'      => 50,
                    'versions'    => [1],
                ]),
                'LAWBLOCK' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Корпус/строение',
                    'length'      => 20,
                    'versions'    => [1],
                ]),
                'LAWOFFICE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Офис/квартира',
                    'length'      => 20,
                    'versions'    => [1],
                ]),
                'PHONEOFFICIALS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Телефон ответственного исполнителя',
                    'length'      => 20,
                    'versions'    => [1],
                ]),
                'DPDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата присвоения уникального номера контракта',
                    'versions'    => [1],
                ]),
                'DPNUM1' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Первая часть уникального  номера контракта',
                    'length'      => 8,
                    'versions'    => [1],
                ]),
                'DPNUM2' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Вторая часть уникального номера контракта',
                    'length'      => 4,
                    'versions'    => [1],
                ]),
                'DPNUM3' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Третья часть уникального номера контракта',
                    'length'      => 4,
                    'versions'    => [1],
                ]),
                'DPNUM4' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Четвертая часть уникального номера контракта',
                    'length'      => 1,
                    'versions'    => [1],
                ]),
                'DPNUM5' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Пятая часть уникального номера контракта',
                    'length'      => 1,
                    'versions'    => [1],
                ]),
                'CONNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер контракта',
                    'length'      => 50,
                    'versions'    => [1],
                ]),
                'ISCONNUMBER' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Признак номера контракта (с номером – 0/без номера – 1)',
                    'versions'    => [1],
                ]),
                'CONDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата контракта',
                    'versions'    => [1],
                ]),
                'CONCURRCODE' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Код валюты цены контракта',
                    'length'      => 3,
                    'versions'    => [1],
                ]),
                'CONCURRNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Наименование валюты цены контракта',
                    'length'      => 100,
                    'versions'    => [1],
                ]),
                'CONAMOUNT' => new MoneyField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сумма контракта',
                    'versions'    => [1],
                ]),
                'ISCONAMOUNT' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Признак суммы контракта (с суммой – 0/без суммы – 1)',
                    'versions'    => [1],
                ]),
                'CONENDDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата завершения исполнения обязательств по контракту',
                    'versions'    => [1],
                ]),
                'DPNUMBEROTHERBANK' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Сведения о ранее присвоенном контракту уникальном номере',
                    'length'      => 25,
                    'versions'    => [1],
                ]),
                'BANKVKFULLNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Наименование банка УК',
                    'length'      => 255,
                    'versions'    => [1],
                ]),
                'NONRESIDENTINFO' => new BlobTableField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'БЛОБ таблица: Реквизиты нерезидента (нерезидентов)',
                    'recordType'  => 'ContractRegNonresidentInfo',
                    'versions'    => [1],
                ]),
                'DOCATTACHMENT' => new AttachmentField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Вложения файлов (формируется по правилу, приведенному в п. 3.3.8)',
                    'versions'    => [1],
                ]),
                'SENDDATEBEFOREISSUECLIENT' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => false,
                    'description' => 'Дата представления документов для постановки контракта на учет',
                    'versions'    => [1],
                ]),
                'SENDTYPEBEFOREISSUECLIENT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => false,
                    'description' => 'Способ представления документов для постановки контракта на учет',
                    'length'      => 2,
                    'versions'    => [1],
                ]),
                'DATEOGRN' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => false,
                    'description' => 'Дата государственной регистрации',
                    'versions'    => [1],
                ]),
            ]
        );
    }
}
