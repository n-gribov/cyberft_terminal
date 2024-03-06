<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\DateTimeField;
use common\models\vtbxml\documents\fields\DoubleField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class TransitAccPayDocNotice
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class TransitAccPayDocNotice extends BSDocument
{
    const TYPE = 'TransitAccPayDocNotice';
    const TYPE_ID = null;
    const VERSIONS = [8];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        8 => ['NOTICENUMBER', 'NOTICEDATE', 'NOTICEAMOUNT', 'OPERCODE', 'NOTICECURRCODE', 'DESCRIPTION'],
    ];

    /** @var string Номер уведомления **/
    public $NOTICENUMBER;

    /** @var \DateTime Дата уведомления **/
    public $NOTICEDATE;

    /** @var float Сумма уведомления **/
    public $NOTICEAMOUNT;

    /** @var string Код вида операции уведомления **/
    public $OPERCODE;

    /** @var string Код валюты суммы уведомления **/
    public $NOTICECURRCODE;

    /** @var string Примечание к уведомлению **/
    public $DESCRIPTION;

    public function getFields()
    {
        return array_merge(
            parent::getFields(),
            [
                'NOTICENUMBER' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Номер уведомления',
                    'length'      => 50,
                    'versions'    => [8],
                ]),
                'NOTICEDATE' => new DateTimeField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Дата уведомления',
                    'versions'    => [8],
                ]),
                'NOTICEAMOUNT' => new DoubleField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Сумма уведомления',
                    'versions'    => [8],
                ]),
                'OPERCODE' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Код вида операции уведомления',
                    'length'      => 10,
                    'versions'    => [8],
                ]),
                'NOTICECURRCODE' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Код валюты суммы уведомления',
                    'length'      => 3,
                    'versions'    => [8],
                ]),
                'DESCRIPTION' => new StringField([
                    'isRequired'  => false,
                    'isSigned'    => true,
                    'description' => 'Примечание к уведомлению',
                    'length'      => 255,
                    'versions'    => [8],
                ]),
            ]
        );
    }
}
