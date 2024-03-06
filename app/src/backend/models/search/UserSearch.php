<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;
use Yii;
use common\helpers\UserHelper;
use common\models\UserTerminal;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    public $name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id',  'status', 'created_at', 'updated_at'], 'integer'],
            [['email', 'name', 'auth_key', 'password_hash', 'password_reset_token'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function searchByTerminal($params, $terminalId)
    {
        $users = UserTerminal::getUsers($terminalId);

        $query = User::find();
        $query->where(['id' => $users]);

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
        if (!$query) {
            $query = User::find();
        }

        // Получить модель пользователя из активной сессии
        $userIdentity = Yii::$app->user->identity;

        if (!in_array($userIdentity->role, [User::ROLE_ADMIN, User::ROLE_LSO, User::ROLE_RSO])) {
            // Для дополнительного админа
            // все пользователи по доступным ему терминалам
            // Исключая главного администратора и офицеров безопасности

            $userList = UserHelper::getUsersAdditionalAdmin($userIdentity);

            // Запрос с учетом доступных пользователей
            $query = User::find()->where(['id' => $userList]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'role' => $this->role,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere([
                'or',
                ['like', 'lastName', $this->name],
                ['like', 'firstName', $this->name],
                ['like', 'middleName', $this->name]
            ])
		;

        return $dataProvider;
    }

}
