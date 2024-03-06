<?php

namespace addons\edm\models\PaymentRegister;

use addons\edm\models\DictBank;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use common\data\InfiniteActiveDataProvider;
use common\data\InfinitePagination;
use common\document\DocumentSearch;
use common\helpers\NumericHelper;
use DateTime;
use Yii;


class PaymentRegisterPaymentOrderSearch extends PaymentRegisterPaymentOrder
{
    const FORM_NAME = 'PRPO';

    public $searchBody;
    protected $_highlights;
    public $startDate;
    public $endDate;
    public $showDeleted;
    public $payer;
    public $bankName;
    public $bankBik;
    public $accountNumber;

    private $_select;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['startDate', 'endDate', 'payer', 'bankBik', 'accountNumber'], 'safe'],
            ['showDeleted', 'boolean']
        ]);
    }

    public function formName()
    {
        return static::FORM_NAME;
    }

    public function attributeLabels()
    {
        return array_merge(
            [
                'startDate' => Yii::t('edm', 'Period start date'),
                'endDate' => Yii::t('edm', 'Period end date'),
                'showDeleted' => Yii::t('app', 'Show deleted entries'),
                'payer' => Yii::t('edm', 'Payer'),
                'bankBik' => Yii::t('edm', 'Bank'),
                'accountNumber' => Yii::t('edm', 'Account number')
            ],
            parent::attributeLabels()
        );
    }

    public function search($params, $isReadyForPaymentRegister = null)
    {

       $this->_select = DocumentSearch::createSelectList(
            [
                'id', 'registerId', 'number', 'date', 'sum', 'payerAccount', 'payerName', 'beneficiaryName',
                'currency', 'businessStatus', 'businessStatusDescription', 'paymentPurpose'
            ],
            static::tableName()
        );

        $query = Yii::$app->terminalAccess->query(PaymentRegisterPaymentOrderSearch::className());
        $query->from(static::tableName()); // . ' force index for join(termId_status)');

        // C учетом доступных текущему пользователю счетов
        Yii::$app->edmAccountAccess->query($query, 'payerAccount', true);

        $this->applyQueryFilters($params, $query);
        $query->select($this->_select);

        // Поиск по бизнес-статусу
        $query->andFilterWhere(['businessStatus' => $this->businessStatus]);

        $dataProvider = new InfiniteActiveDataProvider([
            'query'			=> $query,
            'pagination'	=> new InfinitePagination([
                'pageSize'	=> 20,
            ])
        ]);

        if ($isReadyForPaymentRegister) {
            $dataProvider->sort	= ['defaultOrder' => ['registerId' => SORT_ASC, 'id' => SORT_DESC]];
        } else {
            $dataProvider->sort	= ['defaultOrder' => ['id' => SORT_DESC]];
        }

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

    public function applyQueryFilters($params, $query)
    {
        $this->load($params);

        if (!$this->showDeleted) {
            $query->andWhere(['!=', 'status', 'deleted']);
        }

        // Если registerId = -1,
        // то фильтр по платежным поручениям без реестров
        if ($this->registerId == -1) {
            $query->andWhere(['registerId' => null]);
        } else {
            $query->andFilterWhere(['registerId' => $this->registerId]);
        }

        $query
            ->andFilterWhere(['id' => $this->id, static::tableName() . '.number' => $this->number])
            ->andFilterWhere(['=', 'payerAccount', $this->accountNumber])
            ->andFilterWhere(['=', 'payerAccount', $this->payerAccount])
            ->andFilterWhere(['like', 'paymentPurpose', $this->paymentPurpose])
            ->andFilterWhere(['like', 'beneficiaryName', $this->beneficiaryName])
            ->andFilterWhere(['=', 'currency', $this->currency])
            ->andFilterWhere(['date' => $this->date])
            ->andFilterWhere(['>=', 'date', $this->startDate])
            ->andFilterWhere(['<=', 'date', $this->endDate])
            ->andFilterWhere(['accnt.bankBik' => $this->bankBik]);

        $query->innerJoin(
            EdmPayerAccount::tableName() . ' accnt',
            'accnt.number = payerAccount'
        );

        $query->innerJoin(
            DictOrganization::tableName() . ' org',
            'accnt.organizationId = org.id'
        );

        $query->innerJoin(
            DictBank::tableName() . ' bank',
            'bank.bik = accnt.bankBik'
        );

        $this->_select[] = 'org.name as payer';
        $this->_select[] = 'bank.name as bankName';
        $this->_select[] = 'accnt.bankBik as bankBik';

        DocumentSearch::applyPayerFilter($query, $this->payer, 'accnt');

        if (!empty($this->sum)) {
            $query->andWhere(['sum' => NumericHelper::getFloatValue($this->sum)]);
        }

        // Фильтр по дате исполнения
        if ($this->dateDue) {
            $date = new DateTime($this->dateDue);
            $dateFormatted = $date->format('Y-m-d');
            $query->andWhere(['>=', 'dateDue', $dateFormatted . ' 00:00:00']);
            $query->andWhere(['<=', 'dateDue', $dateFormatted . ' 23:59:59']);
        }
    }

}