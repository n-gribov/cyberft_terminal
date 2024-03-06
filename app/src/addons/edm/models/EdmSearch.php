<?php

namespace addons\edm\models;

use common\document\DocumentSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
/**
 * @deprecated
 */
class EdmSearch extends DocumentSearch
{
    private $_extModel;

    public function init()
    {
        parent::init();

        $this->_extModel = new EdmDocumentExt();
    }

    /**
     * Get document type labels list
     *
     * @return array
     */
    public static function getDocTypeLabels()
    {
        $typeLabels = Yii::$app->registry->getModuleTypes('edm');

        foreach($typeLabels as $key => $value) {
            $typeLabels[$key] = Yii::t('edm', $key);
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
     * Search all documents with "correction" status
     *
     * @param array $params Params list
     * @return ActiveDataProvider
     */
    public function searchForCorrection($params)
    {
        $query = $this->find()
            ->andFilterWhere(['typeGroup' => 'edm'])
            ->andWhere(['document.status' => self::STATUS_CORRECTION])
            ->andWhere(['direction' => self::DIRECTION_OUT]);

        $this->applyQueryFilters($params, $query);

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'sort'       => ['defaultOrder' => ['dateCreate' => SORT_DESC]],
            'pagination' => new Pagination([
                'pageSize' => $this->pageSize,
                ])
        ]);

        return $dataProvider;
    }

    public function applyExtFilters($query)
    {
        $query->andFilterWhere(['typeGroup' => 'edm']);
    }

}