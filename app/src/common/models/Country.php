<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "country".
 *
 * @property string $name
 * @property string $nameInEnglish
 * @property string $alfa2Code
 * @property string $alfa3Code
 * @property string $numericCode
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['numericCode'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'nameInEnglish', 'alfa2Code', 'alfa3Code', 'numericCode'], 'required'],
            [['name', 'nameInEnglish'], 'string', 'max' => 255],
            [['alfa2Code'], 'string', 'min' => 2, 'max' => 2],
            [['alfa3Code', 'numericCode'], 'string', 'min' => 3, 'max' => 3],
            [['alfa2Code'], 'unique'],
            [['alfa3Code'], 'unique'],
            [['numericCode'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'nameInEnglish' => 'Name in English',
            'alfa2Code' => 'Alfa2 code',
            'alfa3Code' => 'Alfa3 code',
            'numericCode' => 'Numeric code',
        ];
    }

    /**
     * @param $anyCode
     * @return static|null
     */
    public static function findOneByCode($anyCode)
    {
        if (preg_match('/^\d{3}$/', $anyCode)) {
            $searchAttribute = 'numericCode';
        } elseif (preg_match('/^[A-Z]{2}$/', $anyCode)) {
            $searchAttribute = 'alfa2Code';
        } elseif (preg_match('/^[A-Z]{3}$/', $anyCode)) {
            $searchAttribute = 'alfa3Code';
        } else {
            Yii::warning("Cannot detect country code type for $anyCode");
            return null;
        }

        $country = static::findOne([$searchAttribute => $anyCode]);

        return $country ?: null;
    }

    public static function convertCodeToAlfa2($anyCode)
    {
        $county = static::findOneByCode($anyCode);
        return $county ? $county->alfa2Code : null;
    }

    public static function convertCodeToAlfa3($anyCode)
    {
        $county = static::findOneByCode($anyCode);
        return $county ? $county->alfa3Code : null;
    }

    public static function convertCodeToNumeric($anyCode)
    {
        $county = static::findOneByCode($anyCode);
        return $county ? $county->numericCode : null;
    }

}
