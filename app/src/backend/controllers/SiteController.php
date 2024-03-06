<?php

namespace backend\controllers;

use addons\edm\models\BankLetter\BankLetterSearch;
use addons\edm\models\BankLetter\BankLetterViewModel;
use addons\edm\models\EdmPayerAccountSearch;
use common\base\Controller;
use common\helpers\CertsHelper;
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
use Yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Cookie;
use yii\web\ErrorAction;
use yii\web\HttpException;
use yii\web\Response;

/**
* Класс контроллера обслуживает базовые запросы к сайту
*/
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

    /**
     * Метод обрабатывает действия пользователя на главной странице
     * @return type
     */
    public function actionIndex()
    {
        // Перенаправить на страницу индекса
        return $this->redirect('/profile/dashboard');
    }

    /**
     * Метод обрабатывает действия пользователя на странице логина
     * @return type
     */
    public function actionLogin()
    {
        // Если пользователь уже зашёл, то перенаправляется на домашнюю страницу
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        // Если у пользователя установлен вход по ключу, то перенаправляется на страницу входа с ключом
        if ($this->getPreviousLoginMethod() === self::LOGIN_METHOD_KEY) {
            // Перенаправить на страницу входа по ключу
            return $this->redirect(['/site/login-key']);
        }

        // Перенаправить на страницу входа с логином и паролем
        return $this->redirect(['/site/login-password']);
    }

    /**
     * Метод осуществляет вход с логином и паролем
     * @return type
     */
    public function actionLoginPassword()
    {
        // Выбор вёрстки для страницы логина
        $this->layout = 'login';

        // Модель для формы входа с логином и паролем
        $model = new LoginPasswordForm();

        // Если была отправка данных через POST
        if (\Yii::$app->request->post()) {
            // Сохранить тип логина как "логин и пароль"
            $this->storeLoginMethod(self::LOGIN_METHOD_PASSWORD);

            // Если данные модели успешно загружены из формы в браузере и был успешно вызван метод login()
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                // Очистить неудачные попытки входа, сделанные ранее
                $this->clearUserFailedLogin($model->getUser());

                // Получить модель пользователя из модели форму логина
                $user = $model->getUser();

                // Проверить истечение срока годности пароля
                if ($user->checkPasswordExpired()) {
                    // Поместить в сессию флаг сообщения об истечении пароля
                    Yii::$app->session->setFlash('info', Yii::t('app/user', 'The password has expired'));
                    // Если пароль истёк, перенаправить на страницу обновления пароля
                    return $this->redirect(Url::toRoute('site/set-password'));
                }

                // Запись IP, с которого зашел пользователь
                $user->lastIp = Yii::$app->getRequest()->getUserIP();
                $user->save(false);

                // Зарегистрировать событие успешной авторизации пользователя в модуле мониторинга
                Yii::$app->monitoring->extUserLog('UserLoginSuccess');

                // Обновить привязку терминала к пользователю
                $this->updateUserTerminal();

                // Получить роль пользователя из активной сессии
                $role = Yii::$app->user->identity->role;

                // Если роль не админ, то установить в сессии флаг для проверки писем-оповещений
                if ($role != User::ROLE_ADMIN){
                    // Поместить в сессию флаг проверки уведомлений после логина
                    Yii::$app->session->setFlash('checkPostLoginNotificationModalLetters', true);
                }

                // Поместить в сессию флаг проверки уведомлений о сертификатах после логина
                Yii::$app->session->setFlash('checkPostLoginNotificationModalCerts', true);

                // Перенаправить на страницу индекса
                return $this->redirect(Url::toRoute('/profile/dashboard'));
            } else {
                // Неудачная попытка входа - увеличить счётчик неудачных попыток по логину-паролю
                $this->increaseUserFailedLoginCount($model->getUser());
            }
        }

        // Если не было отправки данных, то вывести страницу с формой логина
        return $this->render('login-password', ['model' => $model]);
    }

    /**
     * Метод осуществляет вход по ключу
     * @return type
     */
    public function actionLoginKey()
    {
        // Выбор вёрстки для страницы логина
        $this->layout = 'login';

        // Модель для формы входа по ключу
        $model = new LoginKeyForm();

        // Если была отправка данных через POST
        if (\Yii::$app->request->post()) {
            // Сохранить тип логина как "по ключу"
            $this->storeLoginMethod(self::LOGIN_METHOD_KEY);

            // Получить из сессии уникальный код
            $passcode = \Yii::$app->session->get('authPasscode', '');
            // Получить из POST тело сертификата
            $certBody = \Yii::$app->request->post('cert-body');

            // Если тело является сертификатом
            if (X509FileModel::isCertificate($certBody)) {
                // Получить информацию о сертификате
                $x509Info = X509FileModel::loadData($certBody);
                // Получить фингерпринт
                $fingerprint = $x509Info->fingerprint;
            } else {
                // Иначе это просто фингерпринт
                $fingerprint = $certBody;
            }

            // Получить из POST подпись
            $signature = \Yii::$app->request->post('signature');

            // Вызвать у модели формы входа метод login с уникальным кодом, фингерпринтом и подписью
            $result = $model->login($passcode, $fingerprint, $signature);
            // Получить модель пользователя из модели формы входа
            $user = $model->getUser();

            // Если вход был успешным
            if ($result === true) {
                // Запись IP, с которого зашел пользователь
                $user->lastIp = Yii::$app->getRequest()->getUserIP();
                $user->save(false);

                // Зарегистрировать событие успешной авторизации пользователя в модуле мониторинга
                Yii::$app->monitoring->extUserLog('UserKeySuccess');
                // Очистить предыдущие неудачные попытки входа
                $this->clearUserFailedLogin($user);

                // Обновить привязку терминала к пользователю
                $this->updateUserTerminal();
                // Получить роль пользователя из активной сессии
                $role = Yii::$app->user->identity->role;

                // Если роль не админ
                if ($role != User::ROLE_ADMIN) {
                    // Поместить в сессию флаг проверки уведомлений о письмах после логина
                    Yii::$app->session->setFlash('checkPostLoginNotificationModalLetters', true);
                }

                // Поместить в сессию флаг проверки уведомлений о сертификатах после логина
                Yii::$app->session->setFlash('checkPostLoginNotificationModalCerts', true);

                // Перенаправить на страницу индекса
                return $this->redirect(Url::toRoute('/profile/dashboard'));
            } else {
                // Неудачная попытка входа - увеличить счётчик неудачных попыток по ключу
                $this->increaseUserFailedLoginCount($user, 'key');
                // Поместить в сессию флаг сообщения о неудачной попытке входа
                Yii::$app->session->setFlash('error', $result);
            }
        }

        // Удалить уникальный код из сессии
        \Yii::$app->session->remove('authPasscode');

        // Если не было отправки данных, то вывести страницу с формой логина по ключу
        return $this->render(
            'login-key',
            [
                'model' => $model,
                'algorithm' => 'sha256',
            ]
        );
    }

    /**
     * Метод получает уникальный код, который используется для подписания при входе по ключу.
     * Запрашивается через AJAX страницей логина, в которой находится JS-скрипт взаимодействия с утилитой подписания.
     * Скрипт получает уникальный код, который затем использует для подписания.
     * Этот же код сохраняется в сессии, чтобы потом метод actionLoginKey() тоже использовал его для проверки.
     * 
     * @return type
     */
    public function actionGetAuthPasscode()
    {
        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;
        // Код генерируется как уникальный id процесса + текущее время в секундах
        $passcode = uniqid(true) . time();
        // Сохранить код в сессии
        \Yii::$app->session->set('authPasscode', $passcode);

        // Вернуть уникальный код в виде JSON-объекта
        return ['passcode' => $passcode];
    }

    /*
     * Метод получает предыдущий тип логина
     */
    private function getPreviousLoginMethod()
    {
        // Возвращает значение куки self::LOGIN_METHOD_COOKIE_NAME 
        return Yii::$app->request->cookies->getValue(self::LOGIN_METHOD_COOKIE_NAME);
    }

    /**
     * Метод сохраняет текущий тип логина
     * @param string $method
     */
    private function storeLoginMethod(string $method)
    {
        // Сохранить тип логина в куки с именем self::LOGIN_METHOD_COOKIE_NAME
        Yii::$app->response->cookies->add(
            new Cookie([
                'name' => self::LOGIN_METHOD_COOKIE_NAME,
                'value' => $method,
                'expire' => time() + 86400 * 365 * 10
            ])
        );
    }

    /**
     * Метод обновляет привязку терминала к пользователю
     */
    private function updateUserTerminal()
    {
        // Получить модель пользователя из активной сессии
        $userModel = Yii::$app->user->identity;

        // Если админ отключил пользователю выбор терминалов,
        // значит пользователь может работать с любым из привязанных к нему терминалов.
        // В этом случая очищаем terminalId в модели пользователя, т.е. не задан никакой.
        if (!$userModel->disableTerminalSelect) {
            if (empty($userModel->terminalId) && $userModel->role != User::ROLE_ADMIN) {
                $userModel->terminalId = Yii::$app->exchange->defaultTerminal->id;
                $userModel->save(false, ['terminalId']);
            }
        } else {
            // Иначе пользователь работает с одним выбранным терминалом,
            // тогда привязываем его к дефолтному терминалу, если выбранный терминал не был задан
            if (!empty($userModel->terminalId)) {
                $userModel->terminalId = null;
                $userModel->save(false, ['terminalId']);
            }
        }
    }

    /**
     * Метод работает со страницей сброса пароля
     * @return type
     */
    public function actionRequestPasswordReset()
    {
        // Выбор вёрстки для страницы логина
        $this->layout = 'login';

        // Модель формы для сброса пароля
        $model = new PasswordResetRequestForm();

        // Если данные модели успешно загружены из формы в браузере
        if ($model->load(Yii::$app->request->post())) {
            // Если не прошла проверка почтового адреса
            if (!$model->checkMailer()) {
                // Поместить в сессию флаг оповещения о невозможности сброса пароля через почту
                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Sorry, we are unable to reset password for email provided.'));
            } else {
                // Если модель валидна
                if ($model->validate()) {
                    // Послать письмо о сбросе пароля
                    $model->sendEmail();
                    Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Check your email for further instructions.'));
                    // Сделать переход на домашнюю страницу
                    return $this->goHome();
                }
            }
        }

        // Вывести страницу сброса пароля
        return $this->render('requestPasswordResetToken', ['model' => $model]);
    }

    /**
     * Метод сбрасывает пароль пользователя
     * @param string $token - токен для сброса
     * @return type
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            // Модель формы сброса пароля с указанным токеном
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $ex) {
            // Если возникла ошибка при создании модели, сгенерировать объект-исключение
            throw new BadRequestHttpException($ex->getMessage());
        }

        // Если данные модели успешно загружены из формы в браузере, модель валидна и пароль поменялся
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            // Поместить в сессию флаг об успешной смене пароля
            Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'New password was saved'));
            // Перенаправить на домашнюю страницу
            return $this->goHome();
        }

        // Вывести страницу с формой сброса пароля
        return $this->render('resetPassword', ['model' => $model]);
    }

    /**
     * Метод задаёт пароль пользователя
     * @return type
     */
    public function actionSetPassword()
    {
        // Модаль формы сброса пароля
        $model = new SetPasswordForm();
        // Получение user_id из текущей сессии
        $model->userId = \Yii::$app->user->id;

        // Если переданы данные POST
        if (\Yii::$app->request->post()) {
            // Если данные модели успешно загружены из формы в браузере, модель валидна и пароль установлен
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->setPassword()) {
                // Поместить в сессию флаг об успешном задании пароля
                Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'New password was saved'));
                // Сделать переход на домашнюю страницу
                return $this->goHome();
            } else {
                // Поместить в сессию флаг об ошибке задания пароля
                Yii::$app->getSession()->setFlash('error', \Yii::t('app', 'Set new password failed'));
            }
        }

        // Вывести страницу с формой задания пароля
        return $this->render('setPassword', ['model' => $model]);
    }

    /**
     * Метод осуществляет выход пользователя
     * @return type
     */
    public function actionLogout()
    {
        // Сбросить сессию пользователя
        Yii::$app->user->logout();
        // Сделать переход на домашнюю страницу
        return $this->goHome();
    }

    /**
     * Метод генерирует сообщения об ошибках
     * @return type
     */
    public function actionError()
    {
        // Получить тип объекта-исключения
        $exception = Yii::$app->errorHandler->exception;
        // Если исключение типа HTTP
        if ($exception instanceof HttpException) {
            // Код ошибки это HTTP-статус
            $code = $exception->statusCode;
        } else {
            // Иначе код ошибки это код исключения
            $code = $exception->getCode();
        }

        /**
         * Перехватываем обработку базовых HTTP ошибок, дабы стандартизировать сообщения отдаваемые пользователям
         * т.к. на данный момент не понятно как быть с переводами сообщений отдаваемых черех HttpException
         * (иногда они не содержат конструкций подразумевающих перевод)
         * жестко задаем содержимое сообщений в данном методе
         */

        // В случае ошибок типа 400, 401, 404 переадресация на главную страницу
        if (in_array($code, [400, 401, 404])) {
            Yii::$app->getResponse()->redirect('/')->send();
            return;
        } else if ($code === 403) {
            // Вывести страницу с ошибкой
            return $this->render('error', [
                'exception' => $exception,
                'name' => Yii::t('app/error', 'Forbidden'),
                'message' => Yii::$app->user->isGuest
                    ? null
                    : Yii::t('app/error', 'The request was a valid request, but the server you have not access for this page.')
            ]);
        } else if ($code === 500) {
            // Вывести страницу с ошибкой
            return $this->render('error', [
                'exception' => $exception,
                'name' => Yii::t('app/error', 'Internal Server Error'),
                'message' => Yii::t('app/error', 'Internal server error happen'),
            ]);
        }

        // Используем стандартный механизм
        return (new ErrorAction($this->id, $this, ['view' => 'error']))->run();
    }

    /**
     * Метод активирует учётную запись
     * 
     * @return type
     */
    public function actionActivateAccount()
    {
        // Модель формы активации ключа
        $model = new KeyActivationForm();

        // Если данные модели успешно загружены из формы в браузере
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // Если пользователь залогинен, получить его модель из текущей сессии
            if ($user = Yii::$app->user->identity) {
                // Активировать статус пользователя
                $user->status = User::STATUS_ACTIVE;
                // Если модель успешно сохранена в БД
                if ($user->save()) {
                    // Установить в сессии флаг об успешной активации
                    Yii::$app->getSession()->setFlash('success', \Yii::t('app/user', 'Activation completed!'));

                    // Зарегистрировать событие ввода правильного ключа активации пользователя в модуле мониторинга
                    Yii::$app->monitoring->extUserLog('InputCorrectActivationCode');

                    return $this->goHome();
                } else {
                    // Установить в сессии флаг об ошибке активации
                    Yii::$app->getSession()->setFlash('error', \Yii::t('app/user', 'Activation failed!'));
                }
            } else {
                // Если пользователь не залогинен, установить в сессии флаг о ненайденном пользователе
                Yii::$app->getSession()->setFlash('error', \Yii::t('app/user', 'User info not found!'));
            }
        } else {
            // Зарегистрировать событие ввода неправильного ключа активации пользователя в модуле мониторинга
            Yii::$app->monitoring->extUserLog('InputIncorrectActivationCode');
        }

        // Вывести страницу с формой активации пользователя
        return $this->render('activateAccount', [
            'model' => $model,
        ]);
    }

    /**
     * Метод очищает информацию о неудачных попытках входа
     *
     * @return boolean
     */
    private function clearUserFailedLogin(User $user)
    {
        try {
            // Если модель пользователя пуста
            if (empty($user)) {
                // Сгенерировать исключение
                throw new Exception('User is empty');
            }

            // Обнулить счётчик неудачных попыток входа
            $user->failedLoginCount = 0;
            // Сохранить модель и вернуть результат сохранения
            return $user->save(false, ['failedLoginCount']);
        } catch (\Exception $ex) {
            // В случае ошибки перехватить исключение, записать текст ошибки в лог и вернуть false
            Yii::info($ex->getMessage(), 'system');

            return false;
        }
    }

    /**
     * Метод увеличивает счётчик неудачных попыток входа
     *
     * @param User $user User
     * @return boolean
     */
    private function increaseUserFailedLoginCount(User $user, $type = 'password')
    {
        try {
            // Если модель пользователя пуста, вернуть false
            if (empty($user)) {
                return false;
            }

            // Получить настройки безопасности для пользователя
            $userSettings = \Yii::$app->settings->get('Security');
            // Увеличить счётчик неудачных попыток входа
            $user->failedLoginCount++;
            // Если попыток более двух
            if ($user->failedLoginCount >= 3) {
                // Событие ошибки авторизации привязываем ко всем терминалам пользователя.
                // Получить список терминалов, связанных с пользователем
                $userTerminals = UserTerminal::getUserTerminalIndexes($user->id);

                foreach($userTerminals as $terminalId) {
                    // Для каждого терминала в списке зарегистрировать
                    // событие неудачного логина в модуле мониторинга
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
                // Зарегистрировать событие неуспешной авторизации пользователя
                if ($type = 'password') {
                    // Если это была попытка входа по логину и паролю,
                    // зарегистрировать соответствующее событие в модуле мониторинга
                    Yii::$app->monitoring->log('user:UserLoginFailed', 'user', $user->id, [
                        'terminalId' => $user->terminalId,
                        'userId' => $user->id,
                    ]);
                } else if ($type = 'key') {
                    // Если это была попытка входа по ключу,
                    // зарегистрировать соответствующее событие в модуле мониторинга
                    Yii::$app->monitoring->log('user:UserKeyFailed', 'user', $user->id, [
                        'terminalId' => $user->terminalId,
                        'userId' => $user->id,
                    ]);
                }
            }

            // Сохранить в базе данных для этого пользователя счётчик неудачных попыток
            return $user->save(false, ['failedLoginCount', 'status']);
        } catch (\Exception $ex) {
            // В случае ошибки перехватить исключение, записать текст ошибки в лог и вернуть false
            Yii::info($ex->getMessage(), 'system');

            return false;
        }
    }
    
    /**
     * Метод задаёт активный терминал для пользователя
     * 
     * @param type $id требуеумый id терминала
     * @return type
     */
    public function actionSwitchTerminal($id)
    {
        // Получить модель пользователя из активной сессии
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
            // Получить список всех терминалов, связанных с пользователем
            $terminals = UserTerminal::getUserTerminalIds(Yii::$app->user->id);
            // Если в списке терминалов есть требуемый id, то назначить его пользователю
            if (isset($terminals[$id]) && $userModel->terminalId != $id) {
                $userModel->terminalId = $id;
            }
        }

        // Сохранить в базе модель пользователя с изменённым полем терминала
        $userModel->save(false, ['terminalId']);

        // Адрес перехода при переключении терминала
        $targetUrl = '/';

        // Перенаправить на страницу индекса
        return $this->redirect($targetUrl);
    }

    /**
     * Метод получает данные о сертификатах пользователя
     * для отображения в модальном окне на странице после успешного входа
     * 
     * @return type
     */
    public function actionGetPostLoginNotificationModalCerts()
    {
        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Получить модель пользователя из активной сессии
        $user = Yii::$app->user->identity;

        // Главный администратор видит данные по всем сертификатам
        if ($user->isMainAdmin()) {
            // Получить список всех истекающих сертификатов
            $certs = ExpiringCert::search(30);
        } else {
            // Получить список всех истекающих сертификатов для пользователя
            $certs = ExpiringCert::search(30, $user);
        }

        // Если сертификатов нет, вернуть пустой результат
        if (empty($certs)) {
            return [];
        }

        // Отфильтровать уже истёкшие сертификаты
        $expiredCerts = CertsHelper::filterExpiredCerts($certs);

        /*
         * CYB-4340 Определяем, есть ли у пользователя доступ к терминалу, где создана организация,
         * имеющая счет в банке "Платина" (БИК 044525931)
         */
        $searchModel = new EdmPayerAccountSearch();
        $isPlatinaBik = $searchModel->searchBikInUserTerminals('044525931');

        // Сформировать контент для модального окна
        $modalContent = $this->renderPartial(
            '@backend/views/site/expiringCertsNotificationModal',
            [
                'certs' => $expiredCerts,
                'isPlatinaBik' => $isPlatinaBik
            ]
        );

        // Вернуть контент
        return compact('modalContent');
    }

    /**
     * Метод получает документы типа "письмо в банк" для пользователя
     * для отображения на странице после успешного входа
     * @return type
     */
    public function actionGetPostLoginNotificationModalLetters()
    {
        // Контент модального окна для возврата
        $modalContent = [];
        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Найти в ДБ входящие документы с типом "письмо в банк"
        $bankLetterModel = new BankLetterSearch();
        $dataProvider = $bankLetterModel->search([
            'BankLetterSearch' => [
                'direction' => 'IN',
            ],
        ]);

        // Для всех найденных документов
        foreach ($dataProvider->getModels() as $document) {
            // Если документ не был просмотрен и его тип auth.026
            if ($document->viewed != 1 && $document->type == 'auth.026') {
                try {
                    // Создать модель для просмотра письма в банк из данных модели документа
                    $letter = BankLetterViewModel::create($document);
                    // Если данный документ имеет пометку важности IMPT
                    if ($letter->tp == 'IMPT') {
                        // Добавить в контент модального окна информацию о документе
                        $modalContent[] = $this->renderPartial(
                            '@addons/edm/views/bank-letter/_viewModal.php',
                            compact('document', 'letter')
                        );
                        // Пометить документ как просмотренный
                        $document->viewed = 1;
                        // Сохранить изменения документа в БД
                        $document->save(false, ['viewed']);
                    }
                } catch (\Exception $ex) {
                    // В случае ошибки записать в лог информацию об ошибке
                    \Yii::info("Letter {$document->id} is malformed: " . $ex->getMessage());
                }
            }
        }

        // Вернуть контент для модального окна
        return compact('modalContent');
    }

    /**
     * Метод завершает сеанс пользователя и делает переход на страницу входа
     * @return Response
     */
    public function actionRedirectIfUserInactive()
    {
        // Если пользователь осуществил вход
        if (!Yii::$app->user->isGuest) {
            // сбросить сессию пользователя
            Yii::$app->user->logout();
        }
        
        // Поместить в сессию флаг сообщения, что пользователь был неактивен
        // и его сеанс был принудительно завершен
        Yii::$app->session->addFlash('error', Yii::t('app', 'You were inactive for a while. For security reasons your session was closed. If you want to continue, please log in again.'));

        // Перенаправить на страницу логина
        return $this->redirect('/login');
    }
}

