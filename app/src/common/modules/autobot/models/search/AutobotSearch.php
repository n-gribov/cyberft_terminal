<?php

namespace common\modules\autobot\models\search;

use common\models\User;
use common\models\UserTerminal;
use common\modules\autobot\models\Autobot;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * AutobotSearch represents the model behind the search form about `common\modules\autobot\models\Autobot`.
 */
class AutobotSearch extends Autobot
{
    public $ownerFullName;
    public $terminalId;

    const EXPIRATION_ACTIVE = 'active';
    const EXPIRATION_EXPIRED = 'expired';
    const EXPIRATION_EXPIRES = 'expires';

    public function rules()
    {
        return [
            [['id', 'primary'], 'integer'],
			[['updatedAt'], 'date'],
            [
                ['terminalId', 'name', 'organizationName', 'ownerFullName',
                    'privateKey', 'certificate', 'fingerprint', 'status', 'expirationDate'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
            $query = static::find()->joinWith('controller.terminal');
        }

        // Получить модель пользователя из активной сессии
        $adminIdentity = Yii::$app->user->identity;

        // Для доп. администратора
        // список ключей согласно доступным терминалам
        if ($adminIdentity->role != User::ROLE_ADMIN) {
            $terminals = UserTerminal::getUserTerminalIds($adminIdentity->id);

            if ($terminals) {
                // Если не доступно ни одного терминала, устанавливаем невыполнимое условие
                $query->andWhere(['terminal.terminalId' => array_values($terminals)]);
            } else {
                $query->andWhere('0=1');
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'primary' => $this->primary,
            'status' => $this->status
        ]);

        $query
            ->andFilterWhere(['like', 'controller.terminal.terminalId', $this->terminalId])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'updatedAt', $this->updatedAt])
            ->andFilterWhere(['like', 'organizationName', $this->organizationName])
            ->andFilterWhere(['like', 'ownerSurname', $this->ownerFullName])
            ->orFilterWhere(['like', 'ownerName', $this->ownerFullName])
            ->andFilterWhere(['like', 'fingerprint', $this->fingerprint]);


        if ($this->expirationDate) {
            if ($this->expirationDate == self::EXPIRATION_EXPIRED) {
                $query->andFilterWhere(['<', 'expirationDate' , date('Y-m-d H:i:s')]);
            } else if ($this->expirationDate == self::EXPIRATION_EXPIRES) {
                $dateFrom = date('Y-m-d H:i:s');
                $dateBefore = date('Y-m-d H:i:s', strtotime('+30 days'));
                $query->andFilterWhere(['between', 'expirationDate', $dateFrom, $dateBefore]);
            } else if ($this->expirationDate == self::EXPIRATION_ACTIVE) {
                $query->andFilterWhere(['>', 'expirationDate', date('Y-m-d H:i:s')]);
            }
        }

        $query->orderBy(new Expression("FIELD(status, '" . Autobot::STATUS_USED_FOR_SIGNING . "') DESC"));
        $query->addOrderBy(['expirationDate' => SORT_DESC]);

        return $dataProvider;
    }

    /**
     * Поиск автоботов по $terminalId
     * @param string $terminalId
     * @return ActiveDataProvider
     */
    public function findByTerminalId($terminalId)
    {
        $query = static::find()
            ->joinWith('controller.terminal')
            ->where(['terminal.terminalId' => $terminalId]);

        return new ActiveDataProvider(['query' => $query]);
    }

    public function findUsedForSigning($terminalAddress)
    {
        return self::find()
            ->joinWith('controller.terminal')
            ->where([
                'terminal.terminalId' => $terminalAddress,
                'autobot.primary' => Autobot::AUTOBOT_PRIMARY,
                'autobot.status' => Autobot::STATUS_USED_FOR_SIGNING
            ])
            ->one();
    }

    public function findUsedForDecryption($terminalAddress)
    {
        return self::find()
            ->joinWith('controller.terminal')
            ->where([
                'terminal.terminalId' => $terminalAddress,
                'autobot.primary' => Autobot::AUTOBOT_PRIMARY,
                'autobot.status' => [Autobot::STATUS_USED_FOR_SIGNING, Autobot::STATUS_ACTIVE, Autobot::STATUS_WAITING_FOR_ACTIVATION]
            ])
            ->one();
    }

}
