<?php

namespace common\models\vtbxml\documents;

use common\models\vtbxml\documents\fields\DateField;
use common\models\vtbxml\documents\fields\IntegerField;
use common\models\vtbxml\documents\fields\StringField;

/**
 * Class CancellationRequest
 * @package common\models\vtbxml\documents
 * @property \common\models\vtbxml\documents\fields\Field $fields
 */
class CancellationRequest extends BSDocument
{
    const TYPE = 'CancellationRequest';
    const TYPE_ID = 13;
    const VERSIONS = [3, 615];
    const SIGNED_FIELDS_IDS_BY_VERSION = [
        3 => ['DOCUMENTDATE', 'DOCUMENTNUMBER', 'CUSTID', 'SENDEROFFICIALS', 'CANCELCLIENT', 'CANCELDATECREATE', 'CANCELTIMECREATE', 'CANCELDOCTYPEID', 'CANCELCUSTID', 'CANCELDOCDATE', 'CANCELDOCNUMBER', 'CANCELDOCMANDATORYFIELDS', 'CANCELDOCNOTIFICATION', 'KBOPID'],
        615 => ['DOCUMENTDATE', 'DOCUMENTNUMBER', 'CUSTID', 'CANCELDOCMANDATORYFIELDS', 'CANCELDOCNOTIFICATION', 'CANCELCUSTID', 'CANCELDOCDATE', 'CANCELDOCNUMBER', 'SENDEROFFICIALS', 'CANCELCLIENT', 'CANCELDATECREATE', 'CANCELTIMECREATE', 'CANCELDOCTYPEID', 'KBOPID'],
    ];

    /** @var \DateTime Дата документа **/
    public $DOCUMENTDATE;

    /** @var string Номер документа **/
    public $DOCUMENTNUMBER;

    /** @var integer ID Организации **/
    public $CUSTID;

    /** @var string Ответственный исполнитель **/
    public $SENDEROFFICIALS;

    /** @var integer ID клиента отзываемого документа. Определяется по референсу отзываемого документа **/
    public $CANCELCLIENT;

    /** @var integer Дата создания отзываемого документа. Определяется по референсу отзываемого документа **/
    public $CANCELDATECREATE;

    /** @var integer Время создания отзываемого документа. Определяется по референсу отзываемого документа **/
    public $CANCELTIMECREATE;

    /** @var integer Тип отзываемого документа (ID документа из «Таблица 1».) **/
    public $CANCELDOCTYPEID;

    /** @var integer ID юр. лица отзываемого документа (совпадает с CUSTID) **/
    public $CANCELCUSTID;

    /** @var \DateTime Дата отзываемого документа **/
    public $CANCELDOCDATE;

    /** @var string Номер отзываемого документа **/
    public $CANCELDOCNUMBER;

    /** @var string Описание основных полей отзываемого документа. Получается с помощью метода 2.4.1 **/
    public $CANCELDOCMANDATORYFIELDS;

    /** @var string Сопроводительное сообщение в банк по отзываемому документу **/
    public $CANCELDOCNOTIFICATION;

    /** @var integer ID подразделения банка **/
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
                    'versions'    => [3, 615],
                ]),
                'DOCUMENTNUMBER' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Номер документа',
                    'length'      => 15,
                    'versions'    => [3, 615],
                ]),
                'CUSTID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ID Организации',
                    'versions'    => [3, 615],
                ]),
                'SENDEROFFICIALS' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Ответственный исполнитель',
                    'length'      => 40,
                    'versions'    => [3, 615],
                ]),
                'CANCELCLIENT' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ID клиента отзываемого документа. Определяется по референсу отзываемого документа',
                    'versions'    => [3, 615],
                ]),
                'CANCELDATECREATE' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Дата создания отзываемого документа. Определяется по референсу отзываемого документа',
                    'versions'    => [3, 615],
                ]),
                'CANCELTIMECREATE' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Время создания отзываемого документа. Определяется по референсу отзываемого документа',
                    'versions'    => [3, 615],
                ]),
                'CANCELDOCTYPEID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Тип отзываемого документа (ID документа из «Таблица 1».)',
                    'versions'    => [3, 615],
                ]),
                'CANCELCUSTID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ID юр. лица отзываемого документа (совпадает с CUSTID)',
                    'versions'    => [3, 615],
                ]),
                'CANCELDOCDATE' => new DateField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Дата отзываемого документа',
                    'versions'    => [3, 615],
                ]),
                'CANCELDOCNUMBER' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Номер отзываемого документа',
                    'length'      => 15,
                    'versions'    => [3, 615],
                ]),
                'CANCELDOCMANDATORYFIELDS' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Описание основных полей отзываемого документа. Получается с помощью метода 2.4.1',
                    'length'      => null,
                    'versions'    => [3, 615],
                ]),
                'CANCELDOCNOTIFICATION' => new StringField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'Сопроводительное сообщение в банк по отзываемому документу',
                    'length'      => null,
                    'versions'    => [3, 615],
                ]),
                'KBOPID' => new IntegerField([
                    'isRequired'  => true,
                    'isSigned'    => true,
                    'description' => 'ID подразделения банка',
                    'versions'    => [3, 615],
                ]),
            ]
        );
    }
}
