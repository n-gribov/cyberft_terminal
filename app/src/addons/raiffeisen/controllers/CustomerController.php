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

    public function actionIndex()
    {
        $query = RaiffeisenCustomer::find()->orderBy(['fullName' => SORT_ASC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => false,
        ]);

        return $this->render(
            'index',
            compact('dataProvider')
        );
    }

    public function actionCreate()
    {
        $model = new RaiffeisenCustomer();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app/raiffeisen', 'Customer data is saved'));
                return $this->redirect('index');
            } else {
                Yii::info('Failed to create customer, errors: ' . var_export($model->getErrors(), true));
                Yii::$app->session->setFlash('error', Yii::t('app/raiffeisen', 'Failed to save customer data'));
            }
        }

        $terminalAddressSelectOptions = $this->getTerminalAddressSelectOptions();
        $signatureTypeSelectOptions = $this->getSignatureTypeSelectOptions();
        return $this->render(
            'create',
            compact('model', 'terminalAddressSelectOptions', 'signatureTypeSelectOptions')
        );
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', compact('model'));
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app/raiffeisen', 'Customer data is saved'));
                return $this->redirect('index');
            } else {
                Yii::info('Failed to update customer, errors: ' . var_export($model->getErrors(), true));
                Yii::$app->session->setFlash('error', Yii::t('app/raiffeisen', 'Failed to save customer data'));
            }
        }

        $terminalAddressSelectOptions = $this->getTerminalAddressSelectOptions();
        $signatureTypeSelectOptions = $this->getSignatureTypeSelectOptions();
        return $this->render(
            'update',
            compact('model', 'terminalAddressSelectOptions', 'signatureTypeSelectOptions')
        );
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', Yii::t('app/raiffeisen', 'Customer is deleted'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/raiffeisen', 'Failed to delete customer'));
        }

        return $this->redirect('index');
    }

    public function actionSendClientTerminalSettings($id)
    {
        $isEnqueued = Yii::$app->resque->enqueue(SendClientTerminalSettingsJob::class, ['customerId' => $id]);

        if ($isEnqueued) {
            Yii::$app->session->setFlash('success', Yii::t('app/raiffeisen', 'Sending settings'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app/raiffeisen', 'Failed to schedule sending job'));
        }

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

    private function findModel($id): RaiffeisenCustomer
    {
        $customer = RaiffeisenCustomer::findOne($id);
        if ($customer === null) {
            throw new NotFoundHttpException();
        }
        return $customer;
    }
}
