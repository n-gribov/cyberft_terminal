<?php

namespace addons\swiftfin\controllers;

use addons\swiftfin\models\SwiftFinUserExt;
use addons\swiftfin\models\SwiftFinUserExtAuthorization;
use common\base\BaseUserExtController;
use common\commands\CommandAR;
use common\helpers\Currencies;
use common\helpers\UserHelper;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class UserExtController extends BaseUserExtController
{
    /**
     * @var User $_user User
     */
    private $_user;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
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
                    'update' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Get user extened data
     *
     * @param integer $id User ID
     * @return mixed
     */
    public function actionIndex($id)
    {
        // Пользователю-администратору нельзя
        // давать возможность выбирать swiftfin-роль

        // Возвращаем ошибку,
        // если id пользователя не передан
        if (empty($id)) {
            throw new \yii\web\HttpException('404');
        }

        $user = User::findOne($id);

        // Возвращаем ошибку,
        // если пользователь не найден
        if (empty($id)) {
            throw new \yii\web\HttpException('404');
        }

        // Возвращаем ошибку,
        // если пользователь - админ
        if ($user->role != User::ROLE_USER) {
            throw new \yii\web\HttpException('404');
        }

        $extModel = $this->getUserExtModel($id);

        $dataProvider = new ActiveDataProvider([
            'query' => SwiftFinUserExtAuthorization::find()->where(['userExtId' => $extModel->id])
        ]);

        $model = new SwiftFinUserExtAuthorization(['userExtId' => $extModel->id]);

        // Вывести страницу
        return $this->render('index', [
            'model'          => $model,
            'extModel'       => $extModel,
            'dataProvider'   => $dataProvider,
            'currencySelect' => Currencies::getCodeLabels(),
            'docTypeSelect'  => $this->getDocumentTypes(),
            'serviceName'    => $this->getServiceName()
        ]);
    }

    public function actionUpdateRole()
    {
        $id = (int) Yii::$app->request->post('id');
        $extModel = SwiftFinUserExt::findOne($id);

        if (empty($extModel)) {
            // Поместить в сессию флаг сообщения о ненайденном пользователе
            Yii::$app->session->setFlash('error', Yii::t('app/user', 'Unknown user - data was not updated'));
            // Перенаправить на страницу индекса
            return $this->redirect(['/user']);
        }

        if (UserHelper::canUpdateProfile($extModel->userId)) {

            if (!empty($extModel)) {
                // Загрузить данные модели из формы в браузере
                $extModel->load(Yii::$app->request->post());
                // Сохранить модель в БД
                $extModel->save();
                if ($extModel->role == SwiftFinUserExt::ROLE_PREAUTHORIZER) {
                    $result = SwiftFinUserExt::find()
                        ->where(['role' => SwiftFinUserExt::ROLE_AUTHORIZER])->one();
                    if (empty($result)) {
                        // Поместить в сессию флаг сообщения о необходимости авторизующего пользователя
                        Yii::$app->session->setFlash(
                            'warning',
                            Yii::t(
                                'app/user',
                                'At least one authorizer is needed for preliminary authorizers to be effective'
                            )
                        );
                    }

                    UserHelper::sendUserToSecurityOfficersAcceptance($extModel->userId);
                }

                // Зарегистрировать событие смены swift-роли пользователя в модуле мониторинга
                Yii::$app->monitoring->extUserLog('EditSwiftUserSettings', ['id' => $extModel->userId]);
            }
        } else {
            // Поместить в сессию флаг сообщения о запрете редактирования пользователя
            Yii::$app->session->addFlash('error', Yii::t('app/user', 'Editing user is not allowed'));
        }

        // Перенаправить на страницу индекса
        return $this->redirect(['index', 'id' => $extModel->userId]);
    }

    /**
     * Update role settings
     *
     * @return mixed
     */
    public function actionUpdateRoleSettings()
    {
        $model = new SwiftFinUserExtAuthorization();
        // Загрузить данные модели из формы в браузере
        $model->load(Yii::$app->request->post());
        $extModel = SwiftFinUserExt::findOne($model->userExtId);

        if (empty($extModel)) {
            // Поместить в сессию флаг сообщения о ненайденном пользователе
            Yii::$app->session->setFlash('error', Yii::t('app/user', 'Unknown user - data was not updated'));

            // Перенаправить на страницу индекса
            return $this->redirect(['/user']);
        }

        if (UserHelper::canUpdateProfile($extModel->userId)) {
            if (!$model->validate()) {
                $errors = $model->getErrors();
                foreach ($errors as $attr => $message) {
                    // Поместить в сессию флаг сообщения об ошибке
                    Yii::$app->session->setFlash(
                        'error',
                        $model->getAttributeLabel($attr) . ': ' . implode('<br>', $message)
                    );
                }
            } else {
                $result = SwiftFinUserExtAuthorization::findOne([
                    'userExtId' => $model->userExtId,
                    'docType' => $model->docType,
                    'currency' => $model->currency,
                    'minSum' => (int)$model->minSum,
                    'maxSum' => (int)$model->maxSum
                ]);

                if (empty($result)) {
                    // Если модель успешно сохранена в БД
                    if ($model->save()) {
                        UserHelper::sendUserToSecurityOfficersAcceptance($extModel->userId);
                    } else {
                        // Поместить в сессию флаг сообщения об ошибке сохранения настроек пользователя
                        Yii::$app->session->setFlash('error', Yii::t('app/user', 'Failed to save user settings'));
                    }
                } else {
                    // Поместить в сессию флаг сообщения об уже заданных условиях
                    Yii::$app->session->setFlash('info', Yii::t('app/user', 'These conditions are already defined'));
                }
            }
        } else {
            // Поместить в сессию флаг сообщения о запрете редактирования пользователя
            Yii::$app->session->addFlash('error', Yii::t('app/user', 'Editing user is not allowed'));
        }

        // Перенаправить на страницу индекса
        return $this->redirect([
            'index',
            'id' => $extModel->userId,
            'tabMode' => Yii::$app->request->post('tabMode')
        ]);
    }

    /**
     * Delete role setting
     *
     * @param integer $id      ID
     * @param string  $tabMode Tab mode
     * @return mixed
     */
    public function actionDeleteRoleSetting($userId, $id, $tabMode = '')
    {
        $model = SwiftFinUserExtAuthorization::findOne($id);
        if (!empty($model)) {
            $userExt = SwiftFinUserExt::findOne($model->userExtId);
            if (!empty($userExt) && $userExt->userId == $userId) {
                // Удалить документ из БД
                $model->delete();
                $this->setApproveCommand($userId);
            }
        }

        // Перенаправить на страницу индекса
        return $this->redirect(['index', 'id' => $userId, 'tabMode' => $tabMode]);
    }

    public function getDocumentTypes()
    {
        $documentTypes = array_keys(Yii::$app->registry->getModuleTypes($this->module->serviceId));

        return array_combine($documentTypes, $documentTypes);
    }

    /**
     * Set user ID
     *
     * @param integer $userId User ID
     * @return boolean
     */
    protected function setApproveCommand($userId)
    {
        $this->_user = User::findOne($userId);
        if (empty($this->_user)) {
            return false;
        }

        if (!User::canUseSecurityOfficers()) {
            return true;
        }

        return $this->addCommand($userId);
    }

    /**
     * Add command
     *
     * @param integer $userId User ID
     * @return boolean
     */
    protected function addCommand($userId)
    {
        $params = [
            'code'     => 'UserSettingApprove',
            'entity'   => 'user',
            'entityId' => $userId,
            'userId'   => Yii::$app->user->id,
        ];

        $result = Yii::$app->commandBus->addCommand(Yii::$app->user->id, 'UserSettingApprove', $params);
        if (!$result) {
            return false;
        }

        $this->_user->updateStatus(User::STATUS_APPROVE);

        $lastCommand = Yii::$app->commandBus->findCommandId('UserSettingApprove', [
            'entityId' => $userId,
            'status'   => CommandAR::STATUS_FOR_ACCEPTANCE
        ]);

        if ($lastCommand) {
            Yii::$app->commandBus->cancelCommand($lastCommand->id);
        }

        return true;
    }

}
