<?php

namespace addons\ISO20022\models\form;

use Yii;
use common\base\Model;

class TypeCodeForm extends Model {

	public $code;
	public $ru;
	public $en;

	public function rules()
	{
		return [
			[['code', 'ru'], 'required'],
			['code', 'string', 'length' => [4]],
			['code', 'filter', 'filter' => 'strtoupper'],
            ['en', 'safe']
		];
	}

	public function attributeLabels()
    {
        return [
            'code'       => Yii::t('app/iso20022', 'Code'),
            'ru'    => Yii::t('app/iso20022', 'Russian title'),
            'en' => Yii::t('app/iso20022', 'English title'),
        ];
    }

}