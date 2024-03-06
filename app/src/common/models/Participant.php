<?php

namespace common\models;

use common\validators\ParticipantIdValidator;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "participant".
 *
 * @property integer $id
 * @property string $address
 * @property string $info
 */
class Participant extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'participant';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['address', 'required'],
            ['info', 'string'],
            ['address', 'string', 'max' => 16],
            ['address', 'unique'],
            ['address', ParticipantIdValidator::className()],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'address' => Yii::t('app', 'Address'),
            'info' => Yii::t('app', 'Info'),
        ];
    }
}
