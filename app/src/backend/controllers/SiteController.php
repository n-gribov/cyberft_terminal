<?php

namespace backend\controllers;

use addons\edm\models\BankLetter\BankLetterSearch;
use addons\edm\models\BankLetter\BankLetterViewModel;
use addons\edm\models\EdmPayerAccountSearch;
use common\base\Controller;
use common\document\Document;
use common\helpers\CertsHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\models\form\KeyActivationForm;
use common\models\form\LoginKeyForm;
use common\models\form\LoginPasswordForm;
use common\models\form\PasswordResetRequestForm;
use common\models\form\ResetPasswordForm;
use common\models\form\SetPasswordForm;
use common\models\User;
use common\models\UserTerminal;
use common\modules\certManager\components\ssl\X509FileModel;
use common\modules\monitor\models\ExpiringCert;
use Psr\Log\LogLevel;
use SimpleXMLElement;
use Yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Cookie;
use yii\web\ErrorAction;
use yii\web\HttpException;
use yii\web\Response;

class SiteController extends Controller
{
    const LOGIN_METHOD_COOKIE_NAME = 'loginMethod';
    const LOGIN_METHOD_KEY = 'key';
    const LOGIN_METHOD_PASSWORD = 'password';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login-password', 'login-key', 'request-password-reset', 'reset-password', 'get-auth-passcode'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'index', 'logout', 'set-password', 'switch-terminal', 'activate-account',
                            'get-post-login-notification-modal-certs', 'get-post-login-notification-modal-letters'
                        ],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['login', 'error', 'redirect-if-user-inactive'],
                    ]
                ],
            ],
        ];
    }

	public function actionIndex()
	{
		return $this->redirect(Url::toRoute('/profile/dashboard'));
	}

	public function actionLogin()
	{
		if (!\Yii::$app->user->isGuest) {
			return $this->goHome();
		}

        if ($this->getPreviousLoginMethod() === self::LOGIN_METHOD_KEY) {
            return $this->redirect(['/site/login-key']);
        }

        return $this->redirect(['/site/login-password']);
	}

	public function actionLoginPassword()
	{
	    $this->layout = 'login';

		$model = new LoginPasswordForm();

        if (\Yii::$app->request->post()) {
            $this->storeLoginMethod(self::LOGIN_METHOD_PASSWORD);

            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                $this->clearUserFailedLogin($model->getUser());

                $user = $model->getUser();

                if ($user->checkPasswordExpired()) {
                    Yii::$app->session->setFlash('info', Yii::t('app/user', 'The password has expired'));

                    return $this->redirect(Url::toRoute('site/set-password'));
                }

                // Запись IP, с которого зашел пользователь
                $user->lastIp = Yii::$app->getRequest()->getUserIP();
                $user->save(false);

                // Регистрация события успешной авторизации пользователя
                Yii::$app->monitoring->extUserLog('UserLoginSuccess');

                $this->updateUserTerminal();

                $role = Yii::$app->user->identity->role;

                if ($role != User::ROLE_ADMIN){
                    Yii::$app->session->setFlash('checkPostLoginNotificationModalLetters', true);
                }

                Yii::$app->session->setFlash('checkPostLoginNotificationModalCerts', true);

                return $this->redirect(Url::toRoute('/profile/dashboard'));
            } else {
                $this->increaseUserFailedLogin($model->getUser());
            }
        }

        return $this->render('login-password', ['model' => $model]);
    }

	public function actionLoginKey()
	{
        $this->layout = 'login';

		$model = new LoginKeyForm();

		if (\Yii::$app->request->post()) {
            $this->storeLoginMethod(self::LOGIN_METHOD_KEY);

            $passcode    = \Yii::$app->session->get('authPasscode', '');
            $certBody = \Yii::$app->request->post('cert-body');

            if (X509FileModel::isCertificate($certBody)) {
                $x509Info = X509FileModel::loadData($certBody);
                $fingerprint = $x509Info->fingerprint;
            } else {
                $fingerprint = $certBody;
            }

            $signature   = \Yii::$app->request->post('signature');

            $result = $model->login($passcode, $fingerprint, $signature);
            $user = $model->getUser();

            if ($result === true) {
                // Запись IP, с которого зашел пользователь
                $user->lastIp = Yii::$app->getRequest()->getUserIP();
                $user->save(false);

                // Регистрация события успешной авторизации пользователя
                Yii::$app->monitoring->extUserLog('UserKeySuccess');
                $this->clearUserFailedLogin($user);

                $this->updateUserTerminal();

                $role = Yii::$app->user->identity->role;

                if ($role != User::ROLE_ADMIN) {
                    Yii::$app->session->setFlash('checkPostLoginNotificationModalLetters', true);
                }

                Yii::$app->session->setFlash('checkPostLoginNotificationModalCerts', true);

                return $this->redirect(Url::toRoute('/profile/dashboard'));
            } else {
                $this->increaseUserFailedLogin($user, 'key');
                Yii::$app->session->setFlash('error', $result);
            }
        }

        \Yii::$app->session->remove('authPasscode');

        return $this->render(
            'login-key',
            [
                'model' => $model,
                'algorithm' => 'sha256',
            ]
        );
    }

    public function actionGetAuthPasscode()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $passcode = uniqid(true) . time();
        \Yii::$app->session->set('authPasscode', $passcode);
        return ['passcode' => $passcode];
    }

    private function getPreviousLoginMethod()
    {
        return Yii::$app->request->cookies->getValue(self::LOGIN_METHOD_COOKIE_NAME);
	}

    private function storeLoginMethod(string $method)
    {
        Yii::$app->response->cookies->add(
            new Cookie([
                'name' => self::LOGIN_METHOD_COOKIE_NAME,
                'value' => $method,
                'expire' => time() + 86400 * 365 * 10
            ])
        );
	}

    private function updateUserTerminal()
    {
        $userModel = Yii::$app->user->identity;
        /**
         * Если админ отключил юзеру выбор терминалов,
         * значит он может работать с любым из привязанных к нему терминалов, поэтому очищаем terminalId.
         * Иначе юзер работает с одним выбранным терминалом,
         * тогда привязываем его к дефолтному терминалу, если выбранный терминал не был задан
         */
        if (!$userModel->disableTerminalSelect) {
            if (empty($userModel->terminalId) && $userModel->role != User::ROLE_ADMIN) {
                $userModel->terminalId = Yii::$app->terminals->defaultTerminal->id;
                $userModel->save(false, ['terminalId']);
            }
        } else {
            if (!empty($userModel->terminalId)) {
                $userModel->terminalId = null;
                $userModel->save(false, ['terminalId']);
            }
        }
    }

	public function actionRequestPasswordReset()
	{
        $this->layout = 'login';

		$model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post())) {
            if (!$model->checkMailer()) {
                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Sorry, we are unable to reset password for email provided.'));
            } else {
                if ($model->validate()) {
                    $model->sendEmail();
                    Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Check your email for further instructions.'));

                    return $this->goHome();
                }
            }
        }

		return $this->render('requestPasswordResetToken', ['model' => $model]);
	}

	public function actionResetPassword($token)
	{
		try {
			$model = new ResetPasswordForm($token);
		} catch (InvalidParamException $ex) {
			throw new BadRequestHttpException($ex->getMessage());
		}

		if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
			Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'New password was saved'));

			return $this->goHome();
		}

		return $this->render('resetPassword', ['model' => $model]);
	}

	public function actionSetPassword()
    {
        $model         = new SetPasswordForm();
        $model->userId = \Yii::$app->user->id;

        if (\Yii::$app->request->post()) {
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->setPassword()) {
                Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'New password was saved'));

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', \Yii::t('app', 'Set new password failed'));
            }
        }

        return $this->render('setPassword', ['model' => $model]);
    }

	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->goHome();
	}

	public function actionError()
	{
		$exception = Yii::$app->errorHandler->exception;
		if ($exception instanceof HttpException) {
			$code = $exception->statusCode;
		} else {
			$code = $exception->getCode();
		}

		/**
		 * Перехватываем обработку базовых HTTP ошибок, дабы стандартизировать сообщения отдаваемые пользователям
		 * т.к. на данный момент не понятно как быть с переводами сообщений отдаваемых черех HttpException (иногда они не содержат конструкций подразумевающих перевод)
		 * жестко задаем содержимое сообщений в данном методе
		 */

        // В случае ошибок типа 400, 401, 404 переадресация на главную страницу
        if (in_array($code, [400, 401, 404])) {
            Yii::$app->getResponse()->redirect('/')->send();

            return;
        } else if($code === 403) {
            return $this->render('error', [
                'exception' => $exception,
                'name' => Yii::t('app/error', 'Forbidden'),
                'message' => Yii::$app->user->isGuest
                    ? null
                    : Yii::t('app/error', 'The request was a valid request, but the server you have not access for this page.')
            ]);
        } else if($code === 500) {
            return $this->render('error', [
                'exception' => $exception,
                'name' => Yii::t('app/error', 'Internal Server Error'),
                'message' => Yii::t('app/error', 'Internal server error happen'),
            ]);
        }

		// используем стандартный механизм
		return (new ErrorAction($this->id, $this, ['view' => 'error']))->run();
	}

    public function actionActivateAccount()
    {
        $model = new KeyActivationForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($user = Yii::$app->user->identity) {
                $user->status = User::STATUS_ACTIVE;

                if ($user->save()) {
                    Yii::$app->getSession()->setFlash('success', \Yii::t('app/user', 'Activation completed!'));

                    // Ввод правильного ключа активации пользователя
                    Yii::$app->monitoring->extUserLog('InputCorrectActivationCode');

                    return $this->goHome();
                } else {
                    Yii::$app->getSession()->setFlash('error', \Yii::t('app/user', 'Activation failed!'));
                }
            } else {
                Yii::$app->getSession()->setFlash('error', \Yii::t('app/user', 'User info not found!'));
            }
        } else {
            // Ввод неправильного ключа активации пользователя
            Yii::$app->monitoring->extUserLog('InputIncorrectActivationCode');
        }

        return $this->render('activateAccount', [
            'model' => $model,
        ]);
    }

    /**
     * Clear user count of failed login attempts
     *
     * @param User $user User
     * @return boolean
     */
    private function clearUserFailedLogin($user)
    {
        try {
            if (empty($user)) {
                throw new Exception("User is empty");
            }

            if (!($user instanceof User)) {
                throw new Exception("User is not an instance of User");
            }

            $user->failedLoginCount = 0;

            return $user->save(false, ['failedLoginCount']);
        } catch (\Exception $ex) {
            Yii::info($ex->getMessage(), 'system');

            return false;
        }
    }

    /**
     * Increase count of failed login attempts
     *
     * @param User $user User
     * @return boolean
     */
    private function increaseUserFailedLogin($user, $type = 'password')
    {
        try {
            if (empty($user)) {
                return false;
            }

            if (!($user instanceof User)) {
                throw new \Exception('User is not an instance of User');
            }

            $userSettings = \Yii::$app->settings->get('Security');
            $user->failedLoginCount++;
            if ($user->failedLoginCount >= 3) {
                // Событие ошибки авторизации привязываем ко всем терминалам пользователя
                $userTerminals = UserTerminal::getUserTerminalIndexes($user->id);

                foreach($userTerminals as $terminalId) {
                    Yii::$app->monitoring->log('user:failedLogin', 'user', $user->id, [
                        'logLevel' => LogLevel::ALERT,
                        'userId' => $user->id,
                        'terminalId' => $terminalId
                    ]);
                }

                if ($user->failedLoginCount >= $userSettings->maxLoginAttemptsCount) {
                    $user->failedLoginCount = 0;
                    $user->status = User::STATUS_INACTIVE;
                }
            } else {
                // Регистрация события неуспешной авторизации пользователя
                if ($type = 'password') {
                    Yii::$app->monitoring->log('user:UserLoginFailed', 'user', $user->id, [
                        'terminalId' => $user->terminalId,
                        'userId' => $user->id,
                    ]);
                } else if ($type = 'key') {
                    Yii::$app->monitoring->log('user:UserKeyFailed', 'user', $user->id, [
                        'terminalId' => $user->terminalId,
                        'userId' => $user->id,
                    ]);
                }
            }

            return $user->save(false, ['failedLoginCount', 'status']);
        } catch (\Exception $ex) {
            Yii::info($ex->getMessage(), 'system');

            return false;
        }
    }

    public function actionSwitchTerminal($id)
    {
        $userModel = Yii::$app->user->identity;
        /**
         * По дефолту ставим terminalId = null, то есть не выбран никакой терминал.
         * Далее либо будет выбран валидный терминал, либо останется null
         */
        $userModel->terminalId = null;
        /**
         *  Если админ НЕ отключил пользователю выбор терминалов
         */
        if (!$userModel->disableTerminalSelect) {

            $terminals = UserTerminal::getUserTerminalIds(Yii::$app->user->id);

            if (isset($terminals[$id]) && $userModel->terminalId != $id) {
                $userModel->terminalId = $id;
            }
        }

        $userModel->save(false, ['terminalId']);

        // Адрес редиректа при переключении терминала
        $targetUrl = '/';

        return $this->redirect($targetUrl);
    }

    public function actionGetPostLoginNotificationModalCerts()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $user = Yii::$app->user->identity;

        // Главный администратор видит данные по всем сертификатам
        if ($user->isMainAdmin()) {
            $certs = ExpiringCert::search(30);
        } else {
            $certs = ExpiringCert::search(30, $user);
        }

        if (empty($certs)) {
            return [];
        }

        $expiredCerts = CertsHelper::filterExpiredCerts($certs);

        /*
         * CYB-4340 Определяем, есть ли у пользователя доступ к терминалу, где создана организация,
         * имеющая счет в банке "Платина" (БИК 044525931)
         */

        $searchModel = new EdmPayerAccountSearch();
        $isPlatinaBik = $searchModel->searchBikInUserTerminals('044525931');

        $modalContent = $this->renderPartial(
            '@backend/views/site/expiringCertsNotificationModal',
            [
                'certs' => $expiredCerts,
                'isPlatinaBik' => $isPlatinaBik
            ]
        );

        return compact('modalContent');
    }

    public function actionGetPostLoginNotificationModalLetters()
    {
        $modalContent = [];
        Yii::$app->response->format = Response::FORMAT_JSON;

        $bankLetterModel = new BankLetterSearch();
        $dataProvider = $bankLetterModel->search([
            'BankLetterSearch' => [
                'direction' => 'IN',
            ],
        ]);

        foreach ($dataProvider->getModels() as $model)
        {
            $id = $model->getAttribute('id');
            $viewed = $model->getAttribute('viewed');
            $type = $model->getAttribute('type');
            $actualStoredFileId = $model->getAttribute('actualStoredFileId');

            if (($viewed != 1) && ($type == 'auth.026')) {
                $searchModel = new BankLetterSearch(['id' => $id]);
                $query = BankLetterSearch::find();
                $searchModel->applyQueryFilters(['id' => $id], $query);

                $document = $query->one();

                try{
                    $letter = BankLetterViewModel::create($document);
                } catch (\Exception $ex) {
                    \Yii::info("Letter $id is malformed:");
                    \Yii::info($ex->getMessage());
                    continue;
                }

                $documentModel = Yii::$app->terminalAccess->findModel(Document::className(), $id);

                $content = (string) CyberXmlDocument::getTypeModel($actualStoredFileId);
                $tp = '';
                if (!empty($content)) {
                    $xml = new SimpleXMLElement($content);
                    $tp = $xml->CcyCtrlReqOrLttr->ReqOrLttr->Tp;
                }

                if ($tp == 'IMPT'){
                    $modalContent[] = $this->renderPartial(
                        '@addons/edm/views/bank-letter/_viewModal.php',
                        compact('document', 'letter', 'tp')
                    );
                    $document->viewed = 1;
                    $document->save(false, ['viewed']);
                }
            }
        }
        return compact('modalContent');
    }

    /**
     * Завершение сеанса пользователя
     * и редирект на страницу авторизации
     * @return Response
     */
    public function actionRedirectIfUserInactive()
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
        }

        Yii::$app->session->addFlash('error', Yii::t('app', 'You were inactive for a while. For security reasons your session was closed. If you want to continue, please log in again.'));

        return $this->redirect('/login');
    }

}
