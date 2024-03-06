<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class DocInfoSalaryCheck
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class DocInfoSalaryCheck extends BSDocument
{
    const TYPE = 'DocInfoSalaryCheck';
    const TYPE_ID = null;
    const VERSIONS = [1];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        1 => ['REFRECORDID', 'CHECKRESULT', 'MSGRESULT'],
    ];

    /** @var string Идентификатор записи в SalaryBlob **/
    public $REFRECORDID;

    /** @var integer Результат проверки: 1 – предупреждение, 2 – ошибка **/
    public $CHECKRESULT;

    /** @var string Текст ошибки/предупреждения **/
    public $MSGRESULT;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'REFRECORDID' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Идентификатор записи в SalaryBlob',
                    'length'      => 50,
                    'versions'    => [1],
                ]),
                'CHECKRESULT' => new IntegerField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Результат проверки: 1 – предупреждение, 2 – ошибка',
                    'versions'    => [1],
                ]),
                'MSGRESULT' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Текст ошибки/предупреждения',
                    'length'      => 255,
                    'versions'    => [1],
                ]),
            ]
        );
    }
}
