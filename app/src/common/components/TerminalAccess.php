<?php

namespace common\components;

use common\models\User;
use Yii;
use yii\base\Component;
use yii\web\NotFoundHttpException;
use common\models\UserTerminal;

class TerminalAccess extends Component
{
	public function findModel($className, $condition)
    {
        $where = $this->getWhere($className, $condition);
        $query = $className::find()->where($where);
		if (($model = $query->one()) !== null) {
			return $model;
		} else {
            /** @var \common\db\ActiveQuery $query */
            // $sql = $query->createCommand()->getRawSql();
            $user = Yii::$app->user->identity;

			throw new NotFoundHttpException('role: ' . $user->roleLabel
                    . ' terminalId: ' . $user->terminalId
                    . ' ' . $className . ': ' . var_export($where, true));
		}
    }

    public function query($className, $condition = null)
    {
        return $className::find()->where($this->getWhere($className, $condition));
    }

    private function getWhere($className, $condition = null)
    {
        // По умолчанию исключаем объекты, у которых terminalId пустой

        $where = ['and', ['not', [$className::tableName() . '.terminalId' => null]]];

        if (
            !empty(Yii::$app->user)
            && !empty(Yii::$app->user->identity)
            && Yii::$app->user->identity->role != User::ROLE_ADMIN
        ) {
            /**
             * Если пользователь не основной админ, получаем список доступных ему терминалов
             * если у пользователя указан terminalId (CYB-3494), то выбираем только по нему,
             * иначе (дефолтное поведение) по всем привязанным терминалам
             *
             * Также см. DocumentSearch::applyQueryFilters()
             */

            $user = Yii::$app->user->identity;

            $terminalId = $user->terminalId;

            if (empty($terminalId) && $user->disableTerminalSelect) {
                $terminalId = array_keys(UserTerminal::getUserTerminalIds(Yii::$app->user->identity->id));
            }

            if ($terminalId) {
                $where[] = ['and', [$className::tableName() . '.terminalId' => $terminalId]];
            } else {
                $where[] = ['and', '0=1'];
            }
        }

        if (!is_null($condition)) {
            if (!is_array($condition)) {
                $condition = ['id' => $condition];
            }

            $where[] = $condition;
        }

        return $where;
    }
}