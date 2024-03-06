<?php
namespace common\modules\participant\helpers;

use common\helpers\Address;
use common\modules\participant\models\BICDirParticipant;
use Yii;
use yii\helpers\ArrayHelper;

class ParticipantHelper
{
    private static function getParticipantListForDocumentSearch($dataProvider, $attribute, $q)
    {
        $isAdmin = Yii::$app->user->can('admin') || Yii::$app->user->can('additionalAdmin');

        $terminalIds = $dataProvider->query->select([$attribute])->distinct()->all();
        $terminalIdsArray = ArrayHelper::getColumn($terminalIds, $attribute);
        $terminalIdsArrayMap = [];
        foreach ($terminalIdsArray as $terminalId) {
            $terminalIdsArrayMap[Address::truncateAddress($terminalId)] = $terminalId;
        }

        $query = BICDirParticipant::find()
                ->select('participantBIC, name')
                ->andWhere(['in', 'participantBIC', array_keys($terminalIdsArrayMap)]);

        if (isset($q)) {
            if ($isAdmin) {
                $query->andWhere(['like', 'participantBIC', $q]);
            } else {
                $query->andWhere(['like', 'name', $q]);
            }
        }

        $query->limit(10);

        $participants = $query->asArray()->all();

        $participantsFixed = [];
        foreach ($participants as $participant) {
            $participantsFixed[] = [
                'id' => $terminalIdsArrayMap[$participant['participantBIC']],
                'name' => $isAdmin ? $terminalIdsArrayMap[$participant['participantBIC']] : $participant['name']
            ];
        }

        return $participantsFixed;
    }

    public static function getSenderListForDocumentSearch($dataProvider, $q = null)
    {
        return static::getParticipantListForDocumentSearch($dataProvider, 'sender', $q);
    }

    public static function getReceiverListForDocumentSearch($dataProvider, $q = null)
    {
        return static::getParticipantListForDocumentSearch($dataProvider, 'receiver', $q);
    }
}
