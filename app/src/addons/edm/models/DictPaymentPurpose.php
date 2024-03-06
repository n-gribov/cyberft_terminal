<?php

namespace addons\edm\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $value
 */
class DictPaymentPurpose extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'edmDictPaymentPurpose';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value', 'terminalId'], 'required'],
            [['value'], 'string', 'max' => 180]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'    => Yii::t('app', 'ID'),
            'value' => Yii::t('app', 'Value'),
        ];
    }
}
