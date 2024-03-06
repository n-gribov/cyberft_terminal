<?php

namespace addons\raiffeisen\controllers;

use addons\raiffeisen\jobs\SendClientTerminalSettingsJob;
use addons\raiffeisen\models\RaiffeisenCustomer;
use common\base\BaseServiceController;
use common\helpers\CertsHelper;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class CustomerController extends BaseServiceController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
        $query = RaiffeisenCustomer::find()->orderBy(['fullName' => SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => false,
        ]);

        // Вывести страницу
        return $this->render(
            'index',
            compact('dataProvider')
        );
    }

    public function actionCreate()
    {
        $model = new RaiffeisenCustomer();

        // Если данные модели успешно загружены из формы в браузере
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            // Если модель успешно сохранена в БД
            if ($model->save()) {
                // Поместить в сессию флаг сообщения об успешном сохранении клиента
                Yii::$app->session->setFlash('success', Yii::t('app/raiffeisen', 'Customer data is saved'));
                // Перенаправить на страницу индекса
                return $this->redirect('index');
            } else {
                Yii::info('Failed to create customer, errors: ' . var_export($model->getErrors(), true));
                // Поместить в сессию флаг сообщения об ошибке сохранения клиента
                Yii::$app->session->setFlash('error', Yii::t('app/raiffeisen', 'Failed to save customer data'));
            }
        }

        $terminalAddressSelectOptions = $this->getTerminalAddressSelectOptions();
        $signatureTypeSelectOptions = $this->getSignatureTypeSelectOptions();
        // Вывести страницу
        return $this->render(
            'create',
            compact('model', 'terminalAddressSelectOptions', 'signatureTypeSelectOptions')
        );
    }

    public function actionView($id)
    {
        // Получить из БД клиента с указанным id
        $model = $this->findModel($id);
        // Вывести страницу
        return $this->render('view', compact('model'));
    }

    public function actionUpdate($id)
    {
        // Получить из БД клиента с указанным id
        $model = $this->findModel($id);

        // Если данные модели успешно загружены из формы в браузере
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            // Если модель успешно сохранена в БД
            if ($model->save()) {
                // Поместить в сессию флаг сообщения об успешном сохранении клиента
                Yii::$app->session->setFlash('success', Yii::t('app/raiffeisen', 'Customer data is saved'));
                // Перенаправить на страницу индекса
                return $this->redirect('index');
            } else {
                Yii::info('Failed to update customer, errors: ' . var_export($model->getErrors(), true));
                // Поместить в сессию флаг сообщения об ошибке сохранения клиента
                Yii::$app->session->setFlash('error', Yii::t('app/raiffeisen', 'Failed to save customer data'));
            }
        }

        $terminalAddressSelectOptions = $this->getTerminalAddressSelectOptions();
        $signatureTypeSelectOptions = $this->getSignatureTypeSelectOptions();
        // Вывести страницу
        return $this->render(
            'update',
            compact('model', 'terminalAddressSelectOptions', 'signatureTypeSelectOptions')
        );
    }

    public function actionDelete($id)
    {
        // Получить из БД клиента с указанным id
        $model = $this->findModel($id);

        // Удалить документ из БД
        if ($model->delete()) {
            // Поместить в сессию флаг сообщения об успешном удалении клиента
            Yii::$app->session->setFlash('success', Yii::t('app/raiffeisen', 'Customer is deleted'));
        } else {
            // Поместить в сессию флаг сообщения об ошибке удаления клиента
            Yii::$app->session->setFlash('error', Yii::t('app/raiffeisen', 'Failed to delete customer'));
        }

        // Перенаправить на страницу индекса
        return $this->redirect('index');
    }

    public function actionSendClientTerminalSettings($id)
    {
        $isEnqueued = Yii::$app->resque->enqueue(SendClientTerminalSettingsJob::class, ['customerId' => $id]);

        if ($isEnqueued) {
            // Поместить в сессию флаг сообщения об успешной отправке настроек
            Yii::$app->session->setFlash('success', Yii::t('app/raiffeisen', 'Sending settings'));
        } else {
            // Поместить в сессию флаг сообщения об ошибке отправки настроек
            Yii::$app->session->setFlash('error', Yii::t('app/raiffeisen', 'Failed to schedule sending job'));
        }

        // Перенаправить на страницу индекса
        return $this->redirect('index');
    }

    private function getTerminalAddressSelectOptions(): array
    {
        return ArrayHelper::map(
            CertsHelper::getCerts(null),
            'terminal',
            'terminal'
        );
    }

    private function getSignatureTypeSelectOptions(): array
    {
        $signatureTypes = RaiffeisenCustomer::getSignatureTypes();
        return array_combine($signatureTypes, $signatureTypes);
    }

    /**
     * Метод ищет модель клиента в БД по первичному ключу.
     * Если модель не найдена, выбрасывается исключение HTTP 404
     */
    private function findModel($id): RaiffeisenCustomer
    {
        // Получить из БД клиента с указанным id
        $customer = RaiffeisenCustomer::findOne($id);
        if ($customer === null) {
            throw new NotFoundHttpException();
        }
        return $customer;
    }
}
