<?php

namespace common\components;

use common\models\User;
use Yii;
use yii\base\Component;
use yii\web\NotFoundHttpException;
use common\models\UserTerminal;

/**
 * Компонент для авторизации доступа к различным объектам
 * на основании выбранного терминала
 */
class TerminalAccess extends Component
{
    /**
     * Метод ищет объект в БД, базируясь на классе модели и условии поиска
     * @param type $className класс модели
     * @param type $condition условие поиска
     * @return type
     * @throws NotFoundHttpException
     */
    public function findModel($className, $condition)
    {
        // Создать запрос на основе модели и условия
        $query = $this->query($className, $condition);
        // Получить модель из запроса
        $model = $query->one();
        if ($model === null) {
            // если модель не найдена или нет доступа
            // Получить модель пользователя из текущей сессии
            $user = Yii::$app->user->identity;
            // Выбросить исключение с ошибкой поиска модели
            throw new NotFoundHttpException(
                "role: {$user->roleLabel} terminalId: {$user->terminalId} $className"
            );
        }
        
        // Вернуть модель
        return $model;
    }

    /**
     * Метод создаёт SQL-запрос для поиска в БД по классу модели и условию
     * @param type $className название класса модели
     * @param type $condition условие поиска
     * @return type
     */
    public function query($className, $condition = null)
    {
        return $className::find()->where($this->getWhere($className, $condition));
    }

    /**
     * Метод формирует расширенный фмльтр поиска с учётом доступных пользователю терминалов
     * @param type $className название класса модели
     * @param type $condition условие поиска
     * @return type
     */
    private function getWhere($className, $condition = null)
    {
        // Исключить из поиска объекты, у которых terminalId пустой
        $where = ['and', ['not', [$className::tableName() . '.terminalId' => null]]];

        // Проверка доступа осуществляется, если есть активная сессия пользователя
        if (!empty(Yii::$app->user)) {
            // Получить модель пользователя из активной сессии
            $user = Yii::$app->user->identity;
            if ($user && $user->role != User::ROLE_ADMIN) {
                // Если пользователь не основной админ, получаем список доступных ему терминалов

                // Привязанный к пользователю терминал
                $terminalId = $user->terminalId;
                
                // Если не указан терминал и пользователю запрещён выбор терминала,
                // получить список всех привязанных терминалов
                if (empty($terminalId) && $user->disableTerminalSelect) {
                    $terminalId = array_keys(UserTerminal::getUserTerminalIds($user->id));
                }

                if ($terminalId) {
                    // Если терминал/список не пустые, то добавить фильтрацию по терминалам в фильтр
                    $where[] = ['and', [$className::tableName() . '.terminalId' => $terminalId]];
                } else {
                    // Иначе фильтр блокируется так, чтобы ничего не возвращать
                    $where[] = ['and', '0=1'];
                }
            }
        }

        if (!is_null($condition)) {
            // Если условие поиска не пусто
            if (!is_array($condition)) {
                // Если условие это не массив, то это просто id объекта
                $condition = ['id' => $condition];
            }

            // Добавить условие в фильтр
            $where[] = $condition;
        }

        // Вернуть фильтр
        return $where;
    }
}
