<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\AttachmentField;
use common\models\vtbxml\documents\fields\DateField;
use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class FreeClientDocGOZ
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class FreeClientDocGOZ extends BSDocument
{
    const TYPE = 'FreeClientDocGOZ';
    const TYPE_ID = 3304;
    const VERSIONS = [1];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        1 => ['DOCUMENTDATE', 'DOCUMENTNUMBER', 'CUSTID', 'SENDEROFFICIALS', 'DOCTYPE', 'DOCATTACHMENT', 'DOCNAME', 'DOCTEXT', 'RECIPIENT', 'KBOPID', 'ACCOUNT', 'PAYNUMBER', 'PAYDATE', 'PAYDOCCLIENT', 'PAYDOCDATECREATE', 'PAYDOCTIMECREATE', 'DOCUMENTNUMBERGOZ', 'DOCUMENTDATEGOZ', 'DOCTYPEGOZ'],
    ];

    /** @var \DateTime Дата документа **/
    public $DOCUMENTDATE;

    /** @var string Номер документа **/
    public $DOCUMENTNUMBER;

    /** @var integer ID организации **/
    public $CUSTID;

    /** @var string Ответственный исполнитель **/
    public $SENDEROFFICIALS;

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

    /** @var integer ID подразделения **/
    public $KBOPID;

    /** @var string Номера счета **/
    public $ACCOUNT;

    /** @var string Номер связанного платёжного поручения **/
    public $PAYNUMBER;

    /** @var \DateTime Дата связанного платёжного поручения **/
    public $PAYDATE;

    /** @var integer Системный номер клиента комплекса связанного платежного поручения **/
    public $PAYDOCCLIENT;

    /** @var integer Дата и время создания платежного поручения **/
    public $PAYDOCDATECREATE;

    /** @var integer Время создания платежного поручения **/
    public $PAYDOCTIMECREATE;

    /** @var string Номер подтверждающего документа **/
    public $DOCUMENTNUMBERGOZ;

    /** @var \DateTime Дата подтверждающего документа **/
    public $DOCUMENTDATEGOZ;

    /** @var integer Тип подтверждающего документа **/
    public $DOCTYPEGOZ;

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
                    'isRequired'  => true,
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
                'KBOPID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ID подразделения',
                    'versions'    => [1],
                ]),
                'ACCOUNT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номера счета',
                    'length'      => 25,
                    'versions'    => [1],
                ]),
                'PAYNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Номер связанного платёжного поручения',
                    'length'      => 15,
                    'versions'    => [1],
                ]),
                'PAYDATE' => new DateField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата связанного платёжного поручения',
                    'versions'    => [1],
                ]),
                'PAYDOCCLIENT' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Системный номер клиента комплекса связанного платежного поручения',
                    'versions'    => [1],
                ]),
                'PAYDOCDATECREATE' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата и время создания платежного поручения',
                    'versions'    => [1],
                ]),
                'PAYDOCTIMECREATE' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Время создания платежного поручения',
                    'versions'    => [1],
                ]),
                'DOCUMENTNUMBERGOZ' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Номер подтверждающего документа',
                    'length'      => 15,
                    'versions'    => [1],
                ]),
                'DOCUMENTDATEGOZ' => new DateField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Дата подтверждающего документа',
                    'versions'    => [1],
                ]),
                'DOCTYPEGOZ' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Тип подтверждающего документа',
                    'versions'    => [1],
                ]),
            ]
        );
    }
}
