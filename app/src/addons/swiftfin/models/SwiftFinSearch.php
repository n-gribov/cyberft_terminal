<?php

namespace addons\swiftfin\models;

use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt;
use addons\swiftfin\helpers\SwiftfinHelper;
use common\document\Document;
use common\document\DocumentSearch;
use common\helpers\NumericHelper;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class SwiftFinSearch extends DocumentSearch
{
    private $_swiftFinDocumentExt;

    public $aliases = [];

    public $currency;
    public $sum;
    public $date;
    public $absId;
    public $nestedItemsCount;
    public $documentId;
    public $operationReference;
    public $valueDate;
    public $correctionReason;

    public function init()
    {
        parent::init();

        $this->_swiftFinDocumentExt = new SwiftFinDocumentExt();
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [array_values($this->_swiftFinDocumentExt->attributes()), 'safe'],
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), $this->_swiftFinDocumentExt->attributeLabels());
    }

    /**
     * Get document type labels list
     *
     * @return array
     */
    public static function getDocTypeLabels()
    {
        $typeLabels = Yii::$app->registry->getModuleTypes('swiftfin');

        foreach($typeLabels as $key => $value) {
            $typeLabels[$key] = Yii::t('app', $key);
        }

        return $typeLabels;
    }

    /**
     * Get document type label
     *
     * @return string
     */
    public function getDocTypeLabel()
    {
        return !is_null($this->type) && array_key_exists($this->type,
            self::getDocTypeLabels()) ? self::getDocTypeLabels()[$this->type] : '';
    }

    /**
     * Get document extension SwiftFin
     *
     * @return ActiveQuery
     */
    public function getDocumentExtSwiftFin()
    {
        $model = new SwiftFinDocumentExt();
        return $this->hasOne(get_class($model), ['documentId' => 'id']);
    }

    public function getDocumentExtForeignCurrencyOperation()
    {
        return $this->hasOne(ForeignCurrencyOperationDocumentExt::className(),  ['documentId' => 'id']);
    }

    /**
     * Search all documents with "correction" status
     *
     * @param array $params Params list
     * @return ActiveDataProvider
     */

    public function applyExtFilters($query)
    {
        $extTableName = $this->_swiftFinDocumentExt->tableName();
        $extTableNameEdm = ForeignCurrencyOperationDocumentExt::tableName();

        $query->joinWith([$extTableName]);
        $query->joinWith([$extTableNameEdm]);
        
        $this->_select[] = 'coalesce(documentExtSwiftFin.operationReference, documentExtEdmForeignCurrencyOperation.numberDocument) as operationReference';  
        $this->_select[] = 'coalesce(documentExtSwiftFin.currency, documentExtEdmForeignCurrencyOperation.currency) as currency';        
        $this->_select[] = 'coalesce(documentExtSwiftFin.sum, documentExtEdmForeignCurrencyOperation.sum) as sum';
        $this->_select[] = 'documentExtSwiftFin.correctionReason as correctionReason';

        $query->orWhere(['and',
            ['document.status' => Document::STATUS_SERVICE_PROCESSING],
            ["{$extTableName}.extStatus" => $this->status]
        ]);

        $query->andFilterWhere([
            'or',
            ['typeGroup' => 'swiftfin'],
            ['typeGroup' => 'edm', 'document.type' => 'MT103']
        ])->andFilterWhere(['like', "{$extTableName}.operationReference", $this->operationReference])
        ->andFilterWhere(['like', "{$extTableName}.currency", $this->currency]) 
        ->andFilterWhere(['like', "{$extTableName}.sum", $this->sum])
        ->andFilterWhere(['like', "{$extTableName}.valueDate", $this->valueDate])
        ->andFilterWhere(['like', "{$extTableName}.correctionReason", $this->correctionReason]);
        
        if (!empty($this->sum)) {
            $this->sum = NumericHelper::getFloatValue($this->sum);
        }
    }

    public function getAliasLabels($usedAliases = [])
    {
        $aliasLabels = [
            self::ALIAS_SEARCH_DELIVERED   => Yii::t('other', 'Outbox, delivered to the addressee'),
            self::ALIAS_SEARCH_UNDELIVERED => Yii::t('other', 'Outbox, not delivered to the addressee'),
            self::ALIAS_INCOMING           => Yii::t('other', 'Inbox'),
        ];

        if (count($usedAliases)) {
            $aliasLabels = array_intersect_key($aliasLabels, $usedAliases);
        }

        return $aliasLabels;
    }

    /**
     * Функция ищет все документы
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $this->_select = [static::tableName() . '.*'];
        $query = $this->find();
        $this->applyQueryFilters($params, $query);
        $query->select($this->_select);

        $dataProviderSort = ['id' => SORT_DESC];
        $dataProvider = $this->getDataProvider($query, $dataProviderSort);
        
        $dataProvider->sort->attributes['operationReference'] = [
            'asc' => [
                'operationReference' => SORT_ASC
            ],
            'desc' => [
                'operationReference' => SORT_DESC
            ]
        ];
        
        $dataProvider->sort->attributes['currency'] = [
            'asc' => [
                'currency' => SORT_ASC
            ],
            'desc' => [
                'currency' => SORT_DESC
            ]
        ];        
        
        $dataProvider->sort->attributes['documentExtSwiftFin.valueDate'] = [
            'asc' => [
                'valueDate' => SORT_ASC
            ],
            'desc' => [
                'valueDate' => SORT_DESC
            ]
        ];
        
        $dataProvider->sort->attributes['sum'] = [
            'asc' => [
                'sum' => SORT_ASC
            ],
            'desc' => [
                'sum' => SORT_DESC
            ]
        ];       
        
        return $dataProvider;
    }    
    
    public function searchForSigning($params)
    {
        $dataProvider = parent::searchForSigning($params);   
        
        $dataProvider->sort->attributes['operationReference'] = [
            'asc' => [
                'operationReference' => SORT_ASC
            ],
            'desc' => [
                'operationReference' => SORT_DESC
            ]
        ];
        
        $dataProvider->sort->attributes['currency'] = [
            'asc' => [
                'currency' => SORT_ASC
            ],
            'desc' => [
                'currency' => SORT_DESC
            ]
        ];        
        
        $dataProvider->sort->attributes['valueDate'] = [
            'asc' => [
                'valueDate' => SORT_ASC
            ],
            'desc' => [
                'valueDate' => SORT_DESC
            ]
        ];
        
        $dataProvider->sort->attributes['sum'] = [
            'asc' => [
                'sum' => SORT_ASC
            ],
            'desc' => [
                'sum' => SORT_DESC
            ]
        ]; 

        return $dataProvider;
    }
    
    public function searchForCorrection($params)
    {
        $this->_select = [static::tableName() . '.*'];

        $query = $this->find()
            ->andFilterWhere(['typeGroup' => 'swiftfin'])
            ->andWhere(['document.status' => self::STATUS_CORRECTION])
            ->andWhere(['direction' => self::DIRECTION_OUT]);

        $this->applyQueryFilters($params, $query);

        $query->select($this->_select);

        $dataProviderSort = ['dateCreate' => SORT_DESC];
        $dataProvider = $this->getDataProvider($query, $dataProviderSort);        
        
        $dataProvider->sort->attributes['type'] = [
            'asc' => [
                'type' => SORT_ASC
            ],
            'desc' => [
                'type' => SORT_DESC
            ]
        ];
        
        $dataProvider->sort->attributes['correctionReason'] = [
            'asc' => [
                'correctionReason' => SORT_ASC
            ],
            'desc' => [
                'correctionReason' => SORT_DESC
            ]
        ];     

        return $dataProvider;
    }

    public function searchForUserVerify($params)
    {
        $params = array_merge($params, [
            'direction' => self::DIRECTION_OUT,
        ]);

        $module = Yii::$app->getModule('swiftfin');

        $this->_select = [static::tableName() . '.*'];

        $query = self::find();
        $query->andFilterWhere(['in', 'document.type', $module->getUserVerifyDocType()]);
        $query->andFilterWhere(['in', 'document.status', static::getUserVerifiableStatus()]);

        $this->applyQueryFilters($params, $query);

        $query->select($this->_select);

        $dataProviderSort = ['dateCreate' => SORT_DESC];
        $dataProvider = $this->getDataProvider($query, $dataProviderSort);
        
        $dataProvider->sort->attributes['operationReference'] = [
            'asc' => [
                'operationReference' => SORT_ASC
            ],
            'desc' => [
                'operationReference' => SORT_DESC
            ]
        ];
        
        $dataProvider->sort->attributes['currency'] = [
            'asc' => [
                'currency' => SORT_ASC
            ],
            'desc' => [
                'currency' => SORT_DESC
            ]
        ];        
        
        $dataProvider->sort->attributes['valueDate'] = [
            'asc' => [
                'valueDate' => SORT_ASC
            ],
            'desc' => [
                'valueDate' => SORT_DESC
            ]
        ];
        
        $dataProvider->sort->attributes['sum'] = [
            'asc' => [
                'sum' => SORT_ASC
            ],
            'desc' => [
                'sum' => SORT_DESC
            ]
        ]; 

        return $dataProvider;
    }

    /**
     * Функция ищет все документы, которые ожидают утверждения.
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchForAuthorization($params)
    {
        $params = array_merge($params, [
            'direction' => self::DIRECTION_OUT,
        ]);

        $module = Yii::$app->getModule('swiftfin');
        $extUser = $module->getUserExtModel(Yii::$app->user->id);

        $filteredModels = [];

        $this->_select = [static::tableName() . '.*'];

        if ($extUser->role == SwiftFinUserExt::ROLE_AUTHORIZER
            || $extUser->role == SwiftFinUserExt::ROLE_PREAUTHORIZER) {

            $query = static::find()
                ->from(Document::tableName() . ' AS document')
                ->innerJoin(SwiftFinDocumentExt::tableName() . ' AS docx', [
                    'and',
                    ['document.status'  => self::STATUS_SERVICE_PROCESSING],
                    'docx.documentId = document.id',
                    ['docx.extStatus' =>
                        $extUser->role == SwiftFinUserExt::ROLE_AUTHORIZER
                                ? SwiftFinDocumentExt::STATUS_AUTHORIZATION
                                : SwiftFinDocumentExt::STATUS_PREAUTHORIZATION]

                ])->orderBy('dateCreate DESC');

            $this->applyQueryFilters($params, $query);
            $query->select($this->_select);
            
            foreach ($query->each() as $document) {
                if (SwiftfinHelper::isAuthorizable($document, Yii::$app->user->id)) {
                    $filteredModels[$document->id] = $document;
                }
            }
        }
            
        $dataProvider = new ArrayDataProvider([
            'allModels' => $filteredModels,
            'key' => 'id',
            'sort' => [
              'attributes' => $this->attributes(),
            ]
        ]);
        
        $dataProvider->sort->attributes['operationReference'] = [
            'asc' => [
                'operationReference' => SORT_ASC
            ],
            'desc' => [
                'operationReference' => SORT_DESC
            ]
        ];
        
        $dataProvider->sort->attributes['currency'] = [
            'asc' => [
                'currency' => SORT_ASC
            ],
            'desc' => [
                'currency' => SORT_DESC
            ]
        ];        
        
        $dataProvider->sort->attributes['valueDate'] = [
            'asc' => [
                'valueDate' => SORT_ASC
            ],
            'desc' => [
                'valueDate' => SORT_DESC
            ]
        ];
        
        $dataProvider->sort->attributes['sum'] = [
            'asc' => [
                'sum' => SORT_ASC
            ],
            'desc' => [
                'sum' => SORT_DESC
            ]
        ];            
        

        return $dataProvider;


    }

    /**
     * Функция ищет все документы c ошибочными статусами
     * как подписанта
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchForErrors($params)
    {
        $this->_select = [static::tableName() . '.*'];
        $query = self::find();
        $query->andFilterWhere(['in', 'document.status', self::getErrorStatus()]);

        $this->applyQueryFilters($params, $query);
        $query->select($this->_select);

        $dataProviderSort = ['dateCreate' => SORT_DESC];
        $dataProvider = $this->getDataProvider($query,$dataProviderSort);

        $dataProvider->sort->attributes['documentExtSwiftFin.valueDate'] = [
            'asc' => [
                'documentExtSwiftFin.valueDate' => SORT_ASC
            ],
            'desc' => [
                'documentExtSwiftFin.valueDate' => SORT_DESC
            ]
        ];

        return $dataProvider;
    }

    /**
     * Получение количества
     * документов SwiftFin для подписания
     */
    public static function getForSigningCount()
    {
        $myRequiredLevel = Yii::$app->user->identity->signatureNumber - 1;


        $query = Yii::$app->terminalAccess->query(
            Document::className(),
            [
                'typeGroup' => 'swiftfin',
                'status' => self::STATUS_FORSIGNING,
                'signaturesCount' => $myRequiredLevel
            ]
        );

        $query->andWhere('`signaturesRequired` > `signaturesCount`');

        return $query->count();
    }

    /**
     * Получение количества
     * документов Swiftfin для модификации
     */
    public static function getForCorrectionCount()
    {
        return Yii::$app->terminalAccess->query(
            Document::className(),
            [
                'typeGroup' => 'swiftfin',
                'status' => self::STATUS_CORRECTION,
                'direction' => self::DIRECTION_OUT
            ]
        )->count();
    }

    /**
     * Получение количества
     * документов Swiftfin для верификации
     */
    public static function getForVerificationCount()
    {
        $module = Yii::$app->getModule('swiftfin');

        return Yii::$app->terminalAccess->query(
            Document::className(),
            [
                'typeGroup' => 'swiftfin',
                'direction' => self::DIRECTION_OUT,
                'type' => $module->getUserVerifyDocType(),
                'status' => static::getUserVerifiableStatus()
            ]
        )->count();
    }

    /**
     * Получение количества
     * документов Swiftfin для авторизации
     */
    public static function getForAuthorizationCount()
    {
        $search = new SwiftFinSearch();

        $models = $search->searchForAuthorization([]);

        return count($models->allModels);
    }

    public static function getAuthorizableStatusLabels()
    {
        return SwiftFinDocumentExt::getStatusLabels();
    }

    public function getDocumentExtEdmForeignCurrencyOperation()
    {
        return $this->hasOne(ForeignCurrencyOperationDocumentExt::class, ['documentId' => 'id']);
    }
}
