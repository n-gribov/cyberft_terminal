<?php

namespace common\modules\participant\models;

use yii\data\ActiveDataProvider;

class BICDirParticipantSearch extends BICDirParticipant
{
	public $validBeforeRealFrom;
	public $validBeforeRealBefore;

    public function rules()
    {
        return [
            [['id'], 'integer'],
			[
                [
                    'participantBIC', 'providerBIC',
                    'validFrom', 'validBefore',
                    'type', 'status', 'blocked', 'lang',
                    'participantBIC',
                    'name', 'institutionName',
                    'status',
                    'type',
                    'website', 'phone'
                ],
                'safe'
			],
        ];
    }

    public function search($params)
    {
        $query = static::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

//		if ($this->validBeforeRealFrom) {
//			$dFrom = (new \DateTime($this->validBeforeRealFrom, new \DateTimeZone(Yii::$app->getTimeZone())))
//				->setTimezone(new \DateTimeZone('UTC'))
//				->format('Y-m-d H:i:s');
//
//			$query
//				->andFilterWhere(['>=', 'validBefore', $dFrom])
//				->andFilterWhere(['>=', 'useBefore', $dFrom])
//			;
//		}
//		if ($this->validBeforeRealBefore) {
//			$dBefore = (new \DateTime($this->validBeforeRealBefore, new \DateTimeZone(Yii::$app->getTimeZone())))
//				->setTimezone(new \DateTimeZone('UTC'))
//				->modify('+1 day')
//				->format('Y-m-d H:i:s');
//
//			$query
//				->andFilterWhere(['<=', 'validBefore', $dBefore])
//				->andFilterWhere(['<=', 'useBefore', $dBefore])
//			;
//		}

        $query
            ->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['like', 'participantBIC', $this->participantBIC])
            ->andFilterWhere(['like', 'providerBIC', $this->providerBIC])
            ->andFilterWhere(['=', 'status', $this->status])
            ->andFilterWhere(['=', 'blocked', $this->blocked])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'institutionName', $this->institutionName])
            ->andFilterWhere(['=', 'countryCode', $this->countryCode])
        ;

        return $dataProvider;
    }
}
