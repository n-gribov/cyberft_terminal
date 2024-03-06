<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\AttachmentField;
use common\models\vtbxml\documents\fields\DateField;
use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class FreeClientDoc
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class FreeClientDoc extends BSDocument
{
    const TYPE = 'FreeClientDoc';
    const TYPE_ID = 4;
    const VERSIONS = [3];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        3 => ['DOCUMENTDATE', 'DOCUMENTNUMBER', 'CUSTID', 'SENDEROFFICIALS', 'DOCTYPE', 'DOCATTACHMENT', 'DOCNAME', 'DOCTEXT', 'RECIPIENT', 'KBOPID'],
    ];

    /** @var \DateTime Дата документа **/
    public $DOCUMENTDATE;

    /** @var string Номер документа **/
    public $DOCUMENTNUMBER;

    /** @var integer ID организации **/
    public $CUSTID;

    /** @var string Ответственный исполнитель **/
    public $SENDEROFFICIALS;

    /** @var integer Тип документа **/
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
                    'description' => 'ID организации',
                    'versions'    => [3],
                ]),
                'SENDEROFFICIALS' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Ответственный исполнитель',
                    'length'      => 40,
                    'versions'    => [3],
                ]),
                'DOCTYPE' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тип документа',
                    'versions'    => [3],
                ]),
                'DOCATTACHMENT' => new AttachmentField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Файлы вложения. (формируется по правилу, приведенному в п. 3.3.8)',
                    'versions'    => [3],
                ]),
                'DOCNAME' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Название документа',
                    'length'      => 255,
                    'versions'    => [3],
                ]),
                'DOCTEXT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Текст документа',
                    'length'      => null,
                    'versions'    => [3],
                ]),
                'RECIPIENT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Лицо, которому предназначен документ',
                    'length'      => 80,
                    'versions'    => [3],
                ]),
                'KBOPID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ID подразделения',
                    'versions'    => [3],
                ]),
            ]
        );
    }
}
