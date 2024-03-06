<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\StringField;

/**
 * Class StatementRuCreditDocSwift
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class StatementRuCreditDocSwift extends BSDocument
{
    const TYPE = 'StatementRuCreditDocSwift';
    const TYPE_ID = null;
    const VERSIONS = [2];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        2 => ['ID', 'CODE', 'SVALUE', 'SAVEDDOCREF'],
    ];

    /** @var string Идентификатор сообщения **/
    public $ID;

    /** @var string Тег сообщения МТ103 **/
    public $CODE;

    /** @var string Значение параметра (тега) сообщения МТ103 **/
    public $SVALUE;

    /** @var string Референс документа **/
    public $SAVEDDOCREF;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'ID' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Идентификатор сообщения',
                    'length'      => 50,
                    'versions'    => [2],
                ]),
                'CODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Тег сообщения МТ103',
                    'length'      => 3,
                    'versions'    => [2],
                ]),
                'SVALUE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Значение параметра (тега) сообщения МТ103',
                    'length'      => 210,
                    'versions'    => [2],
                ]),
                'SAVEDDOCREF' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Референс документа',
                    'length'      => 32,
                    'versions'    => [2],
                ]),
            ]
        );
    }
}
