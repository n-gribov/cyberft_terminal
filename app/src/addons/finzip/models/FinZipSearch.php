<?php

namespace addons\finzip\models;

use common\document\Document;
use common\document\DocumentSearch;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * @property integer  $fileCount  Count of files in ZIP
 * @property string   $subject    FinZip document subject
 * @property string   $descr      FinZip document descriptiom
 */
class FinZipSearch extends DocumentSearch
{
    /**
     * @var FinZipDocumentExt $_extFinZipModel FinZip document ext class
     */
    private $_extFinZipModel;

    public $fileCount;
    public $subject;
    public $descr;
    public $businessStatus;

    public function init()
    {
        parent::init();

        $this->_extFinZipModel = new FinZipDocumentExt();
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [array_values($this->_extFinZipModel->attributes()), 'safe'],
                [['businessStatus'], 'safe']
            ]
        );
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), $this->_extFinZipModel->attributeLabels());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabelShortcuts()
    {
        return ArrayHelper::merge(parent::attributeLabelShortcuts(),
            $this->_extFinZipModel->attributeLabelShortcuts());
    }

    /**
     * Get FinZip document extension
     *
     * @return ActiveQuery
     */
    public function getDocumentExtFinZip()
    {
        return $this->hasOne(get_class($this->_extFinZipModel), ['documentId' => 'id']);
    }

    public function applyExtFilters($query)
    {
        $extTableName = $this->_extFinZipModel->tableName();

        $query->joinWith([$extTableName]);                

        $query
            ->andFilterWhere(['typeGroup' => 'finzip'])
            ->andFilterWhere(["{$extTableName}.fileCount" => $this->fileCount])
            ->andFilterWhere(['like', "{$extTableName}.subject", $this->subject])
            ->andFilterWhere(['like', "{$extTableName}.descr", $this->descr])
            ->andFilterWhere(["{$extTableName}.businessStatus" => $this->businessStatus]);
    }
    
    public function search($params)
    {
        $dataProvider = parent::search($params);
        
        return $dataProvider;
    }
    
    public function searchForSigning($params) 
    {
        $dataProvider = parent::searchForSigning($params);
        /*
        
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
        */        
        
        $dataProvider->sort->attributes['businessStatus'] = [
            'asc' => [
                'businessStatus' => SORT_ASC
            ],
            'desc' => [
                'businessStatus' => SORT_DESC
            ]
        ];  
        
        
        $dataProvider->sort->attributes['dateCreate'] = [
            'asc' => [
                'dateCreate' => SORT_ASC
            ],
            'desc' => [
                'dateCreate' => SORT_DESC
            ]
        ];        
        return $dataProvider;
    }
    
    /**
     * Получение количества
     * документов Finzip для подписания
     */
    public static function getForSigningCount()
    {
        return Yii::$app->terminalAccess->query(
            Document::className(),
            [
                'typeGroup' => 'finzip',
                'status' => self::STATUS_FORSIGNING
            ]
        )->count();
    }

    /**
     * Получение количества новых документов
     */
    public static function getUnreadCount()
    {
        return Yii::$app->terminalAccess->query(
            static::className(),
            [
                'direction' => static::DIRECTION_IN,
                'typeGroup' => 'finzip',
                'viewed' => 0
            ]
        )->count();
    }
}
