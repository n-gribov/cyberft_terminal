<?php

namespace addons\SBBOL\controllers;

use addons\SBBOL\components\SBBOLTransport;
use addons\SBBOL\helpers\SBBOLCustomerHelper;
use addons\SBBOL\helpers\SecureRemotePassword;
use addons\SBBOL\jobs\SendClientTerminalSettingsJob;
use addons\SBBOL\models\AuthReturnCode;
use addons\SBBOL\models\forms\RegisterHoldingForm;
use addons\SBBOL\models\SBBOLCustomer;
use addons\SBBOL\models\SBBOLKey;
use addons\SBBOL\models\SBBOLOrganization;
use addons\SBBOL\models\soap\request\ChangePasswordRequest;
use addons\SBBOL\models\soap\request\GetRequestStatusSRPRequest;
use addons\SBBOL\models\soap\request\PreChangePasswordRequest;
use addons\SBBOL\models\soap\request\PreLoginRequest;
use addons\SBBOL\models\soap\request\SendRequestsSRPRequest;
use addons\SBBOL\models\soap\response\SendRequestsSRPResponse;
use addons\SBBOL\SBBOLModule;
use common\base\BaseServiceController;
use common\helpers\CertsHelper;
use common\helpers\PasswordHelper;
use common\helpers\sbbol\SBBOLXmlSerializer;
use common\helpers\Uuid;
use common\models\sbbolxml\request\PersonalInfoType;
use common\models\sbbolxml\request\Request;
use common\models\sbbolxml\response\Response;
use common\models\sbbolxml\SBBOLTransportConfig;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class OrganizationController extends BaseServiceController
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

    /**
     * Метод обрабатывает страницу индекса
     */
    public function actionIndex()
    {
        $query = SBBOLOrganization::find()->orderBy(['fullName' => SORT_ASC]);

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

    public function actionView($inn)
    {
        $model = SBBOLOrganization::findOne($inn);

        if ($model === null) {
            throw new NotFoundHttpException();
        }

        $customersDataProvider = new ActiveDataProvider([
            'query' => $model->getCustomers()->orderBy('fullName'),
            'sort'  => false,
        ]);

        // Вывести страницу
        return $this->render('view', compact('model', 'customersDataProvider'));
    }

    public function actionUpdate($inn)
    {
        $model = SBBOLOrganization::findOne($inn);

        if ($model === null) {
            throw new NotFoundHttpException();
        }

        $model->scenario = SBBOLOrganization::SCENARIO_WEB_UPDATE;

        // Если данные модели успешно загружены из формы в браузере
        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            // Если модель успешно сохранена в БД
            if ($model->save()) {
                // Поместить в сессию флаг сообщения об успешном сохранении данных организации
                Yii::$app->session->setFlash('success', Yii::t('app/sbbol', 'Organization data is updated'));
                return $this->redirect('index');
            } else {
                // Поместить в сессию флаг сообщения об ошибке сохранения данных организации
                Yii::info("Failed to update SBBOL organization $inn, errors: " . var_export($model->errors, true));
                Yii::$app->session->setFlash('error', Yii::t('app/sbbol', 'Failed to update organization data'));
            }
        }

        $terminalAddressSelectOptions = ArrayHelper::map(
            CertsHelper::getCerts(null),
            'terminal',
            'terminal'
        );

        // Вывести страницу
        return $this->render(
            'update',
            compact('model', 'terminalAddressSelectOptions')
        );
    }

    public function actionViewCustomer($id)
    {
        $model = SBBOLCustomer::findOne($id);

        if ($model === null) {
            throw new NotFoundHttpException();
        }

        // Вывести страницу
        return $this->render('viewCustomer', compact('model'));
    }

    public function actionRequestCustomerUpdate($id)
    {
        $customer = SBBOLCustomer::findOne($id);

        if ($customer === null) {
            throw new NotFoundHttpException();
        }

        $requestDocument = (new Request())
            ->setRequestId((string)Uuid::generate(false)->toString())
            ->setVersion(SBBOLTransportConfig::EXCHANGE_FORMAT_VERSION)
            ->setProtocolVersion(SBBOLTransportConfig::PROTOCOL_VERSION)
            ->setOrgId($customer->id)
            ->setSender($customer->senderName)
            ->setPersonalInfo((new PersonalInfoType()));

        /** @var SBBOLModule $module */
        $module = $this->module;
        $sessionId = $module->sessionManager->findOrCreateSession($customer->holdingHeadId);
        $isSent = false;
        if ($sessionId) {
            $sendResult = $module->transport->sendAsync(
                $requestDocument,
                $sessionId,
                ['action' => 'updateCustomer']
            );
            $isSent = $sendResult->isSent();
            if (!$isSent) {
                Yii::info("Failed to send customer info request, error: {$sendResult->getErrorMessage()}");
            }
        }

        if ($isSent) {
            // Поместить в сессию флаг сообщения об успешной отправке запроса
            Yii::$app->session->setFlash('success', Yii::t('app/sbbol', 'Update request is sent'));
        } else {
            // Поместить в сессию флаг сообщения об ошибке отправки запроса
            Yii::$app->session->setFlash('error', Yii::t('app/sbbol', 'Failed to send customer information request'));
        }

        // Перенаправить на страницу просмотра организации
        return $this->redirect(['view-customer', 'id' => $id]);
    }

    public function actionRequestHoldingInfo()
    {
        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!Yii::$app->request->isPost || !Yii::$app->request->isAjax) {
            throw new BadRequestHttpException();
        }

        $model = new RegisterHoldingForm();
        // Загрузить данные модели из формы в браузере
        $model->load(Yii::$app->request->post());
        if (!$model->validate()) {
            $validationErrors = [];
            foreach ($model->getErrors() as $attribute => $errors) {
                $validationErrors[Html::getInputId($model, $attribute)] = $errors;
            }

            return ['success' => false, 'validationErrors' => $validationErrors];
        }

        /** @var SBBOLTransport $transport */
        $transport = $this->module->transport;
        $loginResult = $transport->loginByCredentials($model->login, $model->password);
        $newPassword = $model->password;
        if ($loginResult->isPasswordChangeRequired()) {
            list($passwordIsChanged, $newPassword) = $this->changePassword($model->login, $model->password);
            if ($passwordIsChanged) {
                $loginResult = $transport->loginByCredentials($model->login, $newPassword);
            } else {
                \Yii::info('Failed to change password');
                return [
                    'success' => false,
                    'errorMessage' => Yii::t('app/sbbol', 'Failed to change password')
                ];
            }
        }

        if (!$loginResult->isLoggedIn()) {
            \Yii::info("SBBOL authorization failed, error code: {$loginResult->getReturnCode()->getId()}");
            return [
                'success' => false,
                'errorMessage' => Yii::t('app/sbbol', 'Authorization failed')
            ];
        }

        list($requestId, $errorMessage) = $this->sendCustomerInfoRequest($loginResult->getSessionId(), static::HOLDING_HEAD_ORG_ID, $model->senderName);

        return [
            'success'      => !empty($requestId),
            'requestId'    => $requestId,
            'sessionId'    => $loginResult->getSessionId(),
            'errorMessage' => $errorMessage,
            'password'     => $newPassword,
        ];
    }

    public function actionImportBranchOrganizations($id)
    {
        $customer = SBBOLCustomer::findOne($id);
        if ($customer === null || !$customer->isHoldingHead) {
            Yii::info("Customer $id is not found or is not a holding head");
            throw new NotFoundHttpException();
        }

        $activeKey = SBBOLKey::findActiveByCustomer($customer->id);
        if ($activeKey === null) {
            // Поместить в сессию флаг сообщения о необходимости наличия активного ключа
            Yii::$app->session->setFlash('error', Yii::t('app/sbbol', 'In order to perform this action customer must have active key'));
            // Перенаправить на страницу просмотра организации
            return $this->redirect(['view-customer', 'id' => $customer->id]);
        }

        $requestDocument = (new Request())
            ->setRequestId(Uuid::generate(false)->toString())
            ->setVersion(SBBOLTransportConfig::EXCHANGE_FORMAT_VERSION)
            ->setProtocolVersion(SBBOLTransportConfig::PROTOCOL_VERSION)
            ->setOrgId($customer->id)
            ->setSender($customer->senderName)
            ->setPersonalInfo((new PersonalInfoType()));

        /** @var SBBOLModule $module */
        $module = $this->module;
        $sessionId = $module->sessionManager->findOrCreateSession($customer->holdingHeadId);
        $isSent = false;
        if ($sessionId) {
            $sendResult = $module->transport->sendAsync(
                $requestDocument,
                $sessionId,
                ['action' => 'importHoldingBranches'],
                null,
                null,
                $customer->holdingHeadId
            );
            $isSent = $sendResult->isSent();
            if (!$isSent) {
                Yii::info("Failed to send customer info request, error: {$sendResult->getErrorMessage()}");
            }
        }

        if ($isSent) {
            // Поместить в сессию флаг сообщения об успешной отправке запроса
            Yii::$app->session->setFlash('success', Yii::t('app/sbbol', 'Branches information request is sent'));
        } else {
            // Поместить в сессию флаг сообщения об ошибке отправки запроса
            Yii::$app->session->setFlash('error', Yii::t('app/sbbol', 'Failed to send customer information request'));
        }

        // Перенаправить на страницу просмотра организации
        return $this->redirect(['view-customer', 'id' => $id]);
    }

    private function changePassword($login, $currentPassword)
    {
        $newPassword = PasswordHelper::generatePassword();
        \Yii::info("Setting new password: $newPassword");

        /** @var SBBOLTransport $transport */
        $transport = $this->module->transport;

        $preLoginResponse = $transport->send(new PreLoginRequest(['userLogin' => $login]));
        list($currentPasswordSalt, $B, $sessionId, $returnCode) = $preLoginResponse->return;

        $preLoginReturnCode = new AuthReturnCode($returnCode);
        if ($preLoginReturnCode->getId() !== AuthReturnCode::SUCCESS) {
            \Yii::info("Changing password failed at preLogin, response code: {$preLoginReturnCode->getId()}");
            return [false, null];
        }

        $secureRemotePassword = new SecureRemotePassword(
            $login,
            $currentPassword,
            $currentPasswordSalt,
            $B
        );

        $preChangePasswordResponse = $transport->send(new PreChangePasswordRequest(['sessionId' => $sessionId]));
        $newPasswordSalt = $preChangePasswordResponse->return;

        $changePasswordRequest = new ChangePasswordRequest([
            'sessionId' => $sessionId,
            'newPasswordData' => [
                $secureRemotePassword->getK(),
                $secureRemotePassword->getA(),
                $secureRemotePassword->calculateNewPasswordVerifier($newPassword, $newPasswordSalt),
            ]
        ]);

        $changePasswordResponse = $transport->send($changePasswordRequest);
        list($m2Bin, $changePasswordReturnCodeBin) = $changePasswordResponse->return;
        $changePasswordReturnCode = new AuthReturnCode($changePasswordReturnCodeBin);
        if ($changePasswordReturnCode->getId() !== AuthReturnCode::SUCCESS) {
            \Yii::info("Changing password failed at changePassword, response code: {$changePasswordReturnCode->getId()}");
            return [false, null];
        }

        return [true, $newPassword];
    }

    private function sendCustomerInfoRequest($sessionId, $orgId, $senderName)
    {
        /** @var SBBOLTransport $transport */
        $transport = $this->module->transport;

        $requestDocument = (new Request())
            ->setRequestId(Uuid::generate(false)->toString())
            ->setVersion(SBBOLTransportConfig::EXCHANGE_FORMAT_VERSION)
            ->setProtocolVersion(SBBOLTransportConfig::PROTOCOL_VERSION)
            ->setOrgId($orgId)
            ->setSender($senderName)
            ->setPersonalInfo((new PersonalInfoType()));

        $requestXmlContent = SBBOLXmlSerializer::serialize($requestDocument);
        $requestMessage = new SendRequestsSRPRequest([
            'sessionId' => $sessionId,
            'requests'  => SBBOLXmlSerializer::createCdataNode('ns1', 'requests', $requestXmlContent)
        ]);

        /** @var SendRequestsSRPResponse $responseMessage */
        $responseMessage = $transport->send($requestMessage);

        if (strpos($responseMessage->return, '<?xml') === 0) {
            /** @var Response $response */
            $response = SBBOLXmlSerializer::deserialize($responseMessage->return, Response::class);
            if (!empty($response->getErrors())) {
                $error = $response->getErrors()[0];
                return [null, $error->getDesc() ?: Yii::t('app/sbbol', 'Failed to get organization info')];
            }
        }
        return [$responseMessage->return, null];
    }

    public function actionCheckHoldingInfoRequestStatus()
    {
        /** @var SBBOLTransport $transport */
        $transport = $this->module->transport;

        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!Yii::$app->request->isPost || !Yii::$app->request->isAjax) {
            throw new BadRequestHttpException();
        }

        $sessionId = Yii::$app->request->post('sessionId');
        $requestId = Yii::$app->request->post('requestId');
        $login = Yii::$app->request->post('login');
        $password = Yii::$app->request->post('password');
        $senderName = Yii::$app->request->post('senderName');

        $requestMessage = new GetRequestStatusSRPRequest([
            'sessionId' => $sessionId,
            'orgId' => static::HOLDING_HEAD_ORG_ID,
            'requests' => [$requestId]
        ]);

        $responseDocument = $transport->send($requestMessage);
        $return = $responseDocument->return;

        if (!is_scalar($return) || strpos($return, '<!--') !== 0) {
            /** @var Response $response */
            $response = SBBOLXmlSerializer::deserialize($responseDocument->return, Response::class);
            if (!empty($response->getErrors())) {
                $error = $response->getErrors()[0];
                return [
                    'isFinished' => true,
                    'errorMessage' => $error->getDesc() ?: Yii::t('app/sbbol', 'Failed to get organization info'),
                ];
            }
            if (!empty($response->getOrganizationsInfo())) {
                $isSaved = SBBOLCustomerHelper::saveHoldingHeadCustomer(
                    $response->getOrganizationsInfo()[0],
                    $senderName,
                    $login,
                    $password
                );
                if (!$isSaved) {
                    return [
                        'isFinished'  => true,
                        'errorMessage' => Yii::t('app/sbbol', 'Failed to save organization to database'),
                    ];
                }
                return $this->redirect('index');
            }
        } else {
            return ['isFinished' => false];
        }
    }

    public function actionSendClientTerminalSettings($inn)
    {
        $isEnqueued = Yii::$app->resque->enqueue(SendClientTerminalSettingsJob::class, ['organizationInn' => $inn]);

        if ($isEnqueued) {
            // Поместить в сессию флаг сообщения об успешной постановке запроса в очередь на отправку
            Yii::$app->session->setFlash('success', Yii::t('app/sbbol', 'Sending settings'));
        } else {
            // Поместить в сессию флаг сообщения об ошибке постановки в очередь запроса на отправку
            Yii::$app->session->setFlash('error', Yii::t('app/sbbol', 'Failed to schedule sending job'));
        }

        // Перенаправить на страницу индекса
        return $this->redirect('index');
    }
}
