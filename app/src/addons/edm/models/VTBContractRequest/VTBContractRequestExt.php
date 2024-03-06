<?php

namespace addons\edm\models\VTBContractRequest;

use addons\edm\models\DictCurrency;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccountUser;
use addons\edm\models\VTBContractChange\VTBContractChangeType;
use addons\edm\models\VTBContractReg\VTBContractRegType;
use addons\edm\models\VTBContractUnReg\VTBContractUnRegType;
use addons\edm\models\VTBCredReg\VTBCredRegType;
use common\base\interfaces\DocumentExtInterface;
use common\document\Document;
use common\helpers\vtb\VTBHelper;
use common\models\User;
use common\models\vtbxml\documents\ContractChange;
use common\models\vtbxml\documents\ContractReg;
use common\models\vtbxml\documents\ContractUnReg;
use common\models\vtbxml\documents\CredReg;
use Yii;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $documentId
 * @property string $type
 * @property string $number
 * @property string $date
 * @property integer $organizationId
 * @property DictOrganization|null $organization
 * @property VTBContractRequestContract[] $contracts
 * @property string $businessStatus
 * @property string $businessStatusComment
 * @property string $businessStatusDescription
 */
class VTBContractRequestExt extends ActiveRecord implements DocumentExtInterface
{
    const REQUEST_TYPE_REGISTRATION = 'registration';
    const REQUEST_TYPE_UNREGISTERING = 'unregistering';
    const REQUEST_TYPE_CHANGE = 'change';

    public $contractsAttributes = [];

    public static function tableName()
    {
        return 'documentExtEdmVTBContractRequest';
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            foreach ($this->contractsAttributes as $contractAttributes) {
                $contract = new VTBContractRequestContract($contractAttributes);
                $contract->requestId = $this->id;
                $contract->save();
            }
        }
    }

    public function getContracts()
    {
        return $this->hasMany(VTBContractRequestContract::className(), ['requestId' => 'id']);
    }

    public function getOrganization()
    {
        return $this->hasOne(DictOrganization::className(), ['organizationId' => 'id']);
    }

    public function getRequestTypeLabel()
    {
        switch ($this->type) {
            case static::REQUEST_TYPE_REGISTRATION:
                if (count($this->contracts) > 0) {
                    return $this->contracts[0]->type === VTBContractRequestContract::CONTRACT_TYPE_CONTRACT
                        ? Yii::t('edm', 'Contract registration')
                        : Yii::t('edm', 'Credit agreement registration');
                }
                return null;
            case static::REQUEST_TYPE_CHANGE:
                return Yii::t('edm', 'Contract change');
            case static::REQUEST_TYPE_UNREGISTERING:
                return Yii::t('edm', 'Contract unregistering');
        }
    }

    public function loadContentModel($model)
    {
        if ($model instanceof VTBCredRegType || $model instanceof VTBContractRegType) {
            /** @var CredReg|ContractReg $vtbDocument */
            $vtbDocument = $model->document;

            $organization = VTBHelper::getOrganizationByVTBCustomerId($vtbDocument->CUSTID);
            $currency = $documentCurrency = DictCurrency::findOne(['code' => $vtbDocument->CONCURRCODE]);
            $contractType = $model instanceof VTBCredRegType
                ? VTBContractRequestContract::CONTRACT_TYPE_CREDIT_AGREEMENT
                : VTBContractRequestContract::CONTRACT_TYPE_CONTRACT;
            $documentDate = $vtbDocument->DOCUMENTDATE !== null ? $vtbDocument->DOCUMENTDATE->format('Y-m-d') : null;

            $this->type = static::REQUEST_TYPE_REGISTRATION;
            $this->number = $vtbDocument->DOCUMENTNUMBER;
            $this->date = $documentDate;
            $this->organizationId = $organization !== null ? $organization->id : null;
            $this->contractsAttributes = [
                [
                    'number'     => $vtbDocument->CONNUMBER,
                    'type'       => $contractType,
                    'date'       => $documentDate,
                    'amount'     => $vtbDocument->CONAMOUNT,
                    'currencyId' => $currency !== null ? $currency->id : null,
                ]
            ];
        } elseif ($model instanceof VTBContractChangeType || $model instanceof VTBContractUnRegType) {
            /** @var ContractChange|ContractUnReg $vtbDocument */
            $vtbDocument = $model->document;

            $organization = VTBHelper::getOrganizationByVTBCustomerId($vtbDocument->CUSTID);
            $documentDate = $vtbDocument->DOCUMENTDATE !== null ? $vtbDocument->DOCUMENTDATE->format('Y-m-d') : null;

            $this->type = $model instanceof VTBContractChangeType
                ? static::REQUEST_TYPE_CHANGE
                : static::REQUEST_TYPE_UNREGISTERING;
            $this->number = $vtbDocument->DOCUMENTNUMBER;
            $this->date = $documentDate;
            $this->organizationId = $organization !== null ? $organization->id : null;

            $this->contractsAttributes = array_map(
                function ($vtbContract) {
                    $contractDate = $vtbContract->PSDATE !== null ? $vtbContract->PSDATE->format('Y-m-d') : null;
                    return [
                        'number' => $vtbContract->PSNUMBER,
                        'date'   => $contractDate,
                    ];
                },
                $vtbDocument->PSROWS
            );
        }
    }

    public function isDocumentDeletable(User $user = null)
    {
        return true;
    }

    public function canBeSignedByUser(User $user, Document $document): bool
    {
        return EdmPayerAccountUser::userCanSingDocumentsForBankTerminal($user->id, $document->receiver);
    }
}
