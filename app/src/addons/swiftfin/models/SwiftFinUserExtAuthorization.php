<?php

namespace addons\swiftfin\models;

use Yii;
use yii\db\ActiveRecord;


class SwiftFinUserExtAuthorization extends ActiveRecord
{
	/**
	 *
	 * @var int $userExtId Parent SwiftFinUserExt ID
	 * @var string $docType
	 * @var string $currency
	 * @var int $minSum
	 * @var int $maxSum
	 */
    private $_userExt;

    public static function tableName()
    {
        return 'swiftfin_userExtAuthorization';
    }

    public function rules()
    {
        return [
            [['userExtId', 'currency', 'docType'], 'required'],
            ['minSum', 'safe'],
            [['maxSum'], 'rangeValidator', 'skipOnEmpty' => false],
            [['minSum', 'maxSum'], 'default', 'value' => 0]
        ];
    }

    public function rangeValidator($attr, $param)
    {
        if ($attr == 'maxSum') {
            if (!empty($this->maxSum) && $this->maxSum < $this->minSum) {
                $this->addError($attr, Yii::t('doc', 'Must be empty or not less than "From sum"'));
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'userId' => Yii::t('app', 'User ID'),
            'currency' => Yii::t('doc', 'Currency'),
			'docType' =>  Yii::t('doc', 'Document type'),
			'minSum' => Yii::t('doc', 'From sum'),
			'maxSum' => Yii::t('doc', 'To sum'),
        ];
    }
}
