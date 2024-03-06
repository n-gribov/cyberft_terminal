<?php
namespace addons\edm\models\StatementRequest;

use addons\edm\models\EdmPayerAccount;
use addons\edm\models\SBBOLStmtReq\SBBOLStmtReqType;
use addons\edm\models\VTBStatementQuery\VTBStatementQueryType;
use common\base\traits\ForSigningCountable;
use common\document\DocumentSearch;
use DateTime;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class StatementRequestSearch extends DocumentSearch
{
    use ForSigningCountable;

    /** @var StatementRequestExt */
    private $extModel;

    public $startDate;
    public $endDate;
    public $accountNumber;

    public function init()
    {
        parent::init();
        $this->extModel = new StatementRequestExt();
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [array_values($this->extModel->attributes()), 'safe'],
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), $this->extModel->attributeLabels());
    }

    public function applyExtFilters($query)
    {
        $extTableName = $this->extModel->tableName();

        $query->joinWith([$extTableName]);

        $query->leftJoin(
            EdmPayerAccount::tableName() . ' account',
            "account.number = $extTableName.accountNumber"
        );

        $query->andWhere(['in', 'document.type', [StatementRequestType::TYPE, VTBStatementQueryType::TYPE, SBBOLStmtReqType::TYPE]]);

        // Фильтр по номеру счета
        $query->andFilterWhere(['like', $extTableName . '.accountNumber', trim($this->accountNumber)]);

        // C учетом доступных текущему пользователю счетов
        $query = Yii::$app->edmAccountAccess->query($query, $extTableName . '.accountNumber', true);

        // Фильтр по дате начала периода
        if ($this->startDate) {
            // Если введена некорректная дата,
            // подставляем текущую

            $datePeriodStart = DateTime::createFromFormat('d.m.Y', $this->startDate);

            if ($datePeriodStart === false) {
                $datePeriodStart = new DateTime();
            }

            $datePeriodStartFormat = $datePeriodStart->format('Y-m-d');
            $query->andWhere(['>=', $extTableName . '.startDate', $datePeriodStartFormat . ' 00:00:00']);
        }

        // Фильтр по дате конца периода
        if ($this->endDate) {
            $datePeriodEnd = DateTime::createFromFormat('d.m.Y', $this->endDate);

            if ($datePeriodEnd === false) {
                $datePeriodEnd = new DateTime();
            }

            $datePeriodEndFormat = $datePeriodEnd->format('Y-m-d');
            $query->andWhere(['<=', $extTableName . '.endDate', $datePeriodEndFormat . ' 23:59:59']);
        }
    }

    public function getDocumentExtEdmStatementRequest()
    {
        return $this->hasOne(StatementRequestExt::className(), ['documentId' => 'id']);
    }

    protected function applySignaturePermissionFilter(ActiveQuery $query): void
    {
        Yii::$app->edmAccountAccess->querySignable($query, 'account.id');
    }
}
