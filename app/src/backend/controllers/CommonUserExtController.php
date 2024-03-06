<?php

namespace backend\controllers;

use common\base\Controller;
use common\commands\CommandAR;
use common\helpers\UserHelper;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use common\models\CommonUserExt;

class CommonUserExtController extends Controller
{
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
            ]
        ];
    }

    /**
     * Get user extened data
     *
     * @param integer $id User ID
     * @return mixed
     */
    public function actionIndex($id, $type)
    {
        $model = $this->getUserExtModel($id, $type);

        // Вывести страницу
        return $this->render('index', [
            'extModel' => $model,
        ]);
    }

    public function actionUpdatePermissions()
    {
        $id = (int) Yii::$app->request->get('id');
        $extModel = CommonUserExt::findOne($id);

        if (empty($extModel)) {
            // Поместить в сессию флаг сообщения о ненайденном пользователе
            Yii::$app->session->setFlash('error', Yii::t('app/user', 'Unknown user - data was not updated'));
            // Перенаправить на страницу индекса
            return $this->redirect(['/user']);
        }

        if (UserHelper::canUpdateProfile($extModel->userId)) {
            $settings = \Yii::$app->request->post('settings');
            if (empty($settings)) {
                $settings = [];
            }
            $extModel->settings = array_values($settings);

            if (!$extModel->save()) {
                // Поместить в сессию флаг сообщения об ошибке сохранения настроек пользователя
                Yii::$app->session->addFlash('error', Yii::t('app/user', 'Failed to save user settings'));
            }
        } else {
            // Поместить в сессию флаг сообщения о запрете редактирования пользователя
            Yii::$app->session->addFlash('error', Yii::t('app/user', 'Editing user is not allowed'));
        }

        // Перенаправить на страницу индекса
        return $this->redirect(['index', 'id' => $extModel->userId, 'type' => $extModel->type]);
    }

    /**
     * Получение данных по сервису указанного пользователя
     * @param $id
     * @param $type
     * @return null|static
     * @throws NotFoundHttpException
     */
    protected function getUserExtModel($id, $type)
    {
        // Проверяем, что указанный пользователь существует
        $user = User::findOne($id);
        if (empty($user)) {
            throw new NotFoundHttpException(Yii::t('app/user', 'Requested page not found'));
        }

        // Проверяем, что указанный сервис существует в записях пользователя
        $extModel = CommonUserExt::findOne(['userId' => $id, 'type' => $type]);
        if (empty($extModel)) {
            throw new NotFoundHttpException(Yii::t('app/user', 'Requested page not found'));
        }

        // Проверяем, что указанный сервис активирован у пользователя
        if (!$extModel->canAccess) {
            throw new NotFoundHttpException(Yii::t('app/user', 'Requested page not found'));
        }

        // Во всех остальных случаях возвращаем модель сервиса
        return $extModel;
    }
}
