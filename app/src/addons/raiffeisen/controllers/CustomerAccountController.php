<?php

namespace addons\raiffeisen\controllers;

use addons\edm\models\DictCurrency;
use addons\raiffeisen\models\RaiffeisenCustomer;
use addons\raiffeisen\models\RaiffeisenCustomerAccount;
use common\base\BaseServiceController;
use Yii;
use yii\data\ActiveDataProvider;
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

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app/raiffeisen', 'Account is saved'));
                return $this->redirect(['/raiffeisen/customer/view', 'id' => $customerId]);
            } else {
                Yii::info('Failed to create customer, errors: ' . var_export($model->getErrors(), true));
                Yii::$app->session->setFlash('error', Yii::t('app/raiffeisen', 'Failed to save account'));
            }
        }

        $currencySelectOptions = $this->getCurrencySelectOptions();
        return $this->render(
            'create',
            compact('model', 'currencySelectOptions')
        );
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app/raiffeisen', 'Account is saved'));
                return $this->redirect(['/raiffeisen/customer/view', 'id' => $model->customerId]);
            } else {
                Yii::info('Failed to create customer, errors: ' . var_export($model->getErrors(), true));
                Yii::$app->session->setFlash('error', Yii::t('app/raiffeisen', 'Failed to save account'));
            }
        }

        $currencySelectOptions = $this->getCurrencySelectOptions();
        return $this->render(
            'update',
            compact('model', 'currencySelectOptions')
        );
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('app/raiffeisen', 'Account is deleted'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/raiffeisen', 'Failed to delete account'));
        }

        return $this->redirect(['/raiffeisen/customer/view', 'id' => $model->customerId]);
    }

    private function getCurrencySelectOptions(): array
    {
        $currencies = DictCurrency::find()->orderBy('name')->all();
        return ArrayHelper::map($currencies, 'code', 'name');
    }

    private function findModel($id): RaiffeisenCustomerAccount
    {
        $account = RaiffeisenCustomerAccount::findOne($id);
        if ($account === null) {
            throw new NotFoundHttpException();
        }
        return $account;
    }
}
