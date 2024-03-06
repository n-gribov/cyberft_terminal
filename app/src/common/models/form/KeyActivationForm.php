<?php
namespace common\models\form;

use Yii;
use yii\base\Model;
use common\models\User;

class KeyActivationForm extends Model
{
	public $key;

	/**
	 * @var \common\models\User $_user User instance
	 */
	private $_user = false;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['key'], 'required'],
            ['key', 'validateActivateKey'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'key' => Yii::t('app/user', 'Activation key'),
		];
	}

	public function validateActivateKey($attribute, $params)
	{
        if (!$this->hasErrors()) {
			$user = Yii::$app->user->identity;

			if (!$user || !$user->validateActivateKey($this->key)) {
				$this->addError($attribute,
					Yii::t('app', 'Incorrect validation key.'));
			}
		}
	}
}