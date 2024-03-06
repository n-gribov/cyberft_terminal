<?php
namespace addons\ISO20022\models;

use addons\ISO20022\helpers\ISO20022Helper;
use common\document\DocumentSearch;
use common\helpers\DocumentHelper;
use common\models\Terminal;
use Yii;
use yii\helpers\ArrayHelper;

class ISO20022Search extends DocumentSearch
{
    private $_extISO20022Model;

    public $statusCode;
    public $remoteSenderId;
    public $originalFilename;

    // auth.026
    public $subject;
    public $descr;
    public $typeCode;
    public $fileName;

    //pain.001
    public $count;
    public $sum;
    public $currency;

    //camt.053
    public $account;
    public $periodBegin;
    public $periodEnd;

    public $documentId;
    public $documentNumber;

    public $msgId;
    public $mmbId;

    public function init()
    {
        parent::init();

        $this->_extISO20022Model = new ISO20022DocumentExt();
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
            [
                [array_values($this->_extISO20022Model->attributes()), 'required'],
                [['fileName', 'statusCode', 'msgId', 'mmbId'], 'safe']
            ]
        );
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(),
            $this->_extISO20022Model->attributeLabels());
    }

    /**
     * Метод поиска документов свободного формата
     * @param $params
     * @return \yii\data\ActiveDataProvider
     */
    public function searchFreeFormat($params)
    {
        $query = $this->find();

        // Отбор по документам свободного формата - Auth.026, Auth.024
        $filterTypes = [
            Auth026Type::TYPE,
            Auth024Type::TYPE
        ];

        $query->where(['in', $this->tableName() . '.type', $filterTypes]);

        return $this->search($params, $query, 'freeFormat');
    }

    /**
     * Метод поиска документов валютного контроля
     */
    public function searchForeignCurrencyControl($params)
    {
        $query = $this->find();

        // Отбор по документам валютного контроля - Auth.025, Auth.024, Auth.018
        $filterTypes = [
            Auth025Type::TYPE,
            Auth024Type::TYPE,
            Auth018Type::TYPE
        ];

        $query->where(['in', $this->tableName() . '.type', $filterTypes]);

        return $this->search($params, $query, 'foreign_currency');
    }

    /*
    * Метод поиска платежных документов
    */
    public function searchPayments($params)
    {
        $query = $this->find();

        // Отбор по платежным документам - pain.001 и sberbank payment order
        $filterTypes = [
            Pain001Type::TYPE
        ];

        $query->where(['in', $this->tableName() . '.type', $filterTypes]);

        return $this->search($params, $query, 'payments');
    }

    /**
     * Метод поиска выписок
     */
    public function searchStatements($params)
    {
        $query = $this->find();

        // Отбор по документам свободного формата - camt.053, camt.054
        $filterTypes = [Camt053Type::TYPE, Camt054Type::TYPE];

        $query->where(['in', $this->tableName() . '.type', $filterTypes]);

        return $this->search($params, $query, 'statements');
    }

    public function search($params, $query = null, $subtype = null)
    {
        $this->_select = [static::tableName() . '.*'];

        // Если не передано подготовленного ранее запроса
        if (is_null($query)) {
            $query = $this->find();
        }

        $this->applyQueryFilters($params, $query);

        // Применяем дополнительные фильтры для подтипа документов ISO
        if ($subtype == 'freeFormat') {
            $this->applyFreeFormatFilters($query);
        } else if ($subtype == 'payments') {
            $this->applyPaymentsFilters($query);
        } else if ($subtype == 'statements') {
            $this->applyStatementsFilters($query);
        } else if ($subtype == 'foreign_currency') {
            $this->applyForeignCurrencyControlFilter($query);
        }

        $query->andWhere(['typeGroup' => ['ISO20022', 'edm']]);

        // Фильтр по дате создания документа
        if ($this->dateCreate) {

            $date = \DateTime::createFromFormat('Y.m.d', $this->dateCreate);

            if ($date === false) {
                $date = new \DateTime($this->dateCreate);
            }

            $dateFormatted = $date->format('Y-m-d');
            $query->andWhere(['>=', 'dateCreate', $dateFormatted . ' 00:00:00']);
            $query->andWhere(['<=', 'dateCreate', $dateFormatted . ' 23:59:59']);
        }

        //$query->andFilterWhere(["DATE_FORMAT(dateCreate,'%Y.%m.%d')" => $this->dateCreate]);

        $query->select($this->_select);

        $dataProviderSort = ['id' => SORT_DESC];
        $dataProvider = $this->getDataProvider($query, $dataProviderSort);

        $dataProvider->sort->attributes['typeCode'] = [
            'asc' => ['typeCode' => SORT_ASC],
            'desc' => ['typeCode' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['subject'] = [
            'asc' => ['subject' => SORT_ASC],
            'desc' => ['subject' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['descr'] = [
            'asc' => ['descr' => SORT_ASC],
            'desc' => ['descr' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['msgId'] = [
            'asc' => ['msgId' => SORT_ASC],
            'desc' => ['msgId' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['mmbId'] = [
            'asc' => ['mmbId' => SORT_ASC],
            'desc' => ['mmbId' => SORT_DESC]
        ];

        return $dataProvider;
    }

    public function applyExtFilters($query)
    {
        $extTableName = $this->_extISO20022Model->tableName();

        $this->_select[] = $extTableName . '.msgId as msgId';
        $this->_select[] = $extTableName . '.mmbId as mmbId';
        $this->_select[] = $extTableName . '.typeCode as typeCode';

        $query->joinWith([$extTableName]);
        $query->joinWith(['senderTerminal']);

        // Фильтр по типу документов
        $docTypes = [];

        $typeLabels = Yii::$app->registry->getModuleTypes('ISO20022');

        foreach($typeLabels as $key => $value) {
            $docTypes[] = $key;
        }

        $docTypes = array_merge($docTypes, [Camt052Type::TYPE, Camt053Type::TYPE, Camt054Type::TYPE]);
        $query->andFilterWhere(['document.type' => $docTypes]);

        // Фильтр по теме сообщения
        $query->andFilterWhere(['like', "{$extTableName}.subject", $this->subject]);

        // Фильтр по описанию сообщения
        $query->andFilterWhere(['like', "{$extTableName}.descr", $this->descr]);

        // Фильтр по типу кода сообщения
        $query->andFilterWhere(['like', "{$extTableName}.typeCode", $this->typeCode]);

        // Фильтр по имени исходного файла
        $query->andFilterWhere(['like', "{$extTableName}.originalFilename", $this->originalFilename]);

        // Фильтр по статусу обработки в системе получателя
        $query->andFilterWhere(['like', "{$extTableName}.statusCode", $this->statusCode]);

        // Фильтр по ID документа
        $query->andFilterWhere(['like', "{$extTableName}.msgId", $this->msgId]);
    }

    /**
     * Дополнительные фильтры для документов свободного формата
     * @param $query
     */
    public function applyFreeFormatFilters($query)
    {
        // Имя таблицы с ext-моделью
        $extTableName = $this->_extISO20022Model->tableName();

        // Фильтр по теме сообщения
        $query->andFilterWhere(['like', "{$extTableName}.subject", $this->subject]);

        // Фильтр по описанию сообщения
        $query->andFilterWhere(['like', "{$extTableName}.descr", $this->descr]);

        // Фильтр по типу кода сообщения
        $query->andFilterWhere(['like', "{$extTableName}.typeCode", $this->typeCode]);

        // Фильтр по имени вложения
        $query->andFilterWhere(['like', "{$extTableName}.fileName", $this->fileName]);

        // Фильтр по статусу обработку во внешней системе пользователя
        $query->andFilterWhere(['like', "{$extTableName}.statusCode", $this->statusCode]);
    }

    /**
     * Дополнительные фильтры для платежных документов
     * @param $query
     */
    public function applyPaymentsFilters($query)
    {
        // Имя таблицы с ext-моделью
        $extTableName = $this->_extISO20022Model->tableName();

        // Фильтр по количеству документов
        $query->andFilterWhere(['like', "{$extTableName}.count", $this->count]);

        // Фильтр по сумме документов
        $query->andFilterWhere(['like', "{$extTableName}.sum", $this->sum]);

        // Фильтр по валюте документов
        $query->andFilterWhere(['like', "{$extTableName}.currency", $this->currency]);
    }

    /**
     * Дополнительные фильтры для выписок
     * @param $query
     */
    public function applyStatementsFilters($query)
    {
        // Имя таблицы с ext-моделью
        $extTableName = $this->_extISO20022Model->tableName();

        // Фильтр по валюте
        $query->andFilterWhere(['like', "{$extTableName}.currency", $this->currency]);

        // Фильтр по номеру счета
        $query->andFilterWhere(['like', "{$extTableName}.account", $this->account]);

        // Фильтр по дате начала периода
        $query->andFilterWhere(["DATE_FORMAT({$extTableName}.periodBegin, '%Y.%m.%d')" => $this->periodBegin]);

        // Фильтр по дате конца периода
        $query->andFilterWhere(["DATE_FORMAT({$extTableName}.periodEnd, '%Y.%m.%d')" => $this->periodEnd]);
    }

    public function applyForeignCurrencyControlFilter($query)
    {
        // Имя таблицы с ext-моделью
        $extTableName = $this->_extISO20022Model->tableName();

        // Фильтр по коду компании в системе получателя
        $query->andFilterWhere(['like', "{$extTableName}.mmbId", $this->mmbId]);

    }

     /*

     * Get document type label
     *
     * @return string
     */
    public function getDocTypeLabel()
    {
        return !is_null($this->type) && array_key_exists($this->type,
            ISO20022Helper::getDocTypeLabels()) ? ISO20022Helper::getDocTypeLabels()[$this->type] : '';
    }
    /**
     * Get document extension
     *
     * @return ActiveQuery
     */
    public function getDocumentExtISO20022()
    {
        return $this->hasOne(get_class($this->_extISO20022Model), ['documentId' => 'id']);
    }

    /**
     * Соединение с таблицей терминалов
     */
    public function getSenderTerminal()
    {
        return $this->hasOne(Terminal::className(), ['terminalId' => 'sender']);
    }

    /**
     * Получение наименования бизнес-статуса
     * @return mixed
     */
    public function getBusinessStatusTranslation()
    {
        $labels = DocumentHelper::getBusinessStatusesList();

        $extModel = $this->documentExtISO20022;

        if (isset($extModel->statusCode)) {
            return isset($labels[$extModel->statusCode]) ? $labels[$extModel->statusCode] : $extModel->statusCode;
        }
    }

    public function getTerminalSenderRemoteId()
    {
        if (!$this->senderTerminal) {
             return '';
        }

        $terminal = $this->senderTerminal;

        return $terminal->getRemoteIds()
                ->where(['terminalReceiver' => $this->receiver])
                ->orWhere(['terminalReceiver' => null])->one();
    }
}
