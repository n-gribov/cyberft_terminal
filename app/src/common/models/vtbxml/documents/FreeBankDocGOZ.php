<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\AttachmentField;
use common\models\vtbxml\documents\fields\DateField;
use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class FreeBankDocGOZ
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class FreeBankDocGOZ extends BSDocument
{
    const TYPE = 'FreeBankDocGOZ';
    const TYPE_ID = 3306;
    const VERSIONS = [1];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        1 => ['CUSTID', 'DESTCUSTID', 'SENDEROFFICIALS', 'DOCUMENTDATE', 'DOCUMENTNUMBER', 'DOCTYPE', 'DOCATTACHMENT', 'DOCNAME', 'DOCTEXT', 'RECIPIENT', 'ACCOUNT', 'PAYNUMBER', 'PAYDATE'],
    ];

    /** @var integer ID подразделения **/
    public $CUSTID;

    /** @var integer ID организации **/
    public $DESTCUSTID;

    /** @var string Ответственный исполнитель **/
    public $SENDEROFFICIALS;

    /** @var \DateTime Дата документа **/
    public $DOCUMENTDATE;

    /** @var string Номер документа **/
    public $DOCUMENTNUMBER;

    /** @var integer Тип документа, заполняется пустым значением. **/
    public $DOCTYPE;

    /** @var BSDocumentAttachment[] Файлы вложения. (формируется по правилу, приведенному в п. 3.3.8) **/
    public $DOCATTACHMENT = [];

    /** @var string Название документа **/
    public $DOCNAME;

    /** @var string Текст документа **/
    public $DOCTEXT;

    /** @var string Лицо, которому предназначен документ **/
    public $RECIPIENT;

    /** @var string Номер счета **/
    public $ACCOUNT;

    /** @var \DateTime Номер связанного платёжного поручения **/
    public $PAYNUMBER;

    /** @var string Дата связанного платёжного поручения **/
    public $PAYDATE;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'CUSTID' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'ID подразделения',
                    'versions'    => [1],
                ]),
                'DESTCUSTID' => new IntegerField([
                    'isRequired'  => false,
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
                'DOCUMENTDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата документа',
                    'versions'    => [1],
                ]),
                'DOCUMENTNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер документа',
                    'length'      => 15,
                    'versions'    => [1],
                ]),
                'DOCTYPE' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип документа, заполняется пустым значением.',
                    'versions'    => [1],
                ]),
                'DOCATTACHMENT' => new AttachmentField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Файлы вложения. (формируется по правилу, приведенному в п. 3.3.8)',
                    'versions'    => [1],
                ]),
                'DOCNAME' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Название документа',
                    'length'      => 255,
                    'versions'    => [1],
                ]),
                'DOCTEXT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Текст документа',
                    'length'      => null,
                    'versions'    => [1],
                ]),
                'RECIPIENT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Лицо, которому предназначен документ',
                    'length'      => 80,
                    'versions'    => [1],
                ]),
                'ACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер счета',
                    'length'      => 25,
                    'versions'    => [1],
                ]),
                'PAYNUMBER' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер связанного платёжного поручения',
                    'versions'    => [1],
                ]),
                'PAYDATE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата связанного платёжного поручения',
                    'length'      => 15,
                    'versions'    => [1],
                ]),
            ]
        );
    }
}
