<?php

namespace common\document;

use common\base\interfaces\BlockInterface;
use common\data\InfiniteActiveDataProvider;
use common\data\InfinitePagination;
use common\models\User;
use common\models\UserTerminal;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;

class DocumentSearch extends Document
{
    protected $_select = [];
    public $countMode = false;
    /**
     * @var integer $pageSize            Page size
     * @var string  $dateCreateFrom      Create date "from"
     * @var string  $dateCreateBefore    Create date "before"
     */
    public $pageSize = 30;
    public $dateCreateFrom;
    public $dateCreateBefore;
    public $showDeleted;
    public $showDeletableOnly;
    public $substituteServices = [];
    public $typePattern;
    //Эти два атрибута нужны для вывода статистики
    public $dateInDate;
    public $count;

    public $searchBody;
    protected $_highlights;

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['showDeletableOnly'], 'boolean'],
            [['uuid', 'uuidReference', 'uuidRemote'], 'string', 'length' => [2, 36]],
            [['sender', 'receiver'], 'string', 'length' => [2, 12]],
            [['type', 'typePattern', 'typeGroup', 'status', 'sender', 'receiver'], 'string'],
            [['dateCreate', 'dateCreateFrom', 'dateCreateBefore'], 'filter', 'filter' => 'trim'],
            [['searchBody','senderParticipantName', 'receiverParticipantName', 'showDeleted', 'showDeletableOnly'], 'safe'],
            ['direction', function () {
                $diff = array_diff((array)$this->direction, [static::DIRECTION_IN, static::DIRECTION_OUT]);
                if ($diff) {
                    $this->addError('Unsupported direction values ' . implode(', ', $diff));

                    return false;
                }

                return true;
            }],
        ];
    }

    public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels(),
            ['showDeleted' => Yii::t('app', 'Show deleted entries')]
        );
    }

    public function buildQuery($params, $query = null)
    {
        $this->_select = [static::tableName() . '.*'];
        if (!$query) {
            $query = $this->find();
        }
        $query->parentModel = $this;
        $query->parentModelParams = $params;

        $this->applyQueryFilters($params, $query);
        $query->select($this->_select);

        return $query;
    }

    public function search($params)
    {
        $query = $this->buildQuery($params);
        $dataProvider = $this->getDataProvider($query, ['id' => SORT_DESC]);

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

            if (empty($this->_highlights)) {
                return new ActiveDataProvider(['query' => $this->find()->andWhere('1=0')]);
            }
        }

	return $dataProvider;
    }

    public function addStatusCondition($query, $condition)
    {
        /** todo: check if status condition was already set */
        $query->andWhere($condition);
    }

    /**
     * @param array $params
     * @param ActiveQuery $query
     * @return mixed
     */
    public function applyQueryFilters($params, $query)
    {
        $this->load($params);

        if (!$this->countMode) {
            $query->leftJoin('participant_BICDir as p1', '`p1`.`participantBIC` = `document`.`receiverParticipantId`')
                  ->leftJoin('participant_BICDir as p2', '`p2`.`participantBIC` = `document`.`senderParticipantId`');
            $this->_select[] = 'p1.name as receiverParticipantName';
            $this->_select[] = 'p2.name as senderParticipantName';
        }

        if (!$this->showDeleted) {
            $this->addStatusCondition($query, ['!=', 'document.status', 'deleted']);
        }

        // Получить роль пользователя из активной сессии
        if (Yii::$app->user->identity->role != User::ROLE_ADMIN) {
            /**
             * Если пользователь не главный админ, получаем список доступных ему терминалов
             * если у пользователя указан terminalId (CYB-3494), то выбираем только по нему,
             * иначе (дефолтное поведение) по всем привязанным терминалам
             *
             * также см. TerminalAccess::getWhere()
             */
            $terminalId = Yii::$app->user->identity->terminalId;
            if (empty($terminalId)) {
                $terminalId = array_keys(UserTerminal::getUserTerminalIds(Yii::$app->user->identity->id));
            }

            // И выводим документы согласно списку терминалов
            if ($terminalId) {
                $query->andWhere([$this::tableName() . '.terminalId' => $terminalId]);

                // Если пользователю доступны терминалы, проверяем его права доступа к аддонам
                $services = [];

                // Собираем в массив сервисы, которые доступны текущему пользователю
                // Доп. админу доступны все сервисы
                foreach (array_keys(Yii::$app->addon->getRegisteredAddons()) as $serviceId) {

                    $model = Yii::$app->getModule($serviceId)->getUserExtModel(Yii::$app->user->identity->id);
                    // Получить роль пользователя из активной сессии
                    if (Yii::$app->user->identity->role == User::ROLE_ADDITIONAL_ADMIN
                         || ($model && $model->isAllowedAccess())
                    ) {
                        $services[$serviceId] = true;
                    }
                }

                foreach($this->substituteServices as $from => $to) {
                    // если есть доступ к $from, то также дать доступ к $to
                    if (isset($services[$from])) {
                        $services[$to] = true;
                    }
                }

                // Если пользователю доступны сервисы документов, делаем по ним отбор
                if ($services) {
                    $query->andWhere(['typeGroup' => array_keys($services)]);
                } else {
                    $query->andWhere('0 = 1');
                }
            } else {
                $query->andWhere('0 = 1');
            }
        }

        $query->andFilterWhere([
            'document.id'        => $this->id,
            'document.direction' => $this->direction,
        ]);


        if ($this->status) {
            $this->addStatusCondition($query, ['document.status' => $this->status]);
        }

        if (!$this->countMode) {
            // Если senderParticipant не пустой, то поиск делался от лица юзера, а не админа
            // Если пустой, то дублируем туда sender'a, т.к. админ ищет по нему
            if (!$this->senderParticipantName) {
                $this->senderParticipantName = $this->sender;
            }

            if (!$this->receiverParticipantName) {
                $this->receiverParticipantName = $this->receiver;
            }
            $query->andFilterWhere([
                'or',
                ['like', 'document.sender', $this->senderParticipantName],
                ['like', 'p2.name', $this->senderParticipantName]
            ])
            ->andFilterWhere([
                'or',
                ['like', 'document.receiver', $this->receiverParticipantName],
                ['like', 'p1.name', $this->receiverParticipantName]
            ]);
        }

        $query
            ->andFilterWhere(['like', 'document.uuid', $this->uuid])
            ->andFilterWhere(['like', 'document.uuidReference', $this->uuidReference])
            ->andFilterWhere(['like', 'document.uuidRemote', $this->uuidRemote]);

        if (!$this->applyTypeFilter($query)) {
            $query->andFilterWhere([
                'not in',
                'document.type',
                array_merge(self::$techMessageTypes, ['PaymentStatusReport', 'VTBPayDocRu'])
            ]);
        }

        // Применяем фильтр по датам
        $this->applyDateFilters($query);

        // Применяем фильтр расширяющей модели
        $this->applyExtFilters($query);

        $this->applyShowDeletableOnlyFilter($query);
    }

    protected function applyTypeFilter(ActiveQuery $query)
    {
        if (!empty($this->type)) {
            $query->andFilterWhere(['document.type' => $this->type]);
            return true;
        } else if (!empty($this->typePattern)) {
            $query->andFilterWhere(['like', 'document.type', $this->typePattern]);
            return true;
        }

        return false;
    }

    // Расширяющие модели могут расширить фильтрацию, переопределив данный метод
    public function applyExtFilters($query)
    {
        return true;
    }

    public function applyDateFilters($query)
    {
        if ($this->dateCreateFrom || $this->dateCreateBefore) {
            $query->andFilterWhere(['>=', 'document.dateCreate', $this->dateCreateFrom . ' 00:00:00']);
            $query->andFilterWhere(['<=', 'document.dateCreate', $this->dateCreateBefore . ' 23:59:59']);
        } else if ($this->dateCreate) {
            $dateFrom = trim($this->dateCreate);
            $dateFrom = preg_replace('/^(\d{2})\.(\d{2})\.(\d{4})/', '$3-$2-$1', $dateFrom);
            $dateTo = $dateFrom;
            $len = strlen($dateFrom);
            if ($len < 11) {
                $dateFrom .= ' 00:00:00';
                $dateTo .= ' 23:59:59';
            } else {
                if ($len < 17) {
                    $dateFrom .= ':00';
                }

                $dateObject = date_create_from_format('Y-m-d H:i:s', $dateFrom);
                $dateFrom = date_sub($dateObject, \DateInterval::createFromDateString('5 min'));
                $dateFrom = $dateFrom->format('Y-m-d H:i:s');
                $dateTo = date_add($dateObject, \DateInterval::createFromDateString('10 min'));
                $dateTo = $dateTo->format('Y-m-d H:i:s');
            }

            $query->andWhere(['>=', 'document.dateCreate', $dateFrom]);
            $query->andWhere(['<=', 'document.dateCreate', $dateTo]);
        }

        if ($this->dateUpdate) {
            $query->andWhere(['>=', 'document.dateUpdate', $this->dateUpdate . ' 00:00:00']);
            $query->andWhere(['<', 'document.dateUpdate', $this->dateUpdate . ' 23:59:59']);
        }
    }

    /**
     * @param ActiveQuery $query
     */
    public function applyShowDeletableOnlyFilter($query)
    {
        if (!$this->showDeletableOnly) {
            return;
        }

        $query->andFilterWhere(['document.direction' => Document::DIRECTION_OUT]);
        $this->addStatusCondition($query, ['in', 'document.status', Document::getDeletableStatus()]);
        $query->andFilterWhere(['in', 'document.type', $this->getDeletableDocumentTypes()]);
    }

    /**
     * Функция ищет все документы, которые ожидают подписи.
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchForSigning($params)
    {
        $myRequiredLevel = Yii::$app->user->identity->signatureNumber - 1;

        $this->_select = [static::tableName() . '.*'];

        $query = $this->find()
            ->andWhere(['document.status' => self::STATUS_FORSIGNING])
            ->andWhere(['direction' => self::DIRECTION_OUT])
            ->andWhere(['>', 'signaturesRequired', 'signaturesCount'])
            ->andWhere(['signaturesCount' => $myRequiredLevel])
            ->andWhere(['document.type' => $this->getSignableDocumentTypes()]);

        // Прочие фильтры
        $this->applyQueryFilters($params, $query);

        $query->select($this->_select);

        $dataProviderSort = ['id' => SORT_DESC];
        $dataProvider = $this->getDataProvider($query, $dataProviderSort);

        return $dataProvider;
    }

       /**
     * Функция ищет все документы, которые ожидают подписи.
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchForVerification($params)
    {
        $sender = null;
        if (isset($params['sender'])) {
            $sender = $params['sender'];
        }
        $query = $this->find()
            ->andWhere(['sender' => $sender])
            ->andWhere(['document.status' => self::STATUS_FOR_CONTROLLER_VERIFICATION])
            ->andWhere(['direction' => self::DIRECTION_OUT]);

        // Прочие фильтры
        $this->applyQueryFilters($params, $query);
        $this->applyExtFilters($query);

        $dataProviderSort = ['id' => SORT_DESC];
        $dataProvider = $this->getDataProvider($query,$dataProviderSort);

        return $dataProvider;
    }

    /**
     * Search all documents with "correction" status
     *
     * @param array $params Params list
     * @return ActiveDataProvider
     */
    public function searchForCorrection($params)
    {
        $query = $this->find()
            ->andWhere(['document.status' => self::STATUS_CORRECTION])
            ->andWhere(['direction' => self::DIRECTION_OUT]);

        $this->applyQueryFilters($params, $query);

        $dataProviderSort = ['dateCreate' => SORT_DESC];
        $dataProvider = $this->getDataProvider($query,$dataProviderSort);

        return $dataProvider;
    }

    public function searchForErrors($params)
    {
        $query = $this->find()->andWhere(['document.status' => self::getErrorStatus()]);

        $this->applyQueryFilters($params, $query);

        $dataProviderSort = ['dateCreate' => SORT_DESC];
        $dataProvider = $this->getDataProvider($query,$dataProviderSort);

        return $dataProvider;
    }

    public static function searchForStatistic($alias, $dateFrom, $dateBefore)
    {
        $query = self::find();

        if ($alias == 'incoming') {
            $query->where(['direction' => self::DIRECTION_IN]);
        } else if ($alias == 'outgoing') {
            $query->where(['direction' => self::DIRECTION_OUT]);
        } else if ($alias == 'errors') {
            $query->andFilterWhere(['status' => self::getErrorStatus()]);
        }

        $query->andFilterWhere(['between', 'dateCreate', $dateFrom, $dateBefore]);
        $query->andFilterWhere(['not in', 'document.type', self::$techMessageTypes]);

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        return $dataProvider;
    }

	public function getHighlights()
	{
		return $this->_highlights;
	}

	public function hasHighLights()
	{
		return is_array($this->_highlights) && count($this->_highlights) > 0;
	}

    protected function getDataProvider($query, $sort)
    {
        $dataProvider = $this->createDataProvider($query, $sort);
        $this->setupDataProviderSort($dataProvider);
        return $dataProvider;
    }

    protected function createDataProvider($query, $defaultSortOrder): ActiveDataProvider
    {
        return new InfiniteActiveDataProvider([
            'query'      => $query,
            'sort'       => [
                'defaultOrder' => $defaultSortOrder
            ],
            'pagination' => new InfinitePagination([
                'pageSize' => $this->pageSize,
            ])
        ]);
    }

    private function setupDataProviderSort(DataProviderInterface $dataProvider): void
    {
        $dataProvider->getSort()->attributes['senderParticipantName'] = [
            'asc' => [
                'senderParticipantName' => SORT_ASC, 'sender' => SORT_ASC
            ],
            'desc' => [
                'senderParticipantName' => SORT_DESC, 'sender' => SORT_DESC,
            ]
        ];

        $dataProvider->getSort()->attributes['receiverParticipantName'] = [
            'asc' => [
                'receiverParticipantName' => SORT_ASC,'receiver' => SORT_ASC
            ],
            'desc' => [
                'receiverParticipantName' => SORT_DESC,'receiver' => SORT_DESC,
            ]
        ];

        $dataProvider->getSort()->attributes['periodStart'] = [
            'asc' => [
                'periodStart' => SORT_ASC
            ],
            'desc' => [
                'periodStart' => SORT_DESC
            ]
        ];

        $dataProvider->getSort()->attributes['periodEnd'] = [
            'asc' => [
                'periodEnd' => SORT_ASC
            ],
            'desc' => [
                'receiverParticipantName' => SORT_DESC, 'receiver' => SORT_DESC,
            ]
        ];

        $dataProvider->getSort()->attributes['extModel.date'] = [
            'asc' => [
                'extModel.date' => SORT_ASC
            ],
            'desc' => [
                'extModel.date' => SORT_DESC
            ]
        ];
    }

    protected function getDeletableDocumentTypes()
    {
        $addons = Yii::$app->addon->getRegisteredAddons();
        $types = [];
        foreach ($addons as $serviceId => $addon) {
            if (!$addon instanceof BlockInterface) {
                continue;
            }
            $types = array_merge($types, $addon->getDeletableDocumentTypes(Yii::$app->user));
        }
        return $types;
    }

    protected function getSignableDocumentTypes()
    {
        $addons = Yii::$app->addon->getRegisteredAddons();
        $types = [];
        foreach ($addons as $serviceId => $addon) {
            if (!$addon instanceof BlockInterface) {
                continue;
            }
            $types = array_merge($types, $addon->getSignableDocumentTypes(Yii::$app->user));
        }
        return $types;
    }

    public static function createSelectList($attributes, $tableName = null)
    {
        if (!$tableName) {
            $tableName = static::tableName();
        }

        $out = [];
        foreach($attributes as $attr) {
            $out[] = $tableName . '.' . $attr;
        }

        return $out;
    }

    public static function applyPayerFilter(ActiveQuery $query, ?string $payer, string $accountTableAlias)
    {
        // Фильтр в зависимости от того, по какому значению фильтруем.
        // Либо по организации, либо по наименованию плательщика из счета
        if ($payer) {
            // Определение типа фильтра
            if (stristr($payer, 'organization')) {
                // По организации счета
                $payer = str_replace('_organization', '', $payer);
                $query->andWhere([
                    'or',
                    ["$accountTableAlias.payerName" => null],
                    ["$accountTableAlias.payerName" => ''],
                ]);
                $query->andFilterWhere(["$accountTableAlias.organizationId" => $payer]);
            } else if (stristr($payer, 'payerName')) {
                // По наименованию плательщика счета
                $payer = str_replace('_payerName', '', $payer);
                $query->andWhere(["$accountTableAlias.payerName" => $payer]);
            }
        }
    }
}
