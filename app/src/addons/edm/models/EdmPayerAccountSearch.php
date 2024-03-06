<?php

namespace addons\edm\models;

use common\models\UserTerminal;
use common\models\Terminal;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Модель для счетов плательщиков
 * @property integer $id
 * @property string $name
 * @property string $organizationId
 * @property string $number
 * @property string $currencyId
 * @property string $bankId
 * @property string $requireSignQty
 */
class EdmPayerAccountSearch extends EdmPayerAccount
{
    public $organizationName;
    public $bankName;
    public $currencyName;

    public function rules()
    {
        return [
            [['name', 'organizationName', 'number', 'bankName', 'currencyName'], 'safe']
        ];
    }

    public function search($params)
    {     
        
        $queryOrganizations = Yii::$app->terminalAccess->query(DictOrganization::className());
        $queryOrganizations->select('id')->asArray();
        $organizations = $queryOrganizations->all();
                
        $query = EdmPayerAccount::find();
        
        // Получение счетов по доступным организациям (если уже не нужно искать по одной - CYB-4440)
        if (!isset($params['organizationId'])) {
            $query->where(['organizationId' => ArrayHelper::getColumn($organizations, 'id')]); 
        } else {
            // ????? вообще так неправильно, я думаю
            $query->where(['organizationId' => $params['organizationId']]);
        }
        
        // C учетом доступных текущему пользователю счетов
        $query = Yii::$app->edmAccountAccess->query($query, EdmPayerAccount::tableName() . '.id');
        
        $dataProvider = new ActiveDataProvider([            
            'query' => $query,
        ]);           

        $this->load($params);
        
        $this->applyExtFilters($query);

        if (!$this->validate()) {
            $query->where('0=1');

            return $dataProvider;
        }
        
        return $dataProvider;
    }
    
    public function searchBikInUserTerminals($bankBik)
    { 
        
        /*
         * CYB-4340 Определяем, есть ли у пользователя доступ к терминалу, где создана организация,
         * имеющая счет в банке "Платина" (БИК 044525931)
         */
        $tableName = DictOrganization::tableName();
        // Получить модель пользователя из активной сессии
        $user = Yii::$app->user->identity;
        
        $where = ['and', ['not', [$tableName . '.terminalId' => null]]];
        
        if ($user->role == \common\models\User::ROLE_ADMIN)
        {
            if ($this->checkBikInTerminals($where, $bankBik))
            {
                return true;
            }
        } else {
            $userTerminals = UserTerminal::find()->where(['userId' => $user->id])->select('terminalId')->asArray()->all();

            foreach($userTerminals as $terminal)
            {
                $where[] = ['and', [$tableName . '.terminalId' => $terminal['terminalId']]];
                if ($this->checkBikInTerminals($where, $bankBik))
                {
                    return true;
                }

            }            
        }              
        return false;        
        
    }   
          
    function checkBikInTerminals($where, $bankBik)
    {                    
        $queryOrganizations = DictOrganization::find()->where($where);
        $organizations = $queryOrganizations->all();

        // Получение счетов по доступным организациям
        $query = EdmPayerAccount::find();
        $query->where(['organizationId' => ArrayHelper::getColumn($organizations, 'id')]);

        $dataProvider = new ActiveDataProvider([            
            'query' => $query,
        ]);  

        $this->applyExtFilters($query, $bankBik);

        if ($dataProvider->totalCount != 0)
        {
            return true;
        }
        
        return false;
    }
    
    /**
     * Фильтры со связями по другим таблицам
     * @param $query
     */
    public function applyExtFilters($query, $bankBik = null)
    {                
        // Запрос по связанной таблице организаций
        $organizationTableName = DictOrganization::tableName();
        $query->joinWith([$organizationTableName]);

        // Запрос по связанной таблице банков
        $bankTableName = 'bank';
        $query->joinWith("bank as $bankTableName");

        // Запрос по связанной таблице валют
        $currencyTableName = DictCurrency::tableName();
        $query->joinWith([$currencyTableName]);

        $query->andFilterWhere(['like', "{$bankTableName}.name", $this->bankName])
              ->andFilterWhere(['=', "{$currencyTableName}.id", $this->currencyName]);

        $query->andFilterWhere([
            'or',
            ['like', "{$organizationTableName}.name", $this->organizationName],
            ['like', 'payerName', $this->organizationName]
        ]);

        $query->andFilterWhere(['like', static::tableName() . '.name', $this->name])
              ->andFilterWhere(['like', 'number', $this->number]);
              
        if ($bankBik) {
            $query->andFilterWhere(['=', 'bankBik', $bankBik]);
        }
    }

}