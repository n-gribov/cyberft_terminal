<?php

namespace backend\controllers;

use addons\edm\models\EdmPayerAccount;
use addons\edm\models\EdmPayerAccountUser;
use backend\models\forms\UploadUserAuthCertForm;
use backend\models\search\UserSearch;
use backend\services\UserAuthCertService;
use common\base\Controller;
use common\commands\CommandAcceptAR;
use common\helpers\UserHelper;
use common\models\CommonUserExt;
use common\models\form\UserServicesSettingsForm;
use common\models\Terminal;
use common\models\User;
use common\models\UserAuthCert;
use common\models\UserAuthCertSearch;
use common\models\UserTerminal;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class UserController extends Controller
{
    /**
     * @var UserAuthCertService
     */
    private $authCertService;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['profile'],
                        'roles'   => ['@'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['commonUsers'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'status-cert-management' => ['post'],
                    'set-services-permissions' => ['post'],
                    'set-additional-services-access' => ['post'],
                ],
            ],
        ];
    }

    public function __construct($id, $module, UserAuthCertService $authCertService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->authCertService = $authCertService;
    }

    /**
     * Метод обрабатывает страницу индекса
     * со списком пользователей
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Очистка кэша обратной ссылки для настроек пользователя
        if (Yii::$app->cache->exists('user/settings-' . Yii::$app->session->id)) {
            Yii::$app->cache->delete('user/settings-' . Yii::$app->session->id);
        }

        // Вывести страницу
        return $this->render(
            'index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]
        );
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $activeTab = 'common')
    {
        $tabMode = Yii::$app->request->get('tabMode');

        // Установка кэша для обратной ссылки настроек пользователя
        if (empty($tabMode)) {
            $referrer = Yii::$app->request->referrer;

            // Не перезаписываем ссылку,
            // если вернулись со страницы редактирования пользователя
            if (stristr($referrer, 'update') === false) {
                Yii::$app->cache->set(
                    'user/settings-' . Yii::$app->session->id,
                    $referrer
                );
            }
        }

        // Получить из БД пользователя с указанным id
        $model = $this->findModel($id);

        // Возможность просмотра страницы пользователя
        if (!UserHelper::userProfileAccess($model)) {
            throw new ForbiddenHttpException;
        }
	
        $certSearchModel = new UserAuthCertSearch();
        $certDataProvider = $certSearchModel->search(Yii::$app->request->queryParams, $id);
        
        $uploadCertForm = new UploadUserAuthCertForm();
        $servicesSettingsForm = new UserServicesSettingsForm(['user' => $model]);

        // Вывести страницу
        return $this->render('view', [
            'model' => $model,
            'certDataProvider' => $certDataProvider,
            'certSearchModel' => $certSearchModel,
            'activeTab' => $activeTab,
            'accounts' => UserHelper::getViewableUserAccounts($model),
            'servicesSettingsForm' => $servicesSettingsForm,
            'additionalServiceAccess' => UserHelper::getUserAdditionalServiceAccess($model),
            'uploadCertForm' => $uploadCertForm,
        ]);
    }

    public function actionProfile()
    {
        return $this->actionView(Yii::$app->user->identity->id);
    }

    public function actionSetAccountAccess()
    {
        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        $userId = Yii::$app->request->post('userId');
        $accountId = Yii::$app->request->post('accountId');
        $action = Yii::$app->request->post('action');

        // Получить из БД пользователя с указанным id
        $user = $this->findModel($userId);
        if (!UserHelper::userProfileAccess($user, true)) {
            throw new ForbiddenHttpException;
        }

        $account = EdmPayerAccount::findOne($accountId);
        if ($account === null) {
            throw new NotFoundHttpException("Account $accountId is not found");
        }

        /** @var User $adminIdentity */
        $adminIdentity = Yii::$app->user->identity;
        if ($adminIdentity->role !== User::ROLE_ADMIN) {
            $adminTerminalsIds = UserTerminal::getUserTerminalIndexes($adminIdentity->id);
            $adminHasAccessToAccount = in_array($account->edmDictOrganization->terminalId, $adminTerminalsIds);
            if (!$adminHasAccessToAccount) {
                throw new ForbiddenHttpException("User {$adminIdentity->email} cannot grant access to account {$account->number} to user {$user->email}");
            }
        }

        Yii::info("Will change account access, admin: {$adminIdentity->email}, user: {$user->email}, account: {$account->number}, action: $action");
        switch ($action) {
            case 'grant':
            case 'grantSignature':
                EdmPayerAccountUser::createOrUpdate($userId, $accountId, true);
                break;
            case 'revoke':
                EdmPayerAccountUser::deleteAccountFromUser($userId, $accountId);
                break;
            case 'revokeSignature':
                EdmPayerAccountUser::createOrUpdate($userId, $accountId, false);
                break;
            default:
                Yii::warning("Unsupported action: $action");
        }

        UserHelper::sendUserToSecurityOfficersAcceptance($userId);

        return [
            'isGranted' => EdmPayerAccountUser::isUserAllowAccount($userId, $accountId),
            'canSignDocuments' => EdmPayerAccountUser::userCanSingDocuments($userId, $accountId),
            'flashes' => Yii::$app->session->getAllFlashes(),
        ];
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $dataProvider = [];

        // Если данные модели успешно загружены из формы в браузере
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {

            // Получаем выбранные пользователем терминалы из кэша
            if (Yii::$app->cache->exists('new-user-data-provider-' . Yii::$app->session->id)) {
                $dataProviderArray = Yii::$app->cache->get('new-user-data-provider-' . Yii::$app->session->id);
            } else {
                $dataProviderArray = [];
            }

            if (!$model->validate()) {
                // Поместить в сессию флаг сообщения с информацией об ошибках в модели
                Yii::$app->session->addFlash('error', join('<br/>', $model->getFirstErrors()));
            } else if (count($dataProviderArray) == 0) {
                // Если не указано ни одного терминала, добавить в модель ошибку
                $model->addError('', Yii::t('app/user', 'Not specified available terminals'));
                // Поместить в сессию флаг сообщения с информацией об ошибках в модели
                Yii::$app->session->addFlash('error', join('<br/>', $model->getFirstErrors()));
            } else {
                $password = $model->email;

                // Ответственный за нового пользователя тот, кто создает его в данный момент,
                // если в форме не указано иначе
                if (empty($model->ownerId)) {
                    $model->ownerId = Yii::$app->user->id;
                }

                $model->setPassword($password);

                // Если модель успешно сохранена в БД
                if ($model->save()) {
                    foreach($dataProviderArray as $terminalId => $terminalInfo) {
                        $newUserTerminal = new UserTerminal();
                        $newUserTerminal->userId = $model->id;
                        $newUserTerminal->terminalId = $terminalId;
                        // Сохранить модель в БД
                        $newUserTerminal->save();
                    }
                    // Поместить в сессию флаг сообщения об успешном создании пользователя
                    Yii::$app->session->setFlash('success', Yii::t('app/user', 'New user created with temporary password {password}. Next, configure available for user accounts and rights and activate this user', ['password' => $password]));

                    // После создания пользователя сделать доступными все счета
                    if (User::ROLE_USER == $model->role) {
                        UserHelper::setNewUserAccounts($model->id);
                    }

                    // Зарегистрировать событие создания нового пользователя в модуле мониторинга
                    Yii::$app->monitoring->extUserLog('CreateUser', ['newUserId' => $model->id]);

                    // Перенаправить на страницу просмотра
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        // Проверяем необходимость предустановленного терминала
        $addTerminalId = Yii::$app->request->get('addTerminalId');

        if ($addTerminalId) {
            $dataProviderArray = [];

            // Получаем терминал по его id
            $query = Terminal::find();

            // Доп. администратор может предустанавливать только доступные ему терминалы
            $userIdentity = Yii::$app->user->identity;

            if ($userIdentity->role == User::ROLE_ADDITIONAL_ADMIN) {
                $userTerminals = UserTerminal::getUserTerminalIds($userIdentity->id);

                if (ArrayHelper::keyExists($addTerminalId, $userTerminals)) {
                    $query->where(['id' => $addTerminalId]);
                } else {
                    $query->where('0=1');
                }
            } else {
                $query->where(['id' => $addTerminalId]);
            }

            $terminal = $query->one();

            // Если терминал найден, то формируем array data provider
            // для передачи в представление
            if ($terminal) {
                // Добавляем в массив дата-провайдера новый элемент
                $dataProviderArray[$terminal->id] = [
                    'id' => $terminal->id,
                    'terminalId' => $terminal->terminalId,
                    'terminalName' => $terminal->title
                ];

                $dataProvider = new ArrayDataProvider([
                    'allModels' => $dataProviderArray,
                ]);

                // Записываем массив в кэш
                Yii::$app->cache->set('new-user-data-provider-' . Yii::$app->session->id, $dataProviderArray);
            }
        }

        // Вывести страницу
        return $this->render('create', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        // Получить из БД пользователя с указанным id
        $model = $this->findModel($id);
                
        // Возможность редактирования данных пользователя
        if (!UserHelper::userProfileAccess($model, true)) {
            throw new ForbiddenHttpException;
        }

        // Если данные модели успешно загружены из формы в браузере
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())){
            if (!UserHelper::canUpdateProfile($id)) {
                // Поместить в сессию флаг сообщения о запрете редактирования пользователя
                Yii::$app->session->addFlash('error', Yii::t('app/user', 'Editing user is not allowed'));

                // Перенаправить на страницу просмотра
                return $this->redirect(['view', 'id' => $model->id]);
            }

            if ($model->validate()) {
                if ($model->status == User::STATUS_ACTIVE && Yii::$app->request->post('flushPassword')) {
                    
                   /*
                    * (CYB-4560) Если администратор сбрасывает пароль сам себе через интерфейс,
                    * то новый пароль не генерим, вместо этого перенаправляем на форму ввода нового пароля
                    */
                    if ($model->id === Yii::$app->user->identity->id) {
                        // Перенаправить на страницу установки пароля
                        return $this->redirect('/site/set-password');
                    } else {
                        $newPass = substr(md5(mt_rand(0, 99999999999)), 0, 16);
                        $model->setPassword($newPass);

                        // Поместить в сессию флаг сообщения об успешном сбросе пароля
                        Yii::$app->session->addFlash('success',
                            Yii::t(
                                'app/user',
                                'Password was reset at your request, your new password: "{newPass}"',
                                ['newPass' => $newPass])
                        );                  
                    }
                }

                if ($model->disableTerminalSelect) {
                    $model->terminalId = null;
                }

                // Если модель успешно сохранена в БД
                if ($model->save()) {
                    // Зарегистрировать событие редактирования пользователя в модуле мониторинга
                    Yii::$app->monitoring->extUserLog('EditUser', ['newUserId' => $id]);
                    UserHelper::sendUserToSecurityOfficersAcceptance($id);

                    // Поместить в сессию флаг сообщения об успешном сохранении настроек
                    Yii::$app->session->addFlash('success', Yii::t('app/user', 'Settings updated'));
                    // Перенаправить на страницу просмотра
                    return $this->redirect(['view', 'id' =>  $model->id]);
                }
            }
        }

        // Вывести страницу
        return $this->render('update', ['model' => $model]);
    }

    public function actionSetServicesPermissions($id)
    {
        // Получить из БД пользователя с указанным id
        $user = $this->findModel($id);

        if (!UserHelper::userProfileAccess($user, true)) {
            throw new ForbiddenHttpException;
        }

        $settingsForm = new UserServicesSettingsForm(['user' => $user]);
        // Если данные модели успешно загружены из формы в браузере
        if ($settingsForm->load(Yii::$app->request->post())) {
            // Сохранить модель в БД
            $settingsForm->save();
        }

        // Поместить в сессию флаг сообщения об успешном изменении настроек
        Yii::$app->session->addFlash('success', Yii::t('app/user', 'Settings updated'));

        // Перенаправить на страницу просмотра
        return $this->redirect(['view', 'id' => $id, 'tabMode' => 'services']);
    }

    public function actionSetAdditionalServicesAccess($id)
    {
        // Получить из БД пользователя с указанным id
        $user = $this->findModel($id);

        if (!UserHelper::userProfileAccess($user, true)) {
            throw new ForbiddenHttpException;
        }

        $servicesAccess = Yii::$app->request->post('serviceAccess', []);
        foreach (CommonUserExt::getServices() as $serviceId) {
            $model = CommonUserExt::findOne(['userId' => $user->id, 'type' => $serviceId]);
            if (!$model) {
                $model = new CommonUserExt();
                $model->userId = $user->id;
                $model->type = $serviceId;
            }

            $model->canAccess = (in_array($serviceId, $servicesAccess) ? 1 : 0);
            // Сохранить модель в БД
            $model->save();
        }

        // Поместить в сессию флаг сообщения об успешном изменении настроек
        Yii::$app->session->addFlash('success', Yii::t('app/user', 'Settings updated'));

        // Перенаправить на страницу просмотра
        return $this->redirect(['view', 'id' => $id, 'tabMode' => 'services']);
    }

    /**
     * Marks User model status as deleted.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        // Получить из БД пользователя с указанным id
        $model = $this->findModel($id);

        // Возможность удаления пользователя
        if (!UserHelper::userProfileAccess($model, true) || Yii::$app->user->id == $id) {
            throw new ForbiddenHttpException;
        }

        $model->status = User::STATUS_DELETED;
        if (!$model->save()) {
            // Поместить в сессию флаг сообщения об ошибке удаления пользователя
            Yii::$app->session->addFlash('error', Yii::t('app/user', 'Failed to delete user'));
        } else {
            // Обнулить доступы к счетам
            EdmPayerAccountUser::deleteAccountFromUser($model->id);
            // Поместить в сессию флаг сообщения о пометке пользователя удалённым
            Yii::$app->session->addFlash('success', Yii::t('app/user', 'User marked as deleted'));

            // Зарегистрировать событие удаления пользователя в модуле мониторинга
            Yii::$app->monitoring->extUserLog('DeleteUser', ['newUserId' => $id]);
        }

        // Перенаправить на страницу индекса
        return $this->redirect('index');
    }

    /**
     * Restored User model with deleted status
     * If restore is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionRestore($id)
    {
        if (in_array(Yii::$app->user->identity->role, [User::ROLE_ADMIN, User::ROLE_ADDITIONAL_ADMIN])) {
            // Получить из БД пользователя с указанным id
            $model = $this->findModel($id);
            $model->status = User::STATUS_INACTIVE;

            // Дополнительный администратор может
            // восстанавливать только доступных ему пользователей
            if (Yii::$app->user->identity->role == User::ROLE_ADDITIONAL_ADMIN && $model->ownerId != Yii::$app->user->identity->id) {
                throw new ForbiddenHttpException;
            }

            if (!$model->save()) {
                // Поместить в сессию флаг сообщения об ошибке восстановления пользователя
                Yii::$app->session->addFlash('error', Yii::t('app/user', 'Failed to restore user'));
            } else {
                // Поместить в сессию флаг сообщения об успешном восстановлении пользователя
                Yii::$app->session->addFlash('success', Yii::t('app/user', 'User restored but needs to be activated again'));
                // Зарегистрировать событие удаления пользователя в модуле монитоинга
                Yii::$app->monitoring->extUserLog('RestoreUser', ['newUserId' => $id]);
            }
        } else {
            // Поместить в сессию флаг сообщения о запрете на выполнение операции
            Yii::$app->session->setFlash('error', Yii::t('app/user', 'Permission denied for this operation!'));
        }

        // Перенаправить на страницу индекса
        return $this->redirect('index');
    }


    public function actionActivate($id)
    {
        if (
            in_array(
                Yii::$app->user->identity->role,
                [User::ROLE_ADMIN, User::ROLE_ADDITIONAL_ADMIN, User::ROLE_RSO, User::ROLE_LSO]
            )
        ) {
            $condition = ['id' => $id, 'status' => User::STATUS_INACTIVE];

            // Дополнительный администратор может
            // активировать только доступных ему пользователей
            if (Yii::$app->user->identity->role == User::ROLE_ADDITIONAL_ADMIN) {
               $condition['ownerId'] = Yii::$app->user->identity->id;
            }

            $model = User::findOne($condition);

            if ($model) {
                UserHelper::addActivateCommand($id);
                // Зарегистрировать событие активации пользователя
                Yii::$app->monitoring->extUserLog('ActivateUser', ['newUserId' => $id]);
            }
        } else {
            // Поместить в сессию флаг сообщения о запрете на выполнение операции
            Yii::$app->session->setFlash('error', Yii::t('app/user', 'Permission denied for this operation!'));
        }

        // Перенаправить на страницу просмотра
        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionBlock($id)
    {
        if (
            in_array(
                Yii::$app->user->identity->role,
                [User::ROLE_ADMIN, User::ROLE_ADDITIONAL_ADMIN, User::ROLE_RSO, User::ROLE_LSO]
            )
        ) {
            $condition = ['id' => $id, 'status' => User::STATUS_ACTIVE];

            // Дополнительный администратор может
            // активировать только доступных ему пользователей
            if (Yii::$app->user->identity->role == User::ROLE_ADDITIONAL_ADMIN) {
                $condition['ownerId'] = Yii::$app->user->identity->id;
            }

            $model = User::findOne($condition);

            if ($model) {
                $model->status = User::STATUS_INACTIVE;
                // Сохранить модель в БД
                $model->save();
                // Поместить в сессию флаг сообщения ою успешной блокировке пользователя
                Yii::$app->session->setFlash('success', Yii::t('app/user', 'User is blocked'));
            }

        } else {
            // Поместить в сессию флаг сообщения о запрете на выполнение операции
            Yii::$app->session->setFlash('error', Yii::t('app/user', 'Permission denied for this operation!'));
        }

        // Перенаправить на страницу просмотра
        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionSecureOfficerApprove($id)
    {
        try {
            if (!Yii::$app->user->identity->canActivateApprove($id)) {
                throw new \Exception('Permission denied for this operation!');
            } else {
                if (!$model = User::findOne(['id' => $id])) {
                    throw new \Exception('User not found!');
                } else {
                    if (User::STATUS_ACTIVATING !== $model->status) {
                        throw new \Exception('Operation is not available for user in current status');
                    } else {
                        if (!$command = Yii::$app->commandBus->findCommandId('UserActivate',
                            [
                                'status' => 'forAcceptance',
                                'entity' => 'user',
                                'entityId' => $id,
                            ])
                        ) {
                            throw new \Exception('Error! Command not found!');
                        } else {
                            if (!Yii::$app->commandBus->addCommandAccept($command->id, ['acceptResult' => CommandAcceptAR::ACCEPT_RESULT_ACCEPTED])) {
                                throw new \Exception('Operation failure!');
                            } else {
                                // Поместить в сессию флаг сообщения об успешном выполнении операции
                                Yii::$app->session->setFlash('success', Yii::t('app/user', 'Operation complete successful'));
                            }
                        }
                    }
                }
            }
        } catch(\Exception $ex) {
            // Поместить в сессию флаг сообщения об ошибке
            Yii::$app->session->setFlash('error', Yii::t('app/user', $ex->getMessage()));
        }

        // Перенаправить на страницу просмотра
        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionSecureOfficerSettingsApprove($id)
    {
        try {
            if (!Yii::$app->user->identity->canSettingApprove($id)) {
                throw new \Exception('Permission denied for this operation!');
            } else {
                if (!$model = User::findOne(['id' => $id])) {
                    throw new \Exception('User not found!');
                } else {
                    if (User::STATUS_APPROVE !== $model->status) {
                        throw new \Exception('Operation is not available for user in current status');
                    } else {
                        if (!$command = Yii::$app->commandBus->findCommandId('UserSettingApprove',
                            [
                                'status' => 'forAcceptance',
                                'entity' => 'user',
                                'entityId' => $id,
                            ])
                        ) {
                            throw new \Exception('Error! Command not found!');
                        } else {
                            if (!Yii::$app->commandBus->addCommandAccept($command->id, ['acceptResult' => CommandAcceptAR::ACCEPT_RESULT_ACCEPTED])) {
                                throw new \Exception('Operation failure!');
                            } else {

                                // Зарегистрировать событие подтверждения настроек пользователя в модуле мониторинга
                                Yii::$app->monitoring->extUserLog('SecureOfficerUserApprove', ['approveUserId' => $id]);
                                // Поместить в сессию флаг сообщения об успешном выполнении операции
                                Yii::$app->session->setFlash('success', Yii::t('app/user', 'Operation complete successful'));
                            }
                        }
                    }
                }
            }
        } catch(\Exception $ex) {
            // Поместить в сессию флаг сообщения об ошибке
            Yii::$app->session->setFlash('error', Yii::t('app/user', $ex->getMessage()));
        }

        // Перенаправить на страницу просмотра
        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionAddTerminal()
    {
        if (!Yii::$app->request->isAjax) {
            throw new BadRequestHttpException();
        }

        // Данные из ajax-запроса
        $data = Yii::$app->request->post();
        $userId = $data['userId'];
        $terminalId = $data['terminalId'];

        $user = User::findOne($userId);
        $adminIdentity = Yii::$app->user->identity;

        if ($user === null) {
            throw new NotFoundHttpException("User $userId does not exist");
        }

        if (!UserHelper::userProfileAccess($user, true)) {
            throw new ForbiddenHttpException("User {$adminIdentity->email} has no access to user {$user->email}");
        }

        if ($userId && $terminalId !== 'null') {
            // Добавляем запись в таблицу терминалов пользователей
            if ($terminalId) {
                if ($adminIdentity->role !== User::ROLE_ADMIN) {
                    $adminTerminalsIds = UserTerminal::getUserTerminalIndexes($adminIdentity->id);
                    $adminHasAccessToTerminal = in_array($terminalId, $adminTerminalsIds);
                    if (!$adminHasAccessToTerminal) {
                        throw new ForbiddenHttpException("User {$adminIdentity->email} cannot grant access to terminal $terminalId to user {$user->email}");
                    }
                }

                $terminal = new UserTerminal();
                $terminal->userId = $userId;
                $terminal->terminalId = $terminalId;
                // Сохранить модель в БД
                $terminal->save();

                // Добавление доступа пользователю к счетам по добавленным терминалам
                UserHelper::addUserAccountsAllowByTerminal($userId, $terminalId);

                $userPrimaryTerminal = $terminalId;
            } else {

                // Если не указан id конкретного терминала,
                // то добавляем в список все доступные id терминалов
                $query = Terminal::find()->where(['status' => Terminal::STATUS_ACTIVE]);

                // Для дополнительного администратора только доступyые ему терминалы
                if ($adminIdentity->role !== User::ROLE_ADMIN) {
                    $adminTerminals = UserTerminal::getUserTerminalIds($adminIdentity->id);

                    if ($adminTerminals) {
                        $query->where(['id' => array_keys($adminTerminals)]);
                    }
                }

                $terminals = $query->all();

                $userPrimaryTerminal = null;

                foreach($terminals as $terminal) {
                    $newTerminal = new UserTerminal();
                    $newTerminal->userId = $userId;
                    $newTerminal->terminalId = $terminal->id;
                    // Сохранить модель в БД
                    $newTerminal->save();

                    // Добавление доступа пользователю к счетам по добавленным терминалам
                    UserHelper::addUserAccountsAllowByTerminal($userId, $terminal->id);

                    if (!$userPrimaryTerminal) {
                        $userPrimaryTerminal = $terminal->id;
                    }
                }
            }

            UserHelper::sendUserToSecurityOfficersAcceptance($userId);

            // Добавление доступа пользователю к ресурсам по добавленным терминалам
            $user = User::findOne($userId);

            if ($user && empty($user->terminalId) && !$user->disableTerminalSelect) {
                $user->terminalId = $userPrimaryTerminal;
                $user->save(null);
            }
        }
        return $this->renderUserTerminals($userId);
    }

    public function actionDeleteTerminal()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            // Данные из ajax-запроса
            $userId = $data['userId'];
            $terminalId = $data['terminalId'];

            if ($userId && $terminalId) {

                // Находим и удаляем запись из таблицы терминалов пользователей
                UserTerminal::deleteAll(['userId' => $userId, 'terminalId' => $terminalId]);

                // Удаляем доступ к счетам пользователям, которые привязаны к терминалу
                UserHelper::deleteUserAccountsAllowByTerminal($userId, $terminalId);

                // Если у пользователя был выбран удаляемый терминал, сбрасываем доступ к нему
                $user = User::findOne($userId);

                if ($user && $user->terminalId == $terminalId) {
                    $user->terminalId = null;
                    $user->save(null);
                }

                UserHelper::sendUserToSecurityOfficersAcceptance($userId);

                // Вывод списка терминалов пользователя
                return $this->renderUserTerminals($userId);
            }

        }
    }

    /**
     * Метод для добавления терминалов в список
     * в процессе создания нового пользователя
     */
    public function actionAddTerminalNewUser()
    {
        // Если есть данные в кэше, получаем их
        if (Yii::$app->cache->exists('new-user-data-provider-' . Yii::$app->session->id)) {
            $dataProviderArray = Yii::$app->cache->get('new-user-data-provider-' . Yii::$app->session->id);
        } else {
            // иначе создаем новый кэш
            $dataProviderArray = [];
            Yii::$app->cache->set('new-user-data-provider-' . Yii::$app->session->id, $dataProviderArray);
        }

        // Если не пришел id, то считаем, что нужно добавить все терминалы

        // Получаем параметры из get-запроса
        $post = Yii::$app->request->post();

        if (isset($post['terminalId']) && $post['terminalId']) {
            $terminalId = $post['terminalId'];

            // Получаем терминал по его id
            $terminal = Terminal::findOne($terminalId);

            // Если терминал найден, то формируем array data provider
            // для передачи в представление
            if ($terminal) {
                // Добавляем в массив дата-провайдера новый элемент
                $dataProviderArray[$terminal->id] = [
                    'id' => $terminal->id,
                    'terminalId' => $terminal->terminalId,
                    'terminalName' => $terminal->title
                ];
            }
        } else {
            // Получаем все активные терминалы
            $query = Terminal::find()->where(['status' => Terminal::STATUS_ACTIVE]);

            // Для дополнительного администратора только доступные ему терминалы
            $userIdentity = Yii::$app->user->identity;

            if ($userIdentity->role == User::ROLE_ADDITIONAL_ADMIN) {
                $userTerminals = UserTerminal::getUserTerminalIds($userIdentity->id);

                if ($userTerminals) {
                    $query->where(['id' => array_keys($userTerminals)]);
                }
            }

            $terminals = $query->asArray()->all();

            foreach($terminals as $terminal) {
                // Добавляем в массив дата-провайдера новый элемент
                $dataProviderArray[$terminal['id']] = [
                    'id' => $terminal['id'],
                    'terminalId' => $terminal['terminalId'],
                    'terminalName' => $terminal['title']
                ];
            }
        }

        // Записываем массив в кэш
        Yii::$app->cache->set('new-user-data-provider-' . Yii::$app->session->id, $dataProviderArray);

        // Формируем дата-провайдер для передачи в представление
        $dataProvider = new ArrayDataProvider([
            'allModels' => $dataProviderArray,
        ]);

        // Формируем представление для возврата в форму
        $form = ActiveForm::begin();
        ActiveForm::end();

        $html = $this->renderAjax('terminals/_userTerminalListNewUser', [
            'model' => new User(),
            'form' => $form,
            'dataProvider' => $dataProvider,
        ]);

        return $html;
    }

    /**
     * Удаление термила в режиме создания нового пользователя
     */
    public function actionDeleteTerminalNewUser()
    {
        // Получаем параметры из get-запроса
        $post = Yii::$app->request->post();
        $terminalId = $post['terminalId'];

        // Если есть данные в кэше, получаем их
        if (Yii::$app->cache->exists('new-user-data-provider-' . Yii::$app->session->id)) {
            $dataProviderArray = Yii::$app->cache->get('new-user-data-provider-' . Yii::$app->session->id);

            // Находим терминал из кэша и удаляем его
            if (isset($dataProviderArray[$terminalId])) {
                unset($dataProviderArray[$terminalId]);
            }
        } else {
            // иначе создаем новый кэш
            $dataProviderArray = [];
        }

        // Записываем массив в кэш
        Yii::$app->cache->set('new-user-data-provider-' . Yii::$app->session->id, $dataProviderArray);

        // Формируем дата-провайдер для передачи в представление
        $dataProvider = new ArrayDataProvider([
            'allModels' => $dataProviderArray,
        ]);

        // Формируем представление для возврата в форму
        $form = ActiveForm::begin();
        ActiveForm::end();

        $html = $this->renderAjax('terminals/_userTerminalListNewUser', [
            'model' => new User(),
            'form' => $form,
            'dataProvider' => $dataProvider,
        ]);

        return $html;
    }

    protected function renderUserTerminals($userId)
    {
        // Получить из БД пользователя с указанным id
        $model = $this->findModel($userId);

        $form = ActiveForm::begin();
        ActiveForm::end();

        $html = $this->renderAjax('terminals/_userTerminalList', [
            'model' => $model,
            'form' => $form,
            'userId' => $userId
        ]);

        return $html;
    }

    /**
     * Метод ищет модель пользвоателя в БД по первичному ключу.
     * Если модель не найдена, выбрасывается исключение HTTP 404
     */
    protected function findModel($id)
    {
        // Найти пользвателя в БД по указанному id
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app/user', 'Requested page not found'));
        }
    }

    /**
     * Метод ищет модель сертификата в БД по первичному ключу.
     * Если модель не найдена, выбрасывается исключение HTTP 404
     */
    protected function findCertModel($id)
    {
        // Найти в БД модель сертификата по указанному id
        if (($model = UserAuthCert::findOne($id)) !== null) {
            return $model;
        } else {
                throw new NotFoundHttpException(Yii::t('app/user', 'Requested page not found'));
        }
    }

}