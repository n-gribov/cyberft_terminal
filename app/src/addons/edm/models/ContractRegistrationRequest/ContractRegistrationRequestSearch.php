<?php

namespace addons\edm\models\ContractRegistrationRequest;

use addons\edm\EdmModule;
use addons\edm\models\DictBank;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\VTBContractChange\VTBContractChangeType;
use addons\edm\models\VTBContractReg\VTBContractRegType;
use addons\edm\models\VTBContractRequest\VTBContractRequestContract;
use addons\edm\models\VTBContractRequest\VTBContractRequestExt;
use addons\edm\models\VTBContractUnReg\VTBContractUnRegType;
use addons\edm\models\VTBCredReg\VTBCredRegType;
use addons\ISO20022\models\Auth018Type;
use common\base\traits\ForSigningCountable;
use common\document\DocumentSearch;
use common\document\DocumentTypeGroupQueryBuilder;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class ContractRegistrationRequestSearch extends DocumentSearch
{
    use ForSigningCountable;

    const REQUEST_TYPE_REGISTRATION = 'registration';
    const REQUEST_TYPE_UNREGISTERING = 'unregistering';
    const REQUEST_TYPE_CHANGE = 'change';

    public $number;
    public $passportNumber;
    public $date;
    public $organizationId;
    public $amount;
    public $currencyId;
    public $contractDate;
    public $businessStatus;
    public $bankBik;
    public $bankName;

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [
                    ['number', 'passportNumber', 'date', 'organizationId', 'amount', 'currencyId', 'businessStatus', 'bankBik', 'type', 'status', 'dateCreate', 'signaturesRequired', 'signaturesCount'], 'safe'
                ],
            ]
        );
        
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::attributeLabels(),
            (new ContractRegistrationRequestExt)->attributeLabels(),
            [
                'requestType' => Yii::t('edm', 'Type'),
                'requestTypeLabel' => Yii::t('edm', 'Type'),
                'contractDate' => Yii::t('edm', 'Contract date'),
                'businessStatus'  => Yii::t('doc', 'Execution status'),
            ]
        );
    }

    /**
     * @param ActiveQuery $query
     * @return void
     */
    public function applyExtFilters($query)
    {
        $query->andWhere(['document.type' => array_keys($this->getTypefilter())]);

        $query->leftJoin(
            ContractRegistrationRequestExt::tableName() . ' crrExt',
            'crrExt.documentId = ' . static::tableName() . '.id'
        );
        $this->applyDocumentTypeCondition($query);

        if (!$this->countMode) {
            $this->applyFullSearch($query);
        }

        $query->orderBy(['id' => SORT_DESC]);
    }

    private function applyFullSearch($query)
    {
        $query->leftJoin(
            VTBContractRequestExt::tableName() . ' vtbExt',
            'vtbExt.documentId = ' . static::tableName() . '.id'
        );
        $query->innerJoin(
            DictBank::tableName() . ' bank',
            'receiver = bank.terminalId'
        );

        $this->_select[] = 'coalesce(crrExt.number, vtbExt.number) as number';
        $this->_select[] = 'crrExt.passportType as passportType';
        $this->_select[] = 'coalesce(crrExt.date, date_format(vtbExt.date, "%d.%m.%Y")) as date';
        $this->_select[] = 'coalesce(crrExt.organizationId, vtbExt.organizationId) as organizationId';
        $this->_select[] = 'direction';
        $this->_select[] = 'document.status as status';
        $this->_select[] = 'vtbExt.businessStatus as businessStatus';
        $this->_select[] = 'bank.bik as bankBik';
        $this->_select[] = 'bank.name as bankName';

        $query->andWhere([
            'or',
            ['not', ['crrExt.id' => null]],
            ['not', ['vtbExt.id' => null]]
        ]);

        $query->andFilterWhere([
            'or',
            ['like', 'crrExt.number', $this->number],
            ['like', 'vtbExt.number', $this->number]
        ]);

        if ($this->date) {
            $date = \DateTime::createFromFormat('d.m.Y', $this->date);
            if ($date) {
                $query->andFilterWhere([
                    'or',
                    ['crrExt.date' => $date->format('d.m.Y')],
                    ['vtbExt.date' => $date->format('Y-m-d')]
                ]);
            } else {
                $query->andWhere('0 = 1');
            }
        }


        // Фильтр в зависимости от того, по какому значению фильтруем.
        // Либо по организации, либо по наименованию плательщика из счета
        $org = $this->organizationId;
        // Определение типа фильтра
        if (stristr($org, 'organization')) {
            // По организации счета
            $org = str_replace('_organization', '', $this->organizationId);
        }
        $query->andFilterWhere(['crrExt.organizationId' => $org]);

        if ($this->passportNumber) {
            $query->andFilterWhere([
                'or',
                ['like', 'crrExt.passportNumber', $this->passportNumber],
                ['vtbExt.id' => VTBContractRequestContract::find()->where(['like', 'number', $this->passportNumber])->select(['requestId'])]
            ]);
        }

        if ($this->currencyId) {
            $query->andFilterWhere([
                'or',
                ['crrExt.currencyId' => $this->currencyId],
                ['vtbExt.id' => VTBContractRequestContract::find()->where(['currencyId' => $this->currencyId])->select(['requestId'])]
            ]);
        }

        if ($this->amount) {
            $query->andFilterWhere([
                'or',
                ['crrExt.amount' => $this->amount],
                ['vtbExt.id' => VTBContractRequestContract::find()->where(['amount' => $this->amount])->select(['requestId'])]
            ]);
        }

        $query->andFilterWhere(['vtbExt.businessStatus' => $this->businessStatus]);
        $query->andFilterWhere(['bank.bik' => $this->bankBik]);
        $query->andFilterWhere(['signaturesCount' => $this->signaturesCount]);
        $query->andFilterWhere(['signaturesRequired' => $this->signaturesRequired]);
    }

    public function getExtModel()
    {
        return $this->type === Auth018Type::TYPE
            ? $this->hasOne(ContractRegistrationRequestExt::className(), ['documentId' => 'id'])
            : $this->hasOne(VTBContractRequestExt::className(), ['documentId' => 'id']);
    }

    public function getTypeLabel()
    {
        switch ($this->type) {
            case Auth018Type::TYPE:
                return Yii::t('edm', 'Contract (loan agreement) registration request');
            case VTBContractRegType::TYPE:
                return Yii::t('edm', 'Contract registration request');
            case VTBCredRegType::TYPE:
                return Yii::t('edm', 'Loan agreement registration request');
            case VTBContractChangeType::TYPE:
                return Yii::t('edm', 'Contract change');
            case VTBContractUnRegType::TYPE:
                return Yii::t('edm', 'Contract unregistration request');
        }

        return null;
    }

    public static function getTypeFilter()
    {
        return [
            VTBContractRegType::TYPE => Yii::t('edm', 'Contract registration request'),
            VTBCredRegType::TYPE => Yii::t('edm', 'Loan agreement registration request'),
            VTBContractChangeType::TYPE => Yii::t('edm', 'Contract change'),
            VTBContractUnRegType::TYPE => Yii::t('edm', 'Contract unregistration request'),
        ];
    }

    private function applyDocumentTypeCondition(Query $query)
    {
        $queryBuilder = new DocumentTypeGroupQueryBuilder(
            EdmModule::SERVICE_ID,
            [
                EdmDocumentTypeGroup::LOAN_AGREEMENT_REGISTRATION_REQUEST,
                EdmDocumentTypeGroup::CONTRACT_REGISTRATION_REQUEST,
                EdmDocumentTypeGroup::CONTRACT_CHANGE_REQUEST,
                EdmDocumentTypeGroup::CONTRACT_UNREGISTRATION_REQUEST,
            ],
            'crrExt'
        );

        $queryBuilder->applyToQuery($query);
    }

    protected function applySignaturePermissionFilter(ActiveQuery $query): void
    {
        Yii::$app->edmAccountAccess->queryBankTerminalsHavingSignableAccounts($query, 'document.receiver');
    }
}
