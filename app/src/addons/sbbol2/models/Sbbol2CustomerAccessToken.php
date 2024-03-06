<?php
namespace addons\sbbol2\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "sbbol2_customerAccessToken".
 *
 * @property string $id
 * @property string $customerId
 * @property boolean $isActive
 * @property string $statusLabel
 * @property string $accessToken
 * @property string $refreshToken
 * @property string $accessTokenExpiryTime
 */
class Sbbol2CustomerAccessToken extends ActiveRecord
{
    public static function tableName()
    {
        return 'sbbol2_customerAccessToken';
    }
    
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'createDate',
                'updatedAtAttribute' => 'updateDate',
                'value' => new Expression('NOW()'),
            ],
        ];
    }


    public function rules()
    {
        return [
            [['customerId'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'statusLabel' => Yii::t('app/sbbol2', 'Exchange status'),
        ];
    }
    
    public function getStatusLabel()
    {
        return $this->isActive
            ? Yii::t('app/sbbol2', 'Active')
            : Yii::t('app/sbbol2', 'Authorization required');
    }

}
