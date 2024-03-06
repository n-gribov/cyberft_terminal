<?php

namespace addons\edm\models\PaymentRegister;

use addons\edm\models\DictBank;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\Pain001Rub\Pain001RubType;
use addons\edm\models\SBBOLPayDocRu\SBBOLPayDocRuType;
use addons\edm\models\VTBPayDocRu\VTBPayDocRuType;
use addons\edm\models\VTBRegisterRu\VTBRegisterRuType;
use common\base\traits\ForSigningCountable;
use common\data\InfiniteActiveDataProvider;
use common\data\InfinitePagination;
use common\document\Document;
use common\document\DocumentSearch;
use common\helpers\NumericHelper;
use Yii;
use yii\db\ActiveQuery;

class PaymentRegisterSearch extends DocumentSearch
{
    use ForSigningCountable;

    protected $_highlights;
    public $searchBody;
    public $pageSize = 20;

    public $payer;
    public $accountNumber;
    public $orgName;
    public $accntName;
    public $businessStatusDescription;
    public $businessStatus;
    public $sum;
    public $currency;
    public $beneficiaryName;
    public $paymentPurpose;
    public $bankName;
    public $bankBik;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [
                [
                    'payer', 'accountNumber', 'dateCreate', 'dateUpdate', 'businessStatusDescription',
                    'businessStatus', 'sum', 'currency', 'beneficiaryName', 'paymentPurpose',
                    'signaturesRequired', 'signaturesCount', 'bankBik',
                ],
                'safe'
            ]
        ]);
    }

    public function formName()
    {
        return 'PR';
    }

    public function attributeLabels()
    {
        return array_merge(
            [
                'payer' => Yii::t('edm', 'Payer'),
                'accountNumber' => Yii::t('edm', 'Account number'),
                'currency' => Yii::t('edm', 'Currency'),
                'sum' => Yii::t('edm', 'Sum'),
                'count' => Yii::t('edm', 'Count'),
                'businessStatus' => Yii::t('edm', 'Business status'),
                'beneficiaryName' => Yii::t('edm', 'Payee'),
                'paymentPurpose' => Yii::t('edm', 'Payment Purpose'),
                'bankBik' => Yii::t('edm', 'Bank'),
            ],
            parent::attributeLabels()
        );
    }


    public function search($params)
    {
        $query = Yii::$app->terminalAccess->query(static::className());
        $this->buildQuery($params, $query);

        // C учетом доступных текущему пользователю счетов
        Yii::$app->edmAccountAccess->query($query, 'accountId');

        $query->with('paymentOrdersWithErrors');

        // Поиск по бизнес-статусу
        $query->andFilterWhere(['businessStatus' => $this->businessStatus]);
        $query->andFilterWhere(['accnt.bankBik' => $this->bankBik]);

        $query->innerJoin(
            DictBank::tableName() . ' bank',
           'bank.bik = accnt.bankBik'
        );

        $dataProvider = new InfiniteActiveDataProvider([
            'query'			=> $query,
            'sort'          => ['defaultOrder' => ['dateCreate' => SORT_DESC]],
            'pagination'	=> new InfinitePagination([
                'pageSize'	=> 50,
            ])
        ]);

        if (!empty($this->searchBody)) {
            $models = $dataProvider->getModels();
            $idList = [];

            foreach($models as $model) {
                $idList[] = $model->id;
            }

            $result = Yii::$app->elasticsearch->search($this->typeGroup, '', $this->searchBody, $idList, true);

            if ($result !== false) {
                $this->_highlights = [];

                foreach($result['hits']['hits'] as $row) {
                    $allHighlights = '';

                    foreach($row['highlight'] as $key => $field) {
                        $allHighlights .= $key . ':' . $field[0] . '<br>';
                    }

                    $this->_highlights[$row['_id']] = $row['highlight'];
                }
            }
        }

        return $dataProvider;
    }

    public function getDocumentExtEdmPaymentRegister()
    {
        return $this->hasOne(PaymentRegisterDocumentExt::className(), ['documentId' => 'id']);
    }

    public function getPayerName()
    {
        return $this->accntName ?: $this->orgName;
    }

    public function applyExtFilters($query)
    {
        // Фильтр по типу документов
        $query->andFilterWhere(['document.type' => [
            PaymentRegisterType::TYPE, VTBPayDocRuType::TYPE, VTBRegisterRuType::TYPE, SBBOLPayDocRuType::TYPE, Pain001RubType::TYPE]
        ]);

        $this->_select[] = 'ext.accountNumber';
        $this->_select[] = 'ext.sum';
        $this->_select[] = 'ext.currency';
        $this->_select[] = 'ext.count';
        $this->_select[] = 'ext.businessStatus';
//        $this->_select[] = 'ext.payer';
        $this->_select[] = 'org.name as orgName';
        $this->_select[] = 'accnt.payerName as accntName';
        $this->_select[] = 'dictbank.name as bankName';
        $this->_select[] = 'accnt.bankBik as bankBik';

        $query->innerJoin(
            PaymentRegisterDocumentExt::tableName() . ' ext',
            'ext.documentId = ' . static::tableName() . '.id'
        );

      
        $query->andFilterWhere(['accnt.bankBik' => $this->bankBik]);
        $query->andFilterWhere(['ext.businessStatus' => $this->businessStatus]);
        $query->andFilterWhere(['ext.accountNumber' => $this->accountNumber]);
        $query->andFilterWhere(['ext.currency' => $this->currency]);
        $query->andFilterWhere(['ext.count' => $this->count]);
        $query->andFilterWhere(['signaturesRequired' => $this->signaturesRequired]);
        $query->andFilterWhere(['signaturesCount' => $this->signaturesCount]);
        $query->andFilterWhere(['like', 'ext.businessStatusDescription', $this->businessStatusDescription]);

        if (!empty($this->sum)) {
            $query->andWhere(['sum' => NumericHelper::getFloatValue($this->sum)]);
        }

        $query->innerJoin(
            EdmPayerAccount::tableName() . ' accnt',
                'accnt.number = ext.accountNumber'
        );

        $query->innerJoin(
            DictBank::tableName() . ' dictbank',
           'dictbank.bik = accnt.bankBik'
        );

         $query->innerJoin(
            DictOrganization::tableName() . ' org',
            'org.id = ext.orgId'
        );


        static::applyPayerFilter($query, $this->payer, 'accnt');

        // C учетом доступных текущему пользователю счетов
        Yii::$app->edmAccountAccess->query($query, 'accountId');

    }

    /**
     * Платежные поручения, входящие в состав реестра
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentOrders()
    {
        return $this->hasMany(PaymentRegisterPaymentOrder::className(), ['registerId' => 'id']);
    }

    /**
     * Платежные поручения с ошибочными статусами, входящие в состав реестра
     * @return $this
     */
    public function getPaymentOrdersWithErrors()
    {
        return $this->getPaymentOrders()->andWhere(['businessStatus' => [
                PaymentRegisterDocumentExt::STATUS_REJECTED, PaymentRegisterDocumentExt::STATUS_PENDING]]
        );
    }

    /**
     * Проверка: должен ли реестр быть выделен в журнале
     * @return bool
     */
    public function hasAlertAttributes()
    {
        // Если реестр удален или ему отказно в подписании
        if (in_array($this->status, [Document::STATUS_SIGNING_REJECTED, Document::STATUS_DELETED])) {
            return true;
        }

        // Если его бизнес-статус - отклонен
        if ($this->businessStatus == PaymentRegisterDocumentExt::STATUS_REJECTED) {
            return true;
        }

        return false;
    }

    /**
     * Проверка: сколько платежных поручений с ошибками содержится в реестре
     * @return int
     */
    public function hasPaymentOrdersWithErrors()
    {
        return count($this->paymentOrdersWithErrors);
    }

    protected function applySignaturePermissionFilter(ActiveQuery $query): void
    {
        Yii::$app->edmAccountAccess->querySignable($query, 'accountId');
    }
}
