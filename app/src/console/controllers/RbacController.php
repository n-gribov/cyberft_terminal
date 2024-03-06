<?php

namespace console\controllers;

use common\models\User;
use Yii;
use common\base\ConsoleController;
use yii\helpers\Console;

/**
 * Manages users
 */
class RbacController extends ConsoleController
{
	public $color = true;

	/**
	 * Help message
	 */
	public function actionIndex()
	{
		$this->run('/help', ['rbac']);
	}


	public function actionInit()
	{
        $auth = Yii::$app->authManager;

		// Default role rule
        $groupRule = new \common\rbac\rules\RoleGroupRule();

		$auth->add($groupRule);

        $this->output("Building roles");

        $rbacConfig = require Yii::getAlias('@common/rbac/config.php');
		$roles = [];

        foreach ($rbacConfig['roles'] as $roleName) {
            $role = $auth->createRole($roleName);
            $role->ruleName = $groupRule->name;
            $auth->add($role);
            $roles[$roleName] = $role;
        }

		foreach ($rbacConfig['permissions'] as $group => $permissions) {

			foreach ($permissions as $permission => $settings) {

				$ruleName = $group.ucfirst($permission);

				$rule = $auth->createPermission($ruleName);
				$rule->description = (isset($settings['description']) ? $settings['description'] : null );

                if (isset($settings['rule'])) {
                    if (class_exists($settings['rule'])) {
                        $ruleClass = new $settings['rule']();
                        $auth->add($ruleClass);
                        $rule->ruleName = $ruleClass->name;
                    } else {
                        Console::output('Error! For rule "'. $ruleName .'" not found class "'. $settings['rule'] .'"');
                    }
                }

				$auth->add($rule);

				foreach ($settings['access'] as $roleName) {
                    if (!$auth->hasChild($roles[$roleName], $rule)) {
    					$auth->addChild($roles[$roleName], $rule);
                    } else {
                        echo "no add child\n";
                    }
				}
			}
		}

        Console::output('RBAC initialized');

		return ConsoleController::EXIT_CODE_NORMAL;
	}

    public function actionAssign()
    {
        $users = User::find()->where(['role' => [User::ROLE_ADMIN]])->all();
        $auth = Yii::$app->authManager;
        $role = $auth->getRole('admin');
        foreach ($users as $user) {

            $auth->assign($role, $user->id);
            $this->output("User # {$user->id} assigned");
        }
    }

	public function actionPurge()
	{
		$auth = Yii::$app->authManager;
		$auth->removeAll();

		Console::output('RBAC related tables truncated');

		return ConsoleController::EXIT_CODE_NORMAL;
	}

	public function actionReload()
	{
		$this->actionPurge();
		$this->actionInit();
	}

}
