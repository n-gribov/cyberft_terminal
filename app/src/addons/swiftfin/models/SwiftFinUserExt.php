<?php

namespace addons\swiftfin\models;

use common\models\BaseUserExt;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * @property string $role
 */

class SwiftFinUserExt extends BaseUserExt
{
	const ROLE_NONE = '';
	const ROLE_PREAUTHORIZER = 'preAuthorizer';
	const ROLE_AUTHORIZER = 'authorizer';

    public static function tableName()
    {
        return 'swiftfin_UserExt';
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['role'], 'default', 'value' => ''],
                ['role', 'in', 'range' => array_keys(self::roleLabels())],
            ]
        );
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::attributeLabels(),
            [
                'role' => Yii::t('app', 'Swift role'),
                'roleLabel' => Yii::t('app', 'Swift role')
            ]
        );
    }

	public function hasSettings()
	{
		return !empty($this->id);
	}

	public static function roleLabels()
    {
        return [
			self::ROLE_NONE			  => Yii::t('app', 'None'),
			self::ROLE_PREAUTHORIZER  => Yii::t('app', 'Preliminary Authorizer'),
			self::ROLE_AUTHORIZER     => Yii::t('app', 'Authorizer'),
        ];
    }

	public function getRoleLabel()
	{
		return self::roleLabels()[$this->role];
	}

    public function hasAdditionalSettings(): bool
    {
        return true;
    }
}
