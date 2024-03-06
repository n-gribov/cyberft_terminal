<?php

namespace addons\edm\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Модель для справочника валют
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $description
 */
class DictCurrency extends ActiveRecord
{
    public static function tableName()
    {
        return 'edmDictCurrencies';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'string', 'max' => 3],
            ['code', 'integer'],
            [['code', 'name'], 'required'],
            [['code', 'name'], 'unique'],
            ['description', 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name'    => Yii::t('edm', 'Currency name'),
            'code'       => Yii::t('edm', 'Currency code'),
            'description'  => Yii::t('edm', 'Currency description'),
        ];
    }

    /**
     * Получение списка валют
     * @return mixed
     */
    public static function getValues()
    {
        return self::find()->all();
    }

    /**
     * Получение списка валют в формате - имя + описание
     * @return array
     */
    public static function getValuesFullFormat()
    {
        $list = [];
        $values = self::getValues();

        foreach($values as $value) {
            $list[$value->id] = $value->name . ' - ' . $value->description;
        }

        return $list;
    }

    /**
     * Получение наименования валюты по id
     */
    public static function getNameById($id)
    {
        $model = static::findOne($id);

        return empty($model) ? null : $model->name;
    }

    public static function getNameByCode($code, $attachCode = false)
    {
        $model = static::findOne(['code' => $code]);

        if (!$model) {
            return $attachCode ? $code : null;
        }

        return $attachCode
                ? $model->description . ' (' . $code . ')'
                : $model->description;

    }

    public static function getAlphaCodeByNumericCode($numericCode)
    {
        $model = static::findOne(['code' => $numericCode]);
        return $model ? $model->name : null;
    }

    /**
     * Получение списка валют
     */
    public static function getList()
    {
        $currenciesList = ArrayHelper::map(static::find()->all(), 'name', 'name');

        return $currenciesList;
    }

}
