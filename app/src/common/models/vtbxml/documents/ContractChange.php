<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\AttachmentField;
use common\models\vtbxml\documents\fields\BlobTableField;
use common\models\vtbxml\documents\fields\DateField;
use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class ContractChange
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class ContractChange extends BSDocument
{
    const TYPE = 'ContractChange';
    const TYPE_ID = 1048;
    const VERSIONS = [1];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        1 => ['DOCUMENTDATE', 'DOCUMENTNUMBER', 'CUSTID', 'SENDEROFFICIALS', 'KBOPID', 'CUSTOMERBANKNAME', 'BANKVKFULLNAME', 'CUSTOMERNAME', 'CUSTOMERINN', 'PHONEOFFICIALS', 'DOCATTACHMENT', 'PSROWS', 'PSDATA', 'PSDOCS'],
    ];

    /** @var \DateTime Дата документа **/
    public $DOCUMENTDATE;

    /** @var string Номер документа **/
    public $DOCUMENTNUMBER;

    /** @var integer Уникальный номер юр. лица **/
    public $CUSTID;

    /** @var string Ответственный исполнитель инициатора **/
    public $SENDEROFFICIALS;

    /** @var integer Идентификатор принадлежности к подразделению банка **/
    public $KBOPID;

    /** @var string Наименование подразделения банка-получателя документа **/
    public $CUSTOMERBANKNAME;

    /** @var string Наименование уполномоченного банка **/
    public $BANKVKFULLNAME;

    /** @var string Наименование резидента **/
    public $CUSTOMERNAME;

    /** @var string ИНН резидента **/
    public $CUSTOMERINN;

    /** @var string Телефон ответственного исполнителя **/
    public $PHONEOFFICIALS;

    /** @var BSDocumentAttachment[] Вложения файлов. (формируется по правилу, приведенному в п. 3.3.8) **/
    public $DOCATTACHMENT = [];

    /** @var ContractChangePSRow[] Перечень изменяемых контрактов (кредитных договоров) **/
    public $PSROWS = [];

    /** @var ContractChangePSData[] Содержание изменений в контрактах (кредитных договорах) **/
    public $PSDATA = [];

    /** @var ContractChangePSDoc[] Перечень документов-оснований для внесения изменений в контрактах (кредитных договорах) **/
    public $PSDOCS = [];

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
                    'description' => 'Уникальный номер юр. лица',
                    'versions'    => [1],
                ]),
                'SENDEROFFICIALS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Ответственный исполнитель инициатора',
                    'length'      => 40,
                    'versions'    => [1],
                ]),
                'KBOPID' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Идентификатор принадлежности к подразделению банка',
                    'versions'    => [1],
                ]),
                'CUSTOMERBANKNAME' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Наименование подразделения банка-получателя документа',
                    'length'      => 255,
                    'versions'    => [1],
                ]),
                'BANKVKFULLNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Наименование уполномоченного банка',
                    'length'      => 255,
                    'versions'    => [1],
                ]),
                'CUSTOMERNAME' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Наименование резидента',
                    'length'      => 255,
                    'versions'    => [1],
                ]),
                'CUSTOMERINN' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'ИНН резидента',
                    'length'      => 12,
                    'versions'    => [1],
                ]),
                'PHONEOFFICIALS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Телефон ответственного исполнителя',
                    'length'      => 20,
                    'versions'    => [1],
                ]),
                'DOCATTACHMENT' => new AttachmentField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Вложения файлов. (формируется по правилу, приведенному в п. 3.3.8)',
                    'versions'    => [1],
                ]),
                'PSROWS' => new BlobTableField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Перечень изменяемых контрактов (кредитных договоров)',
                    'recordType'  => 'ContractChangePSRow',
                    'versions'    => [1],
                ]),
                'PSDATA' => new BlobTableField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Содержание изменений в контрактах (кредитных договорах)',
                    'recordType'  => 'ContractChangePSData',
                    'versions'    => [1],
                ]),
                'PSDOCS' => new BlobTableField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Перечень документов-оснований для внесения изменений в контрактах (кредитных договорах)',
                    'recordType'  => 'ContractChangePSDoc',
                    'versions'    => [1],
                ]),
            ]
        );
    }
}
