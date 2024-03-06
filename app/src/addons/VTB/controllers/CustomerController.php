<?php


namespace addons\VTB\controllers;


use addons\VTB\jobs\SendClientTerminalSettingsJob;
use addons\VTB\jobs\UpdateVTBCustomersDataJob;
use addons\VTB\models\VTBCustomer;
use common\base\BaseServiceController;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class CustomerController extends BaseServiceController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Метод обрабатывает страницу индекса
     */
    public function actionIndex()
    {
        $query = VTBCustomer::find()->orderBy(['fullName' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> false
        ]);
        // Вывести страницу
        return $this->render('index', compact('dataProvider'));
    }

    public function actionView($id)
    {
        $model = VTBCustomer::find()
            ->with('accounts', 'propertyType')
            ->where(['id' => $id])
            ->one();

        if ($model === null) {
            throw new NotFoundHttpException();
        }

        // Вывести страницу
        return $this->render('view', compact('model'));
    }

    public function actionUpdate($id)
    {
        $model = VTBCustomer::findOne($id);
        $model->scenario = VTBCustomer::SCENARIO_WEB_UPDATE;

        // Если данные модели успешно загружены из формы в браузере
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            // Если модель успешно сохранена в БД
            if ($model->save()) {
                // Поместить в сессию флаг сообщения об успешном сохранении данных
                Yii::$app->session->setFlash('success', Yii::t('app/vtb', 'Customer data is updated'));
                // Перенаправить на страницу индекса
                return $this->redirect('index');
            }
        }

        // Вывести страницу
        return $this->render(
            'update',
            compact('model')
        );
    }

    public function actionRequestUpdate()
    {
        $isEnqueued = Yii::$app->resque->enqueue(UpdateVTBCustomersDataJob::class);
        if ($isEnqueued) {
            // Поместить в сессию флаг сообщения об успешной отправке запроса
            Yii::$app->session->setFlash('success', Yii::t('app/vtb', 'Update request is in progress'));
        } else {
            // Поместить в сессию флаг сообщения об ошибке отправки запроса
            Yii::$app->session->setFlash('error', Yii::t('app/vtb', 'Failed to schedule update job'));
        }
        // Перенаправить на страницу индекса
        return $this->redirect('index');
    }

    public function actionSendClientTerminalSettings($customerId)
    {
        $isEnqueued = Yii::$app->resque->enqueue(SendClientTerminalSettingsJob::class, ['customerId' => $customerId]);
        if ($isEnqueued) {
            // Поместить в сессию флаг сообщения об успешной отправке запроса
            Yii::$app->session->setFlash('success', Yii::t('app/vtb', 'Sending settings'));
        } else {
            // Поместить в сессию флаг сообщения об ошибке отправки запроса
            Yii::$app->session->setFlash('error', Yii::t('app/vtb', 'Failed to schedule sending job'));
        }
        // Перенаправить на страницу индекса
        return $this->redirect('index');
    }
}
