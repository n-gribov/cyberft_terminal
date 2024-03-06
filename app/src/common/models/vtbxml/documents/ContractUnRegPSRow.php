<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\DateTimeField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class ContractUnRegPSRow
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class ContractUnRegPSRow extends BSDocument
{
    const TYPE = 'ContractUnRegPSRow';
    const TYPE_ID = null;
    const VERSIONS = [3];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        3 => ['PSNUMBER', 'PSDATE', 'GROUND', 'CODE', 'GROUND1', 'GROUND2'],
    ];

    /** @var string Уникальный номер снимаемого с учета контракта (кредитного договора) **/
    public $PSNUMBER;

    /** @var \DateTime Дата присвоения снимаемому с учета контракту (кредитному договору) уникального номера **/
    public $PSDATE;

    /** @var string Основание для снятия с учета контракта (кредитного договора) **/
    public $GROUND;

    /** @var string Пункт инструкции **/
    public $CODE;

    /** @var string Основание для снятия с учета контракта (кредитного договора) (1-я часть) **/
    public $GROUND1;

    /** @var string Основание для снятия с учета контракта (кредитного договора) (2-я часть) **/
    public $GROUND2;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'PSNUMBER' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Уникальный номер снимаемого с учета контракта (кредитного договора)',
                    'length'      => 25,
                    'versions'    => [3],
                ]),
                'PSDATE' => new DateTimeField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Дата присвоения снимаемому с учета контракту (кредитному договору) уникального номера',
                    'versions'    => [3],
                ]),
                'GROUND' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Основание для снятия с учета контракта (кредитного договора)',
                    'length'      => 255,
                    'versions'    => [3],
                ]),
                'CODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Пункт инструкции',
                    'length'      => 10,
                    'versions'    => [3],
                ]),
                'GROUND1' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Основание для снятия с учета контракта (кредитного договора) (1-я часть)',
                    'length'      => 255,
                    'versions'    => [3],
                ]),
                'GROUND2' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Основание для снятия с учета контракта (кредитного договора) (2-я часть)',
                    'length'      => 255,
                    'versions'    => [3],
                ]),
            ]
        );
    }
}
