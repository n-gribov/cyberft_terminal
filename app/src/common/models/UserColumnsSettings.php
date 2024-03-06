<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @property integer $userId
 * @property string $listType
 * @property text $settings
 * @property array $settingsData Mapped JSON attribute stored in settings
 */
class UserColumnsSettings extends ActiveRecord
{
    private static $_type;
    private static $_userId;
    private static $_settings;

    public static function tableName()
    {
        return 'user_columns_settings';
    }

    function behaviors()
    {
        return [
            [
                'class'=>  \common\behaviors\JsonArrayBehavior::className(),
                'attributes'=>[
                    'settingsData' => 'settings'
                ]
            ],
        ];
    }

    public function rules()
    {
        return [
            [['userId', 'listType', 'settings'], 'required'],
        ];
    }

    private static function getSettings($type, $userId)
    {
        if (static::$_type != $type || static::$_userId != $userId) {
            static::$_settings = static::findOne(['userId' => $userId, 'listType' => $type]);
            static::$_type = $type;
            static::$_userId = $userId;
        }

        return static::$_settings;
    }

    /**
     * Получает список колонок для отображения из списка настроек колонок
     * @param $columns
     * @param $type
     * @param $userId
     * @return array
     */
    public static function getEnabledColumnsByType($columns, $type, $userId, $columnsDisabledByDefault = [])
    {
        // Список колонок, которые надо отображать согласно настройкам
        $settingsColumns = [];

        // Список колонок, которые надо отобразить в журнале
        $enabledColumns = [];

        // Получаем список всех колонок по пользователю и типу журнала
        $settings = static::getSettings($type, $userId);

        // Если найдены настройки полей
        if ($settings) {

            // Настройки колонок
            $settingsData = $settings->settingsData;

            // Получаем только включенные для отображения колонки
            $settingsColumns = array_keys($settingsData, 1);

            // Формируем список колонок для вывода согласно настройкам
            foreach($settingsColumns as $setting) {
                if (isset($columns[$setting])) {
                    $enabledColumns[] = $columns[$setting];
                }
            }

            // Проверяем наличие новых добавленных в журнал колонок
            $diffColumns = array_diff_key($columns, $settingsData);

            // Если появились новые колонки,
            // выводим их в журнал и записываем в настройки как включенные
            if ($diffColumns) {
                foreach ($diffColumns as $name => $newColumn) {
                    // Добавляем новую колонку в настройки колонок
                    $settingsData[$name] = 1;

                    // Выводим колонку в текущее представление журнала
                    $enabledColumns[] = $newColumn;
                }

                // Записываем настройки колонок
                $settings->settingsData = $settingsData;
                $settings->save();
            }
        }

        // Если колонки не заполнены согласно настройкам, выводим все колонки
        if (empty($enabledColumns)) {
            $enabledColumns = array_filter(
                $columns,
                function ($columnId) use ($columnsDisabledByDefault) {
                    return !in_array($columnId, $columnsDisabledByDefault);
                },
                ARRAY_FILTER_USE_KEY
            );
        }

        return $enabledColumns;
    }

    /**
     * Получение списка колонок для формы с настройкой
     * @param $columns
     * @param $type
     * @param $userId
     * @param array $columnsDisabledByDefault
     * @return array
     */
    public static function getColumnsForForm($columns, $type, $userId, $columnsDisabledByDefault = [])
    {
        // Получаем список всех колонок по пользователю и типу журнала
        $settings = static::getSettings($type, $userId);

        if ($settings) {
            // Массив с текущими настройками из базы
            $settingsArr = $settings->settingsData;

            // Если в настройках есть поля, которые уже удалены из представления
            if (count($columns) != count($settingsArr)) {

                // Ищем колонки, которые уже не актуальны
                foreach($settingsArr as $setting => $data) {

                    // Поиск колонок из настроек в составе
                    // текущих колонок представления
                    if (in_array($setting, $columns)) {
                        continue;
                    }

                    // Удаляем отсутствующую колонку из настроек
                    unset($settingsArr[$setting]);
                }

                // Записываем новые настройки полей
                $settings->settingsData = $settingsArr;
                $settings->save();
            }

            // Если настройки найдены, возвращаем список всех колонок и их состояния включения
            return $settings->settingsData;
        } else {
            // Иначе считаем все колонки включенными
            $columnsSettings = [];

            foreach($columns as $value) {
                $columnsSettings[$value] = in_array($value, $columnsDisabledByDefault) ? 0 : 1;
            }

            return $columnsSettings;
        }
    }

}
