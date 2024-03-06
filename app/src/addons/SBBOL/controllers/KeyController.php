<?php

namespace addons\SBBOL\controllers;

use addons\SBBOL\helpers\PrintableCertificate;
use addons\SBBOL\models\forms\RegisterKeyForm;
use addons\SBBOL\models\SBBOLKey;
use addons\SBBOL\models\SBBOLRequest;
use common\base\BaseServiceController;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class KeyController extends BaseServiceController
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

    public function actionIndex()
    {
        $query = SBBOLKey::find()
            ->joinWith('keyOwner as keyOwner')
            ->with('keyOwner.customer')
            ->orderBy(['keyOwner.fullName' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => false,
        ]);
        return $this->render(
            'index',
            compact('dataProvider')
        );
    }

    public function actionGenerateCertificateRequestParams()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!Yii::$app->request->isAjax) {
            throw new BadRequestHttpException();
        }

        $model = new RegisterKeyForm(['scenario' => RegisterKeyForm::SCENARIO_GENERATE_CERTIFICATE_REQUEST_PARAMS]);
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->validate()) {
                $csrParams = $model->generateCertificateRequestParams();
                $model->scenario = RegisterKeyForm::SCENARIO_CREATE_KEY;

                return $this->renderModalResponse(
                    'register-key-form/_create',
                    compact('model', 'csrParams')
                );
            }
        }

        return $this->renderModalResponse('register-key-form/_generateCertificateRequestParams', compact('model'));
    }

    public function actionCreate()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!Yii::$app->request->isAjax || !Yii::$app->request->isPost) {
            throw new BadRequestHttpException();
        }

        $model = new RegisterKeyForm(['scenario' => RegisterKeyForm::SCENARIO_CREATE_KEY]);
        $model->load(Yii::$app->request->post());
        $csrParams = $model->generateCertificateRequestParams();
        if ($model->validate()) {
            list($isSuccess, $errorMessage, $requestId, $keyId) = $model->createKey();
            return [
                'success'      => $isSuccess,
                'requestId'    => $requestId,
                'keyId'        => $keyId,
                'errorMessage' => $errorMessage,
            ];
        }

        return $this->renderModalResponse('register-key-form/_create', compact('model', 'csrParams'));
    }

    private function renderModalResponse($view, $params = [])
    {
        $body = $this->renderPartial($view, $params);

        return compact('body');
    }

    public function actionCheckCertificateRequestStatus()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!Yii::$app->request->isPost || !Yii::$app->request->isAjax) {
            throw new BadRequestHttpException();
        }

        $requestId = Yii::$app->request->post('requestId');
        $keyId = Yii::$app->request->post('keyId');

        $key = SBBOLKey::findOne($keyId);
        if ($key === null) {
            throw new \Exception("Key $requestId is not found");
        }

        $importRequest = SBBOLRequest::findOne(['receiverRequestId' => $requestId]);

        if ($importRequest === null) {
            throw new \Exception("Document import request $requestId is not found");
        }

        if ($importRequest->isFailed()) {
            $key->delete();
            return [
                'isFinished' => true,
                'success' => false,
                'errorMessage' => Yii::t('app/sbbol', 'Failed to get send certificate request'),
            ];
        }

        if ($importRequest->status === SBBOLRequest::STATUS_DELIVERED) {
            $key->status = SBBOLKey::STATUS_CERTIFICATE_REQUEST_IS_SENT;
            $key->save();
            Yii::$app->session->setFlash('success', Yii::t('app/sbbol', 'Certificate request is sent to Sberbank'));
            return $this->redirect('index');
        }

        return ['isFinished' => false];
    }

    public function actionDownloadPrintableCertificate($id)
    {
        $key = SBBOLKey::findOne($id);
        if ($key === null) {
            throw new NotFoundHttpException("Key $id is not found");
        }

        $documentContent = PrintableCertificate::generateForKey($key);
        Yii::$app->response->sendContentAsFile($documentContent, "{$key->bicryptId}.docx");
    }
}
