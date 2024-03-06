<?php

namespace console\controllers;

use common\models\User;
use common\models\UserColumnsSettings;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Manages users
 */
class UserController extends Controller
{
	public $color = true;

	/**
	 * Help message
	 */
	public function actionIndex()
	{
		$this->run('/help', ['user']);
	}


	/**
	 * @param string $email
	 * @param string $pkeyPassword
	 * @return int
	 * @deprecated
	 */
	public function actionGenerateCertificate($email, $pkeyPassword)
	{
		$this->stdout("Создаем ключи пользователя Главный администратор...\n", Console::FG_GREEN);

		$certManager = Yii::$app->getModule('certManager');
		$keys = $certManager->generateUserKeys($email, $pkeyPassword);
		if ($keys) {
			$privateKey = $keys['private'];
			$publicKey = $keys['public'];
			$certificate = $keys['cert'];
		} else {
			$this->stdout("There was an error creating keys\n");
			return Controller::EXIT_CODE_ERROR;
		}

		$path = \common\modules\certManager\Module::getUserKeyStoragePath();

		$publicKey = $path . '/' . $email . '.pub';
		$privateKey = $path . '/' . $email . '.key';
		$certificate = $path . '/' . $email . '.crt';

		$this->stdout(
			"#########################################################################################################\n"
			. "#\n"
			. "# Сертификат закрытого ключа главного администратор: $certificate\n"
			. "# Закрытый ключ: $privateKey\n"
			. "# Открытый ключ: $publicKey\n"
			. "# Fingerprint: " . $certManager->getCertFingerprint($certificate) . "\n"
			. "# После того, как сохраните сертификат, обязательно УДАЛИТЕ эти файлы!\n"
			. "#\n"
			. "#########################################################################################################\n",
			Console::FG_RED
		);

		return Controller::EXIT_CODE_NORMAL;
	}

	/**
	 * Add administrator user
	 *
	 * @param string $email    Email
	 * @param string $password Password
	 * @return int
	 */
	public function actionAddAdmin($email, $password)
	{

		echo "actionAddAdmin: email=" . $email . "\n";

		$user = new User();
        $user->setScenario('install');
		$user->setAttributes([
            'email'  => $email,
            'role'   => User::ROLE_ADMIN,
            'status' => User::STATUS_ACTIVE,
        ]);

        /*
		 * Проверяем наличие Пользователя, которого собираемся добавить.
		 * Если он уже существует, то дальнейшие действия не выполняем - они не
		 * требуются.
		 * Нам нужно четко понимать, что пользователь уже существует, отделяя
		 * эту ситуацию от прочих ошибок валидации или сохранения данных модели.
		 */
		if (($existingUser = $user->findByEmailOnly($email)) != null) {
			$this->stdout("User {$existingUser->email} already created with id={$existingUser->id}\n", Console::FG_GREEN);
			return Controller::EXIT_CODE_NORMAL;
		}

		$user->setPassword($password);
		$user->generateAuthKey();

		if ($user->save()) {

			Console::output("Created user {$user->id}");

			return Controller::EXIT_CODE_NORMAL;
		} else {
			$this->stderr("Error while saving user: \n * " . join(PHP_EOL . " * ", $user->getFirstErrors()), Console::FG_RED);
			return Controller::EXIT_CODE_ERROR;
		}
	}

    /**
     * Add security officer
     *
     * @param string $role     User role
     * @param string $email    User email
     * @param string $password User password
     * @return int
     */
    public function actionAddSecureOfficer($role, $email, $password)
    {
        echo "actionAddSecureOfficer: email=" . $email . "\n";

        $user = new User();
        $user->setScenario('install');

        if ($role != 'lso' && $role != 'rso') {
            $this->stdout("Role must be 'lso' or 'rso'\n", Console::FG_GREEN);
            return Controller::EXIT_CODE_NORMAL;
        }

        $user->setAttributes([
            'email' => $email,
            'status' => User::STATUS_ACTIVE,
            'role' => ('lso' == $role) ? User::ROLE_LSO : User::ROLE_RSO,
        ]);

        /*
         * Проверяем наличие пользователя, которого собираемся добавить.
         * Если он уже существует, то дальнейшие действия не выполняем.
         * Нам нужно четко понимать, что пользователь уже существует, отделяя
         * эту ситуацию от прочих ошибок валидации или сохранения данных модели.
         */
        if (($existingUser = $user->findByEmailOnly($email)) != null) {
            $this->stdout("User {$existingUser->email} already created with id={$existingUser->id}\n", Console::FG_GREEN);

            return Controller::EXIT_CODE_NORMAL;
        }

        $user->setPassword($password);
        $user->generateAuthKey();

        if ($user->save()) {

            Console::output("Created user {$user->id}");
            $auth = Yii::$app->authManager;

            $userRole = $auth->getRole($role);
            $auth->assign($userRole, $user->id);

            $this->stdout("Assigned secure officer role for {$user->id}\n", Console::FG_GREEN);

            return Controller::EXIT_CODE_NORMAL;
        } else {
            $this->stderr("Error while saving user: \n * " . join(PHP_EOL . " * ", $user->getFirstErrors()), Console::FG_RED);

            return Controller::EXIT_CODE_ERROR;
        }
    }

	/**
	 * Change user password. Using user/set-password UserID Password
	 *
	 * @param string $userId
	 * @param string $password
	 * @return int
	 */
	public function actionSetPassword($userId, $password)
	{
		$user = User::findOne($userId);
		$user->setPassword($password);
		$user->isReset = User::IS_RESET_FALSE;
        $user->setScenario('setPassword');
		if ($user->save()) {
			Console::output('Password changed');

			return Controller::EXIT_CODE_NORMAL;
		} else {
			Console::output("Error while saving user: \n * " . join(PHP_EOL . " * ", $user->getFirstErrors()));

			return Controller::EXIT_CODE_ERROR;
		}
	}

    /**
     * Команда позволяет сбрасывать
     * настройки колонок в журналах для пользователей
     * Если указан id конкретного пользователя,
     * то настройки сбрасываются только для него
     */
    public function actionResetColumnsSettings($userId = null)
    {
        // Условия для условий поиска настроек
        $conditions = [];

        // Проверяем, указан ли пользователь
        if ($userId) {
            $user = User::findOne($userId);

            if (!$user) {
                // Если указанный пользователь не найден,
                // то возвращаем ошибку
                $this->stderr('Can\'t find user by id' . PHP_EOL);
            }

            $conditions = ['userId' => $userId];
        }

        // Удаляем настройки
        UserColumnsSettings::deleteAll($conditions);
        $this->stdout('Columns settings reset' . PHP_EOL);
    }

}