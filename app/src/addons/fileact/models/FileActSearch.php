<?php

namespace addons\fileact\models;

use common\document\Document;
use common\document\DocumentSearch;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class FileActSearch extends DocumentSearch
{
    /**
     * @var FileActDocumentExt $_extFileActModel FileAct document ext class
     */
    private $_extFileActModel;

    public $pduStoredFileId;
    public $binStoredFileId;
    public $zipStoredFileId;
    public $senderReference;
    public $binFileName;

    public function init()
    {
        parent::init();

        $this->_extFileActModel = new FileActDocumentExt();
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
            [
                [array_values($this->_extFileActModel->attributes()), 'safe'],
            ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::attributeLabels(), $this->_extFileActModel->attributeLabels()
        );
    }

    /**
     * Get FileAct document extension
     *
     * @return ActiveQuery
     */
    public function getDocumentExtFileAct()
    {
        return $this->hasOne(get_class($this->_extFileActModel), ['documentId' => 'id']);
    }

    public function applyExtFilters($query)
    {
        $this->_select[] = 'ext.senderReference as senderReference';
        $this->_select[] = 'ext.binFileName as binFileName';

        $query->innerJoin(
            $this->_extFileActModel->tableName() . ' ext',
            'ext.documentId = ' . static::tableName() . '.id'
        );

        $query->andWhere(['typeGroup' => 'fileact']);

        $query->andFilterWhere(['like', 'ext.senderReference', $this->senderReference]);
        $query->andFilterWhere(['like', 'ext.binFileName', $this->binFileName]);
    }

    /**
     * Получение количества
     * документов Fileact для подписания
     */
    public static function getForSigningCount()
    {
        return Yii::$app->terminalAccess->query(
            Document::className(),
            [
                'typeGroup' => 'fileact',
                'status' => self::STATUS_FORSIGNING
            ]
        )->count();
    }
}
