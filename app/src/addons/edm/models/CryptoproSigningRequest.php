<?php


namespace addons\edm\models;


use yii\db\ActiveRecord;

/**
 * Class CryptoproSigningRequest
 * @package addons\edm\models
 * @property integer $documentId
 * @property string  $status
 */
class CryptoproSigningRequest extends ActiveRecord
{
    const STATUS_FOR_SIGNING   = 'forSigning';
    const STATUS_SIGNED        = 'signed';
    const STATUS_SIGNING_ERROR = 'signingError';

    public function rules()
    {
        return [
            ['documentId', 'integer'],
            ['status', 'string'],
            ['status', 'in', 'range' => [static::STATUS_FOR_SIGNING, static::STATUS_SIGNED, static::STATUS_SIGNING_ERROR]],
            [['documentId', 'status'], 'required'],
        ];
    }

    public static function tableName()
    {
        return 'edmCryptoproSigningRequest';
    }

    public static function findOneByDocument($documentId)
    {
        return static::find()->where(['documentId' => $documentId])->one();
    }

}
