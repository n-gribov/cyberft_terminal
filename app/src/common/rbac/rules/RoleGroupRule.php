<?php
namespace common\rbac\rules;
use common\models\User;
use Yii;
use yii\rbac\Rule;

/**
 * Checks if user group matches
 *
 * @author fuzz
 */
class RoleGroupRule extends Rule
{

	public $name = 'roleGroup';

	public function execute($userId, $item, $params)
	{
		if (!Yii::$app->user->isGuest) {
			$group = Yii::$app->user->identity->role;
			if ($item->name === 'admin') {

				return $group == User::ROLE_ADMIN;
			} else if ($item->name === 'user') {

				return $group == User::ROLE_USER;

			} else if ($item->name === 'lso') {

                return $group == User::ROLE_LSO;
            } else if ($item->name === 'rso') {

                return $group == User::ROLE_RSO;
            } else if ($item->name === 'controller') {

                return $group == User::ROLE_CONTROLLER;
            } else if ($item->name === 'additionalAdmin') {

                return $group == User::ROLE_ADDITIONAL_ADMIN;
            }
		}

		return false;
	}
}
