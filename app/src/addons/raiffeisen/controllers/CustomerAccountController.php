<?php

namespace addons\raiffeisen\controllers;

use addons\edm\models\DictCurrency;
use addons\raiffeisen\models\RaiffeisenCustomerAccount;
use common\base\BaseServiceController;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class CustomerAccountController extends BaseServiceController
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

    public function actionCreate($customerId)
    {
        $model = new RaiffeisenCustomerAccount(['customerId' => $customerId, 'currencyCode' => 810]);

        // Если данные модели успешно загружены из формы в браузере
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            // Если модель успешно сохранена в БД
            if ($model->save()) {
                // Поместить в сессию флаг сообщения об успешном сохранении счёта
                Yii::$app->session->setFlash('success', Yii::t('app/raiffeisen', 'Account is saved'));
                // Перенаправить на страницу просмотра
                return $this->redirect(['/raiffeisen/customer/view', 'id' => $customerId]);
            } else {
                // Поместить в сессию флаг сообщения об ошибке создания счёта
                Yii::info('Failed to create customer, errors: ' . var_export($model->getErrors(), true));
                Yii::$app->session->setFlash('error', Yii::t('app/raiffeisen', 'Failed to save account'));
            }
        }

        $currencySelectOptions = $this->getCurrencySelectOptions();
        // Вывести страницу
        return $this->render(
            'create',
            compact('model', 'currencySelectOptions')
        );
    }

    public function actionUpdate($id)
    {
        // Получить из БД документ с указанным id
        $model = $this->findModel($id);

        // Если данные модели успешно загружены из формы в браузере
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            // Если модель успешно сохранена в БД
            if ($model->save()) {
                // Поместить в сессию флаг сообщения об успешном сохранении счёта
                Yii::$app->session->setFlash('success', Yii::t('app/raiffeisen', 'Account is saved'));
                // Перенаправить на страницу просмотра
                return $this->redirect(['/raiffeisen/customer/view', 'id' => $model->customerId]);
            } else {
                Yii::info('Failed to create customer, errors: ' . var_export($model->getErrors(), true));
                // Поместить в сессию флаг сообщения об ошибке сохранения счёта
                Yii::$app->session->setFlash('error', Yii::t('app/raiffeisen', 'Failed to save account'));
            }
        }

        $currencySelectOptions = $this->getCurrencySelectOptions();
        // Вывести страницу
        return $this->render(
            'update',
            compact('model', 'currencySelectOptions')
        );
    }

    public function actionDelete($id)
    {
        // Получить из БД документ с указанным id
        $model = $this->findModel($id);

        // Удалить документ из БД
        if ($model->delete()) {
            // Поместить в сессию флаг сообщения об успешном удалении счёта
            Yii::$app->session->setFlash('success', Yii::t('app/raiffeisen', 'Account is deleted'));
        } else {
            // Поместить в сессию флаг сообщения об ошибке удаления счёта
            Yii::$app->session->setFlash('error', Yii::t('app/raiffeisen', 'Failed to delete account'));
        }
        // Перенаправить на страницу просмотра
        return $this->redirect(['/raiffeisen/customer/view', 'id' => $model->customerId]);
    }

    private function getCurrencySelectOptions(): array
    {
        $currencies = DictCurrency::find()->orderBy('name')->all();
        return ArrayHelper::map($currencies, 'code', 'name');
    }

    /**
     * Метод ищет модель счёта плательщика в БД по первичному ключу.
     * Если модель не найдена, выбрасывается исключение HTTP 404
     */
    private function findModel($id): RaiffeisenCustomerAccount
    {
        // Получить из БД счёт плательщика с указанным id
        $account = RaiffeisenCustomerAccount::findOne($id);
        if ($account === null) {
            throw new NotFoundHttpException();
        }
        return $account;
    }
}
