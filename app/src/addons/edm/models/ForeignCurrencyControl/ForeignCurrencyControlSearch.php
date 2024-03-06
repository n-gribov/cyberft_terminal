<?php
namespace addons\edm\models\ForeignCurrencyControl;

use addons\edm\models\DictBank;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\ForeignCurrencyControl\ForeignCurrencyOperationInformationExt;
use addons\edm\models\VTBCurrDealInquiry181i\VTBCurrDealInquiry181iType;
use addons\ISO20022\models\Auth024Type;
use addons\ISO20022\models\ISO20022DocumentExt;
use common\base\traits\ForSigningCountable;
use common\document\DocumentSearch;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class ForeignCurrencyControlSearch extends DocumentSearch
{
    use ForSigningCountable;

    private $_extModel;

    public $number;
    public $organizationId;
    public $accountId;
    public $date;
    public $countryCode;
    public $correctionNumber;
    public $businessStatus;
    public $accountNumber;
    public $bankBik;
    public $bankName;

    public function init()
    {
        parent::init();

        $this->_extModel = new ForeignCurrencyOperationInformationExt();
    }

    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [array_values($this->_extModel->attributes()), 'safe'],
                [['number', 'organizationId', 'accountId', 'accountNumber', 'date', 'countryCode',
                    'correctionNumber', 'status', 'businessStatus', 'bankBik', 'bankName'], 'safe'],
            ]
        );
    }

    public function formName()
    {
        return 'FCCS';
    }

    public function applyExtFilters($query)
    {
        $query->andWhere(['document.type' => [Auth024Type::TYPE, VTBCurrDealInquiry181iType::TYPE]]);

        if ($this->dateCreate) {
            $query->andWhere(['>=', 'document.dateCreate', $this->dateCreate . ' 00:00:00']);
            $query->andWhere(['<=', 'document.dateCreate', $this->dateCreate . ' 23:59:59']);
        }

        $this->_select[] = 'fcoExt.number as number';
        $this->_select[] = 'fcoExt.organizationId as organizationId';
        $this->_select[] = 'fcoExt.accountId as accountId';
        $this->_select[] = 'fcoExt.date as date';
        $this->_select[] = 'fcoExt.countryCode as countryCode';
        $this->_select[] = 'fcoExt.correctionNumber as correctionNumber';
        $this->_select[] = 'coalesce(fcoExt.businessStatus, isoExt.statusCode) as businessStatus';
        $this->_select[] = 'direction';
        $this->_select[] = 'document.status as status';
        $this->_select[] = 'account.number as accountNumber';
        $this->_select[] = 'bank.bik as bankBik';
        $this->_select[] = 'bank.name as bankName';

        //$query->joinWith(ForeignCurrencyOperationInformationExt::tableName() . ' fcoExt');

        $query->leftJoin(
            ForeignCurrencyOperationInformationExt::tableName() . ' fcoExt',
            'fcoExt.documentId = ' . static::tableName() . '.id'
        );

        $query->leftJoin(
            ISO20022DocumentExt::tableName() . ' isoExt',
            'isoExt.documentId = ' . static::tableName() . '.id'
        );

        $query->innerJoin(
            EdmPayerAccount::tableName() . ' account',
            'fcoExt.accountId = account.id'
        );

        $query->innerJoin(
            DictBank::tableName() . ' bank',
            'account.bankBik = bank.bik'
        );

        // C учетом доступных текущему пользователю счетов
        \Yii::$app->edmAccountAccess->query($query, 'account.Id');

        $query->andFilterWhere(['like', 'fcoExt.number', $this->number]);
        $query->andFilterWhere(['fcoExt.accountId' => $this->accountId]);
        $query->andFilterWhere(['bankBik' => $this->bankBik]);
        $query->andFilterWhere(['like', 'bank.name', $this->bankName]);

        $query->andFilterWhere(['signaturesCount' => $this->signaturesCount]);
        $query->andFilterWhere(['signaturesRequired' => $this->signaturesRequired]);

        $query->andFilterWhere(['fcoExt.date' => $this->date]);
        $query->andFilterWhere(['fcoExt.organizationId' => $this->organizationId]);
        $query->andFilterWhere([
            'or',
            ['like', 'fcoExt.businessStatus', $this->businessStatus],
            ['like', 'isoExt.statusCode', $this->businessStatus]
        ]);
        $query->andFilterWhere(['like', 'fcoExt.countryCode', $this->countryCode]);
        $query->andFilterWhere(['like', 'fcoExt.correctionNumber', $this->correctionNumber]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(),
            (new ForeignCurrencyOperationInformationExt)->attributeLabels(),
                ['bankName' => Yii::t('edm', 'Bank')] );
    }

    public function setDataProviderSort($dataProvider)
    {
        parent::setDataProviderSort($dataProvider);

        $this->addSortAttributes($dataProvider, [
            'number' => 'number',
            'bankBik' => 'bankBik',
            'bankName' => 'bankName',
            'date' => 'date',
            'businessStatus' => 'businessStatus',
            'accountId' => 'accountNumber'
        ]);

    }

    public function getExtModel()
    {
        return $this->hasOne(ForeignCurrencyOperationInformationExt::class, ['documentId' => 'id']);
    }

    protected function applySignaturePermissionFilter(ActiveQuery $query): void
    {
        Yii::$app->edmAccountAccess->querySignable($query, 'fcoExt.accountId');
    }

}
