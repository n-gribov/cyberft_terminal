<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CryptoproKey;
use common\models\CryptoproKeyTerminal;
use yii\helpers\ArrayHelper;
use Yii;

class CryptoproKeySearch extends CryptoproKey
{
    public $email;
    public $terminal;
    public $beneficiary;
    public $organization;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [
                [
                    'keyId', 'ownerName', 'certData', 'serialNumber',
                    'email', 'status', 'active', 'expireDate', 'terminal', 'beneficiary', 'organization'
                ], 'safe'
            ],
        ];
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'organization' => Yii::t('app/fileact', 'Terminals')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Формирование списка ключей указанного пользователя
     */
    public function searchByUser($user, $params)
    {
        $query = static::find();
        $query->where(['userId' => $user->id]);

        return $this->search($params, $query);
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $query = null)
    {
        if ($query == null) {
            $query = static::find();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // Применение запросов по связанным таблицам
        $this->applyExtFilters($query);

        // Имя таблицы с сертификатами КриптоПро
        $cryptoKeysTableName = static::tableName();

        $query->andFilterWhere(['like', 'keyId', $this->keyId])
            ->andFilterWhere(['like', 'ownerName', $this->ownerName])
            ->andFilterWhere(['like', 'serialNumber', $this->serialNumber])
            ->andFilterWhere(['=', "{$cryptoKeysTableName}.status", $this->status])
            ->andFilterWhere(['=', "{$cryptoKeysTableName}.active", $this->active])
            ->andFilterWhere(['like', 'certData', $this->certData])
            ->andFilterWhere(['<=', 'expireDate', $this->expireDate]);

        // Получение списка ключей,
        // у которых наименования указанных в поиске
        // терминалов совпадают с текстом запроса в фильтра
        if ($this->terminal) {

            // Получаем id-ключей, у которых есть указанный терминал
            $keys = CryptoproKeyTerminal::find()->where(['terminalId' => $this->terminal])->asArray()->all();

            // Если по данному id не найдено терминалов, значит список должен быть пустым

            if (empty($keys)) {
                $query->where('0=1');
            } else {
                // Получаем массив id ключей для подстановки в условия
                $keysArray = ArrayHelper::getColumn($keys, 'keyId');

                // Добавляем соответствующее условие
                $query->andFilterWhere(['in', "{$cryptoKeysTableName}.id", $keysArray]);
            }
        }

        // Отбор по организациям, которые привязаны к ключам
        if ($this->organization) {

            // Получаем id-ключей, у которых есть указанный терминал
            $keys = CryptoproKeyTerminal::find()->where(['terminalId' => $this->organization])->asArray()->all();

            // Если по данному id не найдено терминалов, значит список должен быть пустым

            if (empty($keys)) {
                $query->where('0=1');
            } else {
                // Получаем массив id ключей для подстановки в условия
                $keysArray = ArrayHelper::getColumn($keys, 'keyId');

                // Добавляем соответствующее условие
                $query->andFilterWhere(['in', "{$cryptoKeysTableName}.id", $keysArray]);
            }
        }

        return $dataProvider;
    }

    public function searchUserKeys($userId)
    {
        $query = static::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->where(['userId' => $userId]);

        return $dataProvider;
    }

    public function applyExtFilters($query)
    {
        // Запрос по связанной таблице пользователей
        $userTableName = User::tableName();
        $query->joinWith([$userTableName]);

        $query->andFilterWhere(['like', "{$userTableName}.email", $this->email]);
    }

    /**
     * Получение списка доступных
     * пользователю организаций
     */
    public static function getAvailableOrganizations()
    {
        $user = Yii::$app->user;

        $userTerminals = UserTerminal::find()
                         ->with('terminal')
                         ->where(['userId' => $user->id])
                         ->all();

        $organizations = [];

        foreach($userTerminals as $terminal) {
            $organizations[$terminal->terminalId] = $terminal->terminal->title;
        }

        return $organizations;
    }
}
