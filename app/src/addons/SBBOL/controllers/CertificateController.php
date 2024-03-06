<?php

namespace addons\SBBOL\controllers;

use addons\SBBOL\models\SBBOLCertificate;
use addons\SBBOL\models\SBBOLCustomer;
use addons\SBBOL\SBBOLModule;
use common\base\BaseServiceController;
use common\helpers\Uuid;
use common\models\sbbolxml\request\Request;
use common\models\sbbolxml\request\RequestType\CryptoIncomingAType;
use common\models\sbbolxml\SBBOLTransportConfig;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class CertificateController extends BaseServiceController
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
        $dataProvider = new ActiveDataProvider([
            'query' => SBBOLCertificate::find()->orderBy('dateCreate'),
            'sort'  => false,
        ]);
        // Вывести страницу
        return $this->render(
            'index',
            compact('dataProvider')
        );
    }

    public function actionDownload($id)
    {
        $certificate = SBBOLCertificate::findOne($id);
        if ($certificate === null || empty($certificate->body)) {
            throw new NotFoundHttpException("SBBOL certificate $id is not found or has empty body");
        }

        return Yii::$app->response->sendContentAsFile($certificate->body, "{$certificate->type}-{$certificate->fingerprint}.crt");
    }

    /**
     * @return \yii\web\Response
     */
    public function actionRequestUpdate()
    {
        $customers = SBBOLCustomer::find()
            ->where(['isHoldingHead' => true])
            ->all();

        $hasErrors = false;
        foreach ($customers as $customer) {
            $isSent = $this->sendCertificatesRequest($customer);
            if (!$isSent) {
                $hasErrors = true;
            }
        }

        if (!$hasErrors) {
            // Поместить в сессию флаг сообщения об успешной отправке запроса
            Yii::$app->session->setFlash('success', Yii::t('app/sbbol', 'Update request is sent'));
        } else {
            // Поместить в сессию флаг сообщения об ошибке отправки запроса
            Yii::$app->session->setFlash('error', Yii::t('app/sbbol', 'Failed to get send update request'));
        }

        // Перенаправить на страницу индекса
        return $this->redirect('index');
    }

    private function sendCertificatesRequest(SBBOLCustomer $customer)
    {
        $cryptoIncoming = (new CryptoIncomingAType())
            ->setCertificates(
                (new CryptoIncomingAType\CertificatesAType())
                    ->setBank(1)
                    ->setRoot(1)
            );

        $requestDocument = (new Request())
            ->setRequestId((string)Uuid::generate(false)->toString())
            ->setVersion(SBBOLTransportConfig::EXCHANGE_FORMAT_VERSION)
            ->setProtocolVersion(SBBOLTransportConfig::PROTOCOL_VERSION)
            ->setOrgId($customer->id)
            ->setSender($customer->senderName)
            ->setCryptoIncoming($cryptoIncoming);

        /** @var SBBOLModule $module */
        $module = $this->module;
        $sessionId = null;
        try {
            $sessionId = $module->sessionManager->findOrCreateSession($customer->holdingHeadId);
        } catch (\Exception $exception) {
            Yii::info("Failed to start SBBOL session for customer {$customer->holdingHeadId}");

            return false;
        }

        $isSent = false;
        if ($sessionId) {
            $sendResult = $module->transport->sendAsync($requestDocument, $sessionId);
            $isSent = $sendResult->isSent();
            if (!$isSent) {
                Yii::info("Failed to send customer info request, error: {$sendResult->getErrorMessage()}");
            }
        }

        return $isSent;
    }
}
