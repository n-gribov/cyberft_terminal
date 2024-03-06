<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\AttachmentField;
use common\models\vtbxml\documents\fields\BlobTableField;
use common\models\vtbxml\documents\fields\DateField;
use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class ConfDocInquiry138I
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class ConfDocInquiry138I extends BSDocument
{
    const TYPE = 'ConfDocInquiry138I';
    const TYPE_ID = 1012;
    const VERSIONS = [7];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        7 => ['DOCUMENTDATE', 'DOCUMENTNUMBER', 'CUSTID', 'SENDEROFFICIALS', 'CUSTOMERBANKNAME', 'CUSTOMERBANKBIC', 'CUSTOMERPROPERTYTYPE', 'CUSTOMERNAME', 'CUSTOMERINN', 'CUSTOMERTYPE', 'PHONEOFFICIALS', 'PSNUMBER', 'CONTRACTNUMBER', 'CONTRACTDATE', 'ISCONTRACTNUMBER', 'CONFDOCPSBLOB', 'CONFDOCNOTPSBLOB', 'ADDINFO', 'KBOPID', 'BANKVKFULLNAME', 'FCORRECTION', 'CORRECTIONNUM'],
    ];

    /** @var \DateTime Дата документа **/
    public $DOCUMENTDATE;

    /** @var string Номер документа **/
    public $DOCUMENTNUMBER;

    /** @var integer ID организации **/
    public $CUSTID;

    /** @var string Ответственный исполнитель **/
    public $SENDEROFFICIALS;

    /** @var string Наименование банка Клиента **/
    public $CUSTOMERBANKNAME;

    /** @var string БИК банка клиента **/
    public $CUSTOMERBANKBIC;

    /** @var string Форма собственности организации-клиента **/
    public $CUSTOMERPROPERTYTYPE;

    /** @var string Наименование организации-клиента **/
    public $CUSTOMERNAME;

    /** @var string ИНН организации-клиента **/
    public $CUSTOMERINN;

    /** @var integer Тип организации-клиента **/
    public $CUSTOMERTYPE;

    /** @var string Телефон ответственного сотрудника **/
    public $PHONEOFFICIALS;

    /** @var string Уникальный номер контракта (кредитного договора) **/
    public $PSNUMBER;

    /** @var string Номер договора (контракта) **/
    public $CONTRACTNUMBER;

    /** @var \DateTime Дата договора (контракта) **/
    public $CONTRACTDATE;

    /** @var integer Признак номера договора (с номером/без номера) **/
    public $ISCONTRACTNUMBER;

    /** @var ConfDocInquiry138IConfDocPS[] Блоб-таблица, содержащая информацию о подтверждающих документах для паспортизируемых сделок **/
    public $CONFDOCPSBLOB = [];

    /** @var ConfDocInquiry138IConfDocNotPS[] Блоб-таблица, содержащая информацию о подтверждающих документах для не паспортизируемых сделок (не используется) **/
    public $CONFDOCNOTPSBLOB = [];

    /** @var string Дополнительная информация **/
    public $ADDINFO;

    /** @var integer ID подразделения **/
    public $KBOPID;

    /** @var string Наименование уполномоченного банка **/
    public $BANKVKFULLNAME;

    /** @var integer Признак корректировки **/
    public $FCORRECTION;

    /** @var integer Номер корректировки **/
    public $CORRECTIONNUM;

    /** @var BSDocumentAttachment[] Вложения файлов (формируется по правилу, приведенному в п. 3.3.8) **/
    public $DOCATTACHMENT = [];

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
                'CUSTOMERBANKNAME' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Наименование банка Клиента',
                    'length'      => 255,
                    'versions'    => [7],
                ]),
                'CUSTOMERBANKBIC' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'БИК банка клиента',
                    'length'      => 9,
                    'versions'    => [7],
                ]),
                'CUSTOMERPROPERTYTYPE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Форма собственности организации-клиента',
                    'length'      => 10,
                    'versions'    => [7],
                ]),
                'CUSTOMERNAME' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Наименование организации-клиента',
                    'length'      => 255,
                    'versions'    => [7],
                ]),
                'CUSTOMERINN' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'ИНН организации-клиента',
                    'length'      => 14,
                    'versions'    => [7],
                ]),
                'CUSTOMERTYPE' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип организации-клиента',
                    'versions'    => [7],
                ]),
                'PHONEOFFICIALS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Телефон ответственного сотрудника',
                    'length'      => 20,
                    'versions'    => [7],
                ]),
                'PSNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Уникальный номер контракта (кредитного договора)',
                    'length'      => 50,
                    'versions'    => [7],
                ]),
                'CONTRACTNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер договора (контракта)',
                    'length'      => 50,
                    'versions'    => [7],
                ]),
                'CONTRACTDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата договора (контракта)',
                    'versions'    => [7],
                ]),
                'ISCONTRACTNUMBER' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Признак номера договора (с номером/без номера)',
                    'versions'    => [7],
                ]),
                'CONFDOCPSBLOB' => new BlobTableField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Блоб-таблица, содержащая информацию о подтверждающих документах для паспортизируемых сделок',
                    'recordType'  => 'ConfDocInquiry138IConfDocPS',
                    'versions'    => [7],
                ]),
                'CONFDOCNOTPSBLOB' => new BlobTableField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Блоб-таблица, содержащая информацию о подтверждающих документах для не паспортизируемых сделок (не используется)',
                    'recordType'  => 'ConfDocInquiry138IConfDocNotPS',
                    'versions'    => [7],
                ]),
                'ADDINFO' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дополнительная информация',
                    'length'      => 255,
                    'versions'    => [7],
                ]),
                'KBOPID' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'ID подразделения',
                    'versions'    => [7],
                ]),
                'BANKVKFULLNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Наименование уполномоченного банка',
                    'length'      => 255,
                    'versions'    => [7],
                ]),
                'FCORRECTION' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Признак корректировки',
                    'versions'    => [7],
                ]),
                'CORRECTIONNUM' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер корректировки',
                    'versions'    => [7],
                ]),
                'DOCATTACHMENT' => new AttachmentField([
                    'isRequired'  => false,
                    'isSigned'    => false,
                    'description' => 'Вложения файлов (формируется по правилу, приведенному в п. 3.3.8)',
                    'versions'    => [7],
                ]),
            ]
        );
    }
}
