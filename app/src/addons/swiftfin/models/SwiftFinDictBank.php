<?php
namespace addons\swiftfin\models;
use Yii;

/**
 * @property string swiftCode
 * @property string branchCode
 * @property string name
 * @property string address
 * @property string fullCode
 */
class SwiftFinDictBank extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'swiftfin_dictBank';
    }

    public function rules()
    {
        return parent::rules();
    }

    public static function findByCode($code)
    {
        $column = strlen($code) > 8 ? 'fullCode' : 'swiftCode';

        return static::findOne([$column => $code]);
    }

    public function attributeLabels()
    {
        return [
            'swiftCode' => Yii::t('doc/swiftfin', 'SWIFT code'),
            'branchCode' => Yii::t('doc/swiftfin', 'Branch code'),
            'fullCode' => Yii::t('doc/swiftfin', 'Full code'),
            'name' => Yii::t('doc/swiftfin', 'Name'),
            'address' => Yii::t('doc/swiftfin', 'Address')
        ];
    }

}