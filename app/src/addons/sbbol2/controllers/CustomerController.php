<?php

namespace addons\sbbol2\controllers;

use addons\sbbol2\jobs\SendClientTerminalSettingsJob;
use addons\sbbol2\jobs\UpdateSberbankCustomerJob;
use addons\sbbol2\models\Sbbol2Customer;
use common\base\BaseServiceController;
use common\helpers\CertsHelper;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class CustomerController extends BaseServiceController
{
    const HOLDING_HEAD_ORG_ID = '00000000-0000-0000-0000-000000000000';

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

    public function actionView($id)
    {
        $model = Sbbol2Customer::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException();
        }

        $customersDataProvider = new ActiveDataProvider([
            'query' => $model->getcustomerAccounts()->orderBy('number'),
            'sort'  => false,
        ]);

        // Вывести страницу
        return $this->render('view', compact('model', 'customersDataProvider'));
    }

    public function actionUpdate($id)
    {
        $model = Sbbol2Customer::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException();
        }

        // Если данные модели успешно загружены из формы в браузере
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            // Если модель успешно сохранена в БД
            if ($model->save()) {
                // Поместить в сессию флаг сообщения об успешном сохранении организации
                Yii::$app->session->setFlash('success', Yii::t('app/sbbol', 'Organization data is updated'));
                // Перенаправить на страницу индекса
                return $this->redirect('index');
            } else {
                Yii::info("Failed to update SBBOL organization $id, errors: " . var_export($model->errors, true));
                // Поместить в сессию флаг сообщения об ошибке сохранения организации
                Yii::$app->session->setFlash('error', Yii::t('app/sbbol', 'Failed to update organization data'));
            }
        }

        $terminalAddressSelectOptions = ArrayHelper::map(
            CertsHelper::getCerts(null),
            'terminal',
            'terminal'
        );

        // Вывести страницу радактирования
        return $this->render('update', compact('model', 'terminalAddressSelectOptions'));
    }

    /**
     * Метод обрабатывает страницу индекса
     */
    public function actionIndex()
    {
        $query = Sbbol2Customer::find()->orderBy(['fullName' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => false,
        ]);

        // Вывести страницу индекса
        return $this->render('index', compact('dataProvider'));
    }

    public function actionSendClientTerminalSettings($inn)
    {
        $isEnqueued = Yii::$app->resque->enqueue(SendClientTerminalSettingsJob::class, ['inn' => $inn]);
        
        if ($isEnqueued) {
            // Поместить в сессию флаг сообщения об успешной отправке настроек
            Yii::$app->session->setFlash('success', Yii::t('app/sbbol2', 'Sending settings'));
        } else {
            // Поместить в сессию флаг сообщения об ошибке отправки настроек
            Yii::$app->session->setFlash('error', Yii::t('app/sbbol2', 'Failed to schedule sending job'));
        }

        // Перенаправить на страницу индекса
        return $this->redirect('index');
    }

    public function actionRequestUpdate($id)
    {
        $jobId = Yii::$app->resque->enqueue(
            UpdateSberbankCustomerJob::class, ['customerId' => $id]
        );
        if ($jobId) {
            // Поместить в сессию флаг сообщения об успешной отправке запроса
            Yii::$app->session->setFlash('success', Yii::t('app/sbbol2', 'Update request is sent'));
        } else {
            // Поместить в сессию флаг сообщения об ошибке отправки запроса
            Yii::$app->session->setFlash('error', Yii::t('app/sbbol2', 'Failed to send customer information request'));
        }
        // Перенаправить на страницу просмотра
        return $this->redirect(['view', 'id' => $id]);
    }
}
