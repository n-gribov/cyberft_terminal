<?php

namespace common\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class UserTerminal extends ActiveRecord
{
    private static $_userCache = [];

    public function rules()
    {
        return [
            [['userId', 'terminalId'], 'required'],
            [['userId', 'terminalId'], 'unique', 'targetAttribute' => ['userId', 'terminalId']]
        ];
    }

    public function getTerminal()
    {
        return $this->hasOne(Terminal::className(), ['id' => 'terminalId']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    public static function getUserTerminalIds($userId, $like = null)
    {
        if (!$userId) {
            return [];
        }

        $cacheKey = $userId . $like;

        if (isset(static::$_userCache[$cacheKey])) {
            return static::$_userCache[$cacheKey];
        }

        $terminals = Terminal::find()->from(Terminal::tableName() . ' t')
            ->select(['t.id', 't.terminalId'])
            ->innerJoin(static::tableName() . ' ut', [
                'and',
                't.id = ut.terminalId',
                ['ut.userId' => $userId]
            ])
            ->andWhere(['!=', 't.status', Terminal::STATUS_INACTIVE])
            ->andFilterWhere(['like', 't.terminalId', $like . '%', false])
            ->orderBy(['t.terminalId' => SORT_ASC])
            ->asArray()
            ->all();

        $result = array_column($terminals, 'terminalId', 'id');
        static::$_userCache[$cacheKey] = $result;

        return $result;
    }

    /**
     * Возвращает массив порядковых id терминалов,
     * доступных пользователю
     */
    public static function getUserTerminalIndexes($userId)
    {
        $terminals = UserTerminal::find()
            ->where(['userId' => $userId])
            ->select('terminalId')
            ->asArray()
            ->all();

        $terminalsList = ArrayHelper::getColumn($terminals, 'terminalId');

        return $terminalsList;
    }

    public static function getUserTerminals($userId, $like = null)
    {
        if (!$userId) {
            return [];
        }

        $terminals = Terminal::find()->from(Terminal::tableName() . ' t')
            ->innerJoin(static::tableName() . ' ut', [
                'and',
                't.id = ut.terminalId',
                ['ut.userId' => $userId]
            ])
            ->andWhere(['!=', 't.status', Terminal::STATUS_INACTIVE])
            ->andFilterWhere(['like', 't.terminalId', $like . '%', false])
            ->orderBy(['t.terminalId' => SORT_ASC])
            ->all();

        $terminalsArray = [];
        foreach ($terminals as $terminal) {
            $terminalsArray[$terminal->terminalId] = $terminal;
        }

        return $terminalsArray;
    }

    public static function getUsers($terminalId)
    {
        $users = static::find()->with('user')
                 ->select('userId')
                 ->where(['terminalId' => $terminalId])
                 ->asArray()
                 ->all();

        $usersList = ArrayHelper::getColumn($users, 'userId');

        return $usersList;
    }

}