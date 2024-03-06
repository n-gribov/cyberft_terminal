<?php

namespace addons\edm\models\ConfirmingDocumentInformation;

use addons\edm\models\DictBank;
use addons\edm\models\VTBConfDocInquiry138I\VTBConfDocInquiry138IType;
use addons\ISO20022\models\Auth025Type;
use common\base\traits\ForSigningCountable;
use common\document\DocumentSearch;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class ConfirmingDocumentInformationSearch extends DocumentSearch
{
    use ForSigningCountable;

    public $number;
    public $organizationId;
    public $date;
    public $contractPassport;
    public $contactNumber;
    public $correctionNumber;
    public $person;
    public $businessStatus;
    public $bankBik;
    public $bankName;

    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [
                    [
                        'number', 'organizationId', 'date', 'contractPassport',
                        'person', 'contactNumber', 'status', 'correctionNumber', 'businessStatus', 'bankBik'
                    ], 'safe'
                ],
            ]
        );
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(),
            (new ConfirmingDocumentInformationExt)->attributeLabels());
    }

    public function applyExtFilters($query)
    {
        $query->andWhere(['document.type' => [Auth025Type::TYPE, VTBConfDocInquiry138IType::TYPE]]);

        $this->_select[] = 'ext.number as number';
        $this->_select[] = 'ext.organizationId as organizationId';
        $this->_select[] = 'ext.date as date';
        $this->_select[] = 'ext.contractPassport as contractPassport';
        $this->_select[] = 'ext.person as person';
        $this->_select[] = 'ext.contactNumber as contactNumber';
        $this->_select[] = 'ext.correctionNumber as correctionNumber';
        $this->_select[] = 'direction';
        $this->_select[] = 'document.status as status';
        $this->_select[] = 'ext.businessStatus as businessStatus';
        $this->_select[] = 'bank.bik as bankBik';
        $this->_select[] = 'bank.name as bankName';

        $query->innerJoin(
            ConfirmingDocumentInformationExt::tableName() . ' ext',
            'ext.documentId = ' . static::tableName() . '.id'
        );

        $query->innerJoin(
            DictBank::tableName() . ' bank',
            'ext.bankBik = bank.bik'
        );

        $query->andFilterWhere(['ext.date' => $this->date]);
        $query->andFilterWhere(['ext.organizationId' => $this->organizationId]);
        $query->andFilterWhere(['bank.bik' => $this->bankBik]);
        $query->andFilterWhere(['like', 'ext.number', $this->number]);
        $query->andFilterWhere(['like', 'ext.correctionNumber', $this->correctionNumber]);
        $query->andFilterWhere(['like', 'ext.contractPassport', $this->contractPassport]);
        $query->andFilterWhere(['like', 'ext.person', $this->person]);
        $query->andFilterWhere(['like', 'ext.contactNumber', $this->contactNumber]);
        $query->andFilterWhere(['ext.businessStatus' => $this->businessStatus]);
        $query->andFilterWhere(['signaturesCount' => $this->signaturesCount]);
        $query->andFilterWhere(['signaturesRequired' => $this->signaturesRequired]);

        $query->orderBy(['id' => SORT_DESC]);
    }

    public function getExtModel()
    {
        return $this->hasOne(ConfirmingDocumentInformationExt::class, ['documentId' => 'id']);
    }

    protected function applySignaturePermissionFilter(ActiveQuery $query): void
    {
        Yii::$app->edmAccountAccess->queryBanksHavingSignableAccounts($query, 'bank.bik');
    }
}
