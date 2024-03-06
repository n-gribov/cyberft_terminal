<?php

namespace addons\edm\models\ForeignCurrencyOperation;

use addons\edm\EdmModule;
use addons\edm\models\DictBank;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\Pain001Fx\Pain001FxType;
use addons\edm\models\Pain001Rls\Pain001RlsType;
use addons\edm\models\VTBCurrBuy\VTBCurrBuyType;
use addons\edm\models\VTBCurrConversion\VTBCurrConversionType;
use addons\edm\models\VTBCurrSell\VTBCurrSellType;
use addons\edm\models\VTBTransitAccPayDoc\VTBTransitAccPayDocType;
use common\base\traits\ForSigningCountable;
use common\document\Document;
use common\document\DocumentSearch;
use common\document\DocumentTypeGroupQueryBuilder;
use DateTime;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class ForeignCurrencyOperationSearch extends DocumentSearch
{
    use ForSigningCountable;

    private $_foreignCurrencyOperationDocumentExt;

    public $numberDocument;
    public $date;
    public $payer;
    public $debitAccount;
    public $currencySum;
    public $currency;
    public $sum;
    public $businessStatus;
    public $documentSum;
    public $bankName;
    public $types;
    public $extDocumentType;
    public $beneficiary;
    public $showDeleted;

    public function init()
    {
        parent::init();

        $this->_foreignCurrencyOperationDocumentExt = new ForeignCurrencyOperationDocumentExt();
    }

    public function formName()
    {
        return 'FCO';
    }

    /**
     * Get document type labels list
     *
     * @return array
     */
    public static function getDocTypeLabels()
    {
        return [
            'pain.001.FX:ForeignCurrencyPurchaseRequest' => Yii::t('edm', 'Currency purchase'),
            'VTBCurrBuy' => Yii::t('edm', 'Currency purchase'),
            'pain.001.FX:ForeignCurrencySellRequest' => Yii::t('edm', 'Currency sell'),
            'pain.001.RLS:ForeignCurrencySellTransit' => Yii::t('edm', 'Sell of foreign currency from the transit account'),
            'VTBCurrSell' => Yii::t('edm', 'Currency sell'),
            'pain.001:ForeignCurrencySellTransitAccount' => Yii::t('edm', 'Sell of foreign currency from the transit account'),
            'pain.001.RLS' => Yii::t('edm', 'Sell of foreign currency from the transit account'),
            'VTBTransitAccPayDoc' => Yii::t('edm', 'Sell of foreign currency from the transit account'),
            'VTBCurrConversion' => Yii::t('edm', 'Currency conversion request'),
            'pain.001:ForeignCurrencyConversion' => Yii::t('edm', 'Currency conversion request'),
            'VTBRegisterCur' => Yii::t('edm', 'Currency payment register'),
        ];
    }

    public static function getDocTypesFilter()
    {
        $types = static::getDocTypeLabels();
        $nameToTypes = array_reduce(
            array_keys($types),
            function ($carry, $type) use ($types) {
                $typeName = $types[$type];
                if (array_key_exists($typeName, $carry)) {
                    $carry[$typeName][] = $type;
                } else {
                    $carry[$typeName] = [$type];
                }

                return $carry;
            },
            []
        );
        return array_reduce(
            array_keys($nameToTypes),
            function ($carry, $typeName) use ($nameToTypes) {
                $typesString = implode(',', $nameToTypes[$typeName]);
                $carry[$typesString] = $typeName;

                return $carry;
            },
            []
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [array_values($this->_foreignCurrencyOperationDocumentExt->attributes()), 'required'],
                [$this->attributes(), 'safe'],
                [['documentSum', 'bankName', 'payer', 'types'], 'safe']
            ]
        );
    }

    public function attributeLabels()
    {
        $labels = ArrayHelper::merge(parent::attributeLabels(),
            $this->_foreignCurrencyOperationDocumentExt->attributeLabels());

        $labels['bankName'] = Yii::t('edm', 'Bank');
        $labels['documentSum'] = Yii::t('edm', 'Amount');
        $labels['types'] = Yii::t('edm', 'Type');

        return $labels;

    }

    public function getDocTypeLabel(): ?string
    {
        $typeLabels = self::getDocTypeLabels();
        if (array_key_exists($this->type, $typeLabels)) {
            return $typeLabels[$this->type];
        }

        $typeWithExtType = "{$this->type}:{$this->extDocumentType}";
        return $typeLabels[$typeWithExtType] ?? null;
    }

    public function applyExtFilters($query)
    {
        $this->_select[] = 'ext.numberDocument as numberDocument';
        $this->_select[] = 'ext.date as date';
        $this->_select[] = 'org.name as payer';
        $this->_select[] = 'ext.debitAccount as debitAccount';
        $this->_select[] = 'ext.currency as currency';
        $this->_select[] = 'ext.sum as sum';
        $this->_select[] = 'ext.currencySum as currencySum';
        $this->_select[] = 'ext.businessStatus as businessStatus';
        $this->_select[] = 'debitAccountBank.name as bankName';
        $this->_select[] = 'ext.beneficiary as beneficiary';
        $this->_select[] = 'ext.documentType as extDocumentType';

        $query->innerJoin(
            ForeignCurrencyOperationDocumentExt::tableName() . ' ext',
            'ext.documentId = ' . static::tableName() . '.id'
        );
        $query->innerJoin(
            EdmPayerAccount::tableName() . ' debitAccountData',
            'debitAccountData.number = ext.debitAccount'
        );
        $query->innerJoin(
            DictOrganization::tableName() . ' org',
            'org.id = debitAccountData.organizationId'
        );

        $query->innerJoin(
            DictBank::tableName() . ' debitAccountBank',
            'debitAccountBank.bik = debitAccountData.bankBik'
        );

        static::applyPayerFilter($query, $this->payer, 'debitAccountData');

        $documentTypes = [
            'pain.001',
            Pain001RlsType::TYPE,
            Pain001FxType::TYPE,
            VTBCurrSellType::TYPE,
            VTBCurrBuyType::TYPE,
            VTBTransitAccPayDocType::TYPE,
            VTBCurrConversionType::TYPE,
        ];
        $query->andWhere(['document.type' => $documentTypes]);

        $this->applyDocumentTypeCondition($query);

        $query->andFilterWhere(['numberDocument' => $this->numberDocument]);
        $query->andFilterWhere(['debitAccount' => $this->debitAccount]);
        $query->andFilterWhere(['currency' => $this->currency]);
        $query->andFilterWhere(['signaturesCount' => $this->signaturesCount]);
        $query->andFilterWhere(['signaturesRequired' => $this->signaturesRequired]);
        $query->andFilterWhere(['debitAccountData.bankBik' => $this->bankName]);
        $query->andFilterWhere(['ext.businessStatus' => $this->businessStatus]);
        $query->andFilterWhere(['ext.documentType' => $this->extDocumentType]);
        $query->andFilterWhere(['like', 'ext.beneficiary', $this->beneficiary]);

        $documentSum = floatval(str_replace(' ', '', $this->documentSum));

        if ($documentSum) {
            $query->andFilterWhere(
                ['or',
                    ['currencySum' => $documentSum],
                    ['sum' => $documentSum],
                ]
            );
        }
        if ($this->types) {
            $this->applyTypesFiler($query);
        }

        // C учетом доступных текущему пользователю счетов
        $query = Yii::$app->edmAccountAccess->query($query, 'debitAccount', true);

        if ($this->date) {
            $date = DateTime::createFromFormat('d.m.Y', $this->date);

            if ($date === false) {
                $date = new DateTime();
            }

            $dateFormatted = $date->format('Y-m-d');
            $query->andWhere(['>=', 'date', $dateFormatted . ' 00:00:00']);
            $query->andWhere(['<=', 'date', $dateFormatted . ' 23:59:59']);
        }

        if ($this->dateCreate) {
            $date = DateTime::createFromFormat('d.m.Y', $this->dateCreate);

            if ($date === false) {
                $date = new DateTime();
            }

            $dateFormatted = $date->format('Y-m-d');
            $query->andWhere(['>=', 'dateCreate', $dateFormatted . ' 00:00:00']);
            $query->andWhere(['<=', 'dateCreate', $dateFormatted . ' 23:59:59']);
        }

        if (!$this->showDeleted) {
            $query->andWhere(['!=', 'document.status', Document::STATUS_DELETED]);
        }
    }

    public function getExtModel()
    {
        return $this->hasOne($this->_foreignCurrencyOperationDocumentExt, ['documentId' => 'id']);
    }

    private function applyDocumentTypeCondition(Query $query)
    {
        $queryBuilder = new DocumentTypeGroupQueryBuilder(
            EdmModule::SERVICE_ID,
            [
                EdmDocumentTypeGroup::CURRENCY_PURCHASE,
                EdmDocumentTypeGroup::CURRENCY_SELL,
                EdmDocumentTypeGroup::TRANSIT_ACCOUNT_PAYMENT,
                EdmDocumentTypeGroup::CURRENCY_CONVERSION,
            ],
            'ext'
        );

        $queryBuilder->applyToQuery($query);
    }

    private function applyTypesFiler(Query $query): void
    {
        $types = explode(',', $this->types);
        $conditions = [];
        foreach ($types as $type) {
            if (strpos($type, ':') > 0) {
                list($documentType, $extDocumentType) = explode(':', $type);
                $conditions[] = ['document.type' => $documentType, 'ext.documentType' => $extDocumentType];
            } else {
                $conditions[] = ['document.type' => $type];
            }
        }
        if (count($conditions) > 0) {
            $query->andWhere(array_merge(['or'], $conditions));
        }
    }

    protected function applySignaturePermissionFilter(ActiveQuery $query): void
    {
        Yii::$app->edmAccountAccess->querySignable($query, 'debitAccountData.id');
    }
}
