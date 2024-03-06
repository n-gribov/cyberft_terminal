<?php

namespace common\modules\certManager\models;

use common\models\UserTerminal;
use common\models\User;
use DateTime;
use Yii;
use yii\data\ActiveDataProvider;

class CertSearch extends Cert
{
    public $validBeforeRealFrom;
    public $validBeforeRealBefore;
    public $participantName;
    public $participantBIC;

    public function rules()
    {
        return [[
            ['useBefore', 'fingerprint', 'participantUnitCode', 'fullName',
            'participantName', 'participantBIC', 'role', 'status'],
            'safe'
        ]];
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Cert::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        // Получить модель пользователя из активной сессии
        $user = Yii::$app->user->identity;

        if ($user->role == User::ROLE_USER) {
            $allowedTerminalIds = UserTerminal::getUserTerminalIds(Yii::$app->user->id);
            $query->andWhere(['in', 
                'CONCAT(participantCode, countryCode, sevenSymbol, delimiter, terminalCode, participantUnitCode)', 
                $allowedTerminalIds]);
        }
        
        if (!($this->load($params) && $this->validate())) {
            $query->andFilterWhere(['=', 'role', $this->role]);
            return $dataProvider;
        }

        /**
         * Обработаем фильтр по датам
         */
        if ($this->useBefore) {
            $dateFilter = DateTime::createFromFormat('d.m.Y', $this->useBefore);
            $dateFilter = $dateFilter->format('Y-m-d');
            $query->andWhere(['<=', 'useBefore', $dateFilter . ' 23:59:59']);
        }

        $query
            ->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['=', 'fingerprint', $this->fingerprint])
            ->andFilterWhere(['=', 'role', $this->role])
            ->andFilterWhere(['=', 'status', $this->status])
            ->andFilterWhere(['like', "concat_ws(' ', lastName, firstName, middleName)", $this->fullName]);

        // Если отбор по наименованию организации
        if ($this->participantName || $this->participantBIC) {
            $participant = $this->participantName ?: $this->participantBIC;
            self::queryConditionByTerminalId($query, $participant);
        }

        return $dataProvider;
    }

    public static function searchActiveControllerCertsByTerminalId($terminalId, $excludeFingerprint = null)
    {
        $query = static::find()->where(['status' => Cert::STATUS_C10, 'role' => Cert::ROLE_SIGNER_BOT]);
        $query->andFilterWhere(['!=', 'fingerprint', $excludeFingerprint]);

        self::queryConditionByTerminalId($query, $terminalId);

        return $query->orderBy(['id' => SORT_ASC])->all();
    }

    protected static function queryConditionByTerminalId($query, $address)
    {
        $terminalId = static::addressToTerminalId($address);

        return $query->andWhere($terminalId->toArray(true));
    }

}
