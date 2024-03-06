<?php
namespace addons\edm\models\Statement;

use addons\edm\models\DictBank;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\EdmPayerAccountUser;
use addons\edm\models\RaiffeisenStatement\RaiffeisenStatementType;
use addons\edm\models\Sbbol2Statement\Sbbol2StatementType;
use addons\edm\models\SBBOLStatement\SBBOLStatementType;
use addons\edm\models\VTBStatementRu\VTBStatementRuType;
use addons\ISO20022\models\Camt052Type;
use addons\ISO20022\models\Camt053Type;
use addons\ISO20022\models\Camt054Type;
use common\document\Document;
use common\document\DocumentSearch;
use DateTime;
use Yii;
use yii\helpers\ArrayHelper;

class StatementSearch extends DocumentSearch
{
    private $_extModel;
    private $_extParams;

    public $number; //Номер
    public $dateCreated; //Дата создания
    public $openingBalance; //Начальное сальдо
    public $debitTurnover; //Дебетовой оборот
    public $creditTurnover; //Кредитовый оборот
    public $closingBalance; //Конечное сальдо
    public $accountNumber; //Счет
    public $periodStart; //Начало периода
    public $periodEnd; //Конец периода
    public $companyName; //Наименование организации
    public $prevLastOperationDate; //Дата последней операции
    public $currency;
    public $bankName;
    public $bankBik; //БИК
    public $orgName; // Наименование организации
    public $accountPayerName; // Наименование плательщика
    public $payer;

    public function init()
    {
        parent::init();

        $this->_extModel = new StatementDocumentExt();
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['bankName', 'safe'],
            ['bankBik', 'number'],
            ['payer', 'safe'],
            [array_values($this->_extModel->attributes()), 'safe'],
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(
                parent::attributeLabels(),
                $this->_extModel->attributeLabels(),
                ['bankBik' => Yii::t('edm', 'Bank')],
                ['payer' => Yii::t('edm', 'Company Name')]
            );
    }

    public function getDocumentExtEdmStatement()
    {
        return $this->hasOne(get_class($this->_extModel), ['documentId' => 'id']);
    }

    public function applyExtFilters($query)
    {

        if (!$this->countMode) {
            $this->_select[] = 'ext.currency as currency';
            $this->_select[] = 'ext.companyName as companyName';
            $this->_select[] = 'ext.accountNumber as accountNumber';
            $this->_select[] = 'ext.openingBalance as openingBalance';
            $this->_select[] = 'ext.debitTurnover as debitTurnover';
            $this->_select[] = 'ext.creditTurnover as creditTurnover';
            $this->_select[] = 'ext.closingBalance as closingBalance';
            $this->_select[] = 'ext.periodStart as periodStart';
            $this->_select[] = 'ext.periodEnd as periodEnd';
            $this->_select[] = 'org.name as orgName';
            $this->_select[] = 'account.payerName as accountPayerName';
        }

        // поиск по extTableName все равно будет из-за необходимости фильтра по доступным счетам :(
        $query->joinWith([$this->_extModel->tableName() . ' ext']);
        // C учетом доступных текущему пользователю счетов
        Yii::$app->edmAccountAccess->query($query, 'ext.accountNumber', true);

        $query->leftJoin(
            EdmPayerAccount::tableName() . ' account',
           'account.number = ext.accountNumber'
        );

        $query->leftJoin(
            DictOrganization::tableName() . ' org',
           'org.id = account.organizationId'
        );

        $query->andFilterWhere(['ext.currency' => $this->currency]);
        $query->andFilterWhere(['like', 'ext.companyName', $this->companyName]);
        $query->andFilterWhere(['=', 'ext.accountNumber', trim($this->accountNumber)]);

        // Убираем пробел, разделитель групп
        $this->openingBalance = str_replace(' ', '', $this->openingBalance);
        $this->debitTurnover = str_replace(' ', '', $this->debitTurnover);
        $this->creditTurnover = str_replace(' ', '', $this->creditTurnover);
        $this->closingBalance = str_replace(' ', '', $this->closingBalance);

        $query->andFilterWhere(['like', 'ext.openingBalance', $this->openingBalance]);
        $query->andFilterWhere(['like', 'ext.debitTurnover', $this->debitTurnover]);
        $query->andFilterWhere(['like', 'ext.creditTurnover', $this->creditTurnover]);
        $query->andFilterWhere(['like', 'ext.closingBalance', $this->closingBalance]);

        // Фильтр по дате начала периода
        if ($this->periodStart) {
            $datePeriodStart = DateTime::createFromFormat('d.m.Y', $this->periodStart);

            // Если введена некорректная дата, подставляем текущую
            if ($datePeriodStart === false) {
                $datePeriodStart = new DateTime();
            }

            $datePeriodStartFormat = $datePeriodStart->format('Y-m-d');
            $query->andWhere(['>=', 'ext.periodStart', $datePeriodStartFormat . ' 00:00:00']);
        }

        // Фильтр по дате конца периода
        if ($this->periodEnd) {
            $datePeriodEnd = DateTime::createFromFormat('d.m.Y', $this->periodEnd);

            if ($datePeriodEnd === false) {
                $datePeriodEnd = new DateTime();
            }

            $datePeriodEndFormat = $datePeriodEnd->format('Y-m-d');
            $query->andWhere(['<=', 'ext.periodEnd', $datePeriodEndFormat . ' 23:59:59']);
        }

        // джойнить банк нужно только если не в countMode или есть фильтр по банку
        if (!$this->countMode || $this->bankName) {
            $query->leftJoin(
                 DictBank::tableName() . ' bank',
                'bank.bik = ext.accountBik'
            );

            $this->_select[] = 'bank.name as bankName';
            $this->_select[] = 'bank.bik as bankBik';

            $query->andFilterWhere(['like', 'bank.name', $this->bankName]);
            $query->andFilterWhere(['=', 'bank.bik', $this->bankBik]);
        }

        static::applyPayerFilter($query, $this->payer, 'account');

        $query->andWhere([
            'document.type' => [
                StatementType::TYPE, VTBStatementRuType::TYPE,
                SBBOLStatementType::TYPE, Sbbol2StatementType::TYPE,
                RaiffeisenStatementType::TYPE,
		Camt052Type::TYPE, Camt053Type::TYPE, Camt054Type::TYPE
            ]
        ]);
    }

    public function searchIncomingDocuments($params, $extParams = null)
    {
        if (is_array($extParams)) {
            if (!in_array('hideNullTurnovers', $extParams)) {
                // если не содержится 'hideNullTurnovers', остальное не интересно
                $extParams = null;
            }
        } else {
            $extParams = null; // нехер передавать что попало
        }

        $this->_extParams = $extParams;

        $query = $this->buildQuery($params);

        $query->andWhere(['direction' => self::DIRECTION_IN]);

        // Если переданы дополнительные условия отбора
        if ($extParams) {
            // сейчас в extParams возможен только вариант с hideNullTurnovers,
            // поэтому дополнительные проверки не делаю

            $query->andWhere(['!=', 'ext.debitTurnover', 0]);
            $query->andWhere(['!=', 'ext.creditTurnover', 0]);
        }

        $dataProviderSort = ['id' => SORT_DESC];
        $dataProvider = $this->getDataProvider($query, $dataProviderSort);

        return $dataProvider;
    }

    public static function getUnreadCount()
    {
        $today = new \DateTime();

        $todayFrom = $today;
        $todayFrom->setTime(0, 0, 0);
        $todayFromFormat = $todayFrom->format('Y-m-d H:i:s');

        $todayTo = $today;
        $todayTo->setTime(23, 59, 59);
        $todayToFormat = $todayTo->format('Y-m-d H:i:s');

        $queryStatements = Yii::$app->terminalAccess->query(
            Document::className(),
            [
                'direction' => Document::DIRECTION_IN,
                'type' => [StatementType::TYPE, VTBStatementRuType::TYPE, SBBOLStatementType::TYPE, RaiffeisenStatementType::TYPE],
                'viewed' => 0
            ]
        );

        $statements = $queryStatements->andWhere(['between', 'dateCreate', $todayFromFormat, $todayToFormat])
                    ->all();

        $count = 0;

        // Доступные пользователю счета
        $allowedAccounts = EdmPayerAccountUser::getUserAllowAccountsNumbers(Yii::$app->user->id);

        // Проверка счетов на доступность пользователю
        foreach($statements as $statement) {
            if (!$statement->extModel) {
                continue;
            }

            $statementAccount = $statement->extModel->accountNumber;

            if (in_array($statementAccount, $allowedAccounts) !== false) {
                $count++;
            }
        }

        return $count;
    }
}
