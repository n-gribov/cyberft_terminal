<?php

namespace common\modules\autobot\models;

use yii\db\ActiveRecord;

/**
 * Class ControllerCertAcknowledgementActData
 * @package common\modules\autobot\models
 *
 * @property integer $id
 * @property string $agreementType
 * @property string $agreementNumber
 * @property string $agreementDate
 * @property string $signerFullName
 * @property string $signerPosition
 * @property string $signerAuthority
 * @property string $certOwnerPosition
 * @property string $certOwnerPassportCountry
 * @property string $certOwnerPassportSeries
 * @property string $certOwnerPassportNumber
 * @property string $certOwnerPassportAuthorityCode
 * @property string $certOwnerPassportAuthority
 * @property string $certOwnerPassportIssueDate
 * @property integer $controllerId
 */
class ControllerCertAcknowledgementActData extends ActiveRecord
{
    public static function tableName()
    {
        return 'controllerCertAcknowledgementActData';
    }

    public function rules()
    {
        return [
            [
                [
                    'agreementType',
                    'agreementNumber',
                    'signerFullName',
                    'signerPosition',
                    'signerAuthority',
                    'certOwnerPosition',
                    'certOwnerPassportCountry',
                    'certOwnerPassportSeries',
                    'certOwnerPassportNumber',
                    'certOwnerPassportAuthorityCode',
                    'certOwnerPassportAuthority',
                ],
                'string'
            ],
            [['agreementDate', 'certOwnerPassportIssueDate'], 'date', 'format' => 'yyyy-MM-dd'],
            ['controllerId', 'integer'],
            [
                [
                    'agreementType',
                    'agreementNumber',
                    'agreementDate',
                    'signerFullName',
                    'signerPosition',
                    'signerAuthority',
                    'certOwnerPosition',
                    'certOwnerPassportCountry',
                    'certOwnerPassportSeries',
                    'certOwnerPassportNumber',
                    'certOwnerPassportAuthorityCode',
                    'certOwnerPassportAuthority',
                    'certOwnerPassportIssueDate',
                ],
                'safe'
            ],
            [
                [
                    'agreementType',
                    'agreementNumber',
                    'agreementDate',
                    'signerFullName',
                    'signerPosition',
                    'signerAuthority',
                    'certOwnerPosition',
                    'certOwnerPassportCountry',
                    'certOwnerPassportSeries',
                    'certOwnerPassportNumber',
                    'certOwnerPassportAuthorityCode',
                    'certOwnerPassportAuthority',
                    'certOwnerPassportIssueDate',
                ],
                'default',
                'value' => null
            ],
        ];
    }
}
