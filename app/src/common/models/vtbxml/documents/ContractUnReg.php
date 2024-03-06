<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\AttachmentField;
use common\models\vtbxml\documents\fields\BlobTableField;
use common\models\vtbxml\documents\fields\DateField;
use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class ContractUnReg
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class ContractUnReg extends BSDocument
{
    const TYPE = 'ContractUnReg';
    const TYPE_ID = 1046;
    const VERSIONS = [3];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        3 => ['DOCUMENTDATE', 'DOCUMENTNUMBER', 'CUSTID', 'SENDEROFFICIALS', 'KBOPID', 'CUSTOMERBANKNAME', 'BANKVKFULLNAME', 'CUSTOMERNAME', 'CUSTOMERINN', 'PHONEOFFICIALS', 'DOCATTACHMENT', 'PSROWS'],
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

    /** @var ContractUnRegPSRow[] Перечень контрактов (кредитных договоров) для снятия с учета **/
    public $PSROWS = [];

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'DOCUMENTDATE' => new DateField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Дата документа',
                    'versions'    => [3],
                ]),
                'DOCUMENTNUMBER' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Номер документа',
                    'length'      => 15,
                    'versions'    => [3],
                ]),
                'CUSTID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Уникальный номер юр. лица',
                    'versions'    => [3],
                ]),
                'SENDEROFFICIALS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Ответственный исполнитель инициатора',
                    'length'      => 40,
                    'versions'    => [3],
                ]),
                'KBOPID' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Идентификатор принадлежности к подразделению банка',
                    'versions'    => [3],
                ]),
                'CUSTOMERBANKNAME' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Наименование подразделения банка-получателя документа',
                    'length'      => 255,
                    'versions'    => [3],
                ]),
                'BANKVKFULLNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Наименование уполномоченного банка',
                    'length'      => 255,
                    'versions'    => [3],
                ]),
                'CUSTOMERNAME' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Наименование резидента',
                    'length'      => 255,
                    'versions'    => [3],
                ]),
                'CUSTOMERINN' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'ИНН резидента',
                    'length'      => 12,
                    'versions'    => [3],
                ]),
                'PHONEOFFICIALS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Телефон ответственного исполнителя',
                    'length'      => 20,
                    'versions'    => [3],
                ]),
                'DOCATTACHMENT' => new AttachmentField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Вложения файлов. (формируется по правилу, приведенному в п. 3.3.8)',
                    'versions'    => [3],
                ]),
                'PSROWS' => new BlobTableField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Перечень контрактов (кредитных договоров) для снятия с учета',
                    'recordType'  => 'ContractUnRegPSRow',
                    'versions'    => [3],
                ]),
            ]
        );
    }
}
