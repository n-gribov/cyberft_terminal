<?php

namespace common\settings;

use yii\db\ActiveRecord;

/**
 * Settings DB storage model
 *
 * @property integer  $id            Primary ID
 * @property string  $code           Unique settings class code
 * @property string  $data           Settings serialized data
 * @author fuzz
 */
class SettingsAR extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'terminalId'], 'unique', 'targetAttribute' => ['code', 'terminalId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
    }
}