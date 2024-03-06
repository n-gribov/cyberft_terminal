<?php

namespace addons\sbbol2\controllers;

use addons\sbbol2\authClient\AuthAction;
use addons\sbbol2\authClient\SberbankOpenIdConnect;
use addons\sbbol2\jobs\ImportSberbankCustomerJob;
use addons\sbbol2\Sbbol2Module;
use common\modules\certManager\models\Cert;
use Exception;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * @property Sbbol2Module $module
 */
class ConnectController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'auth', 'auth-error'],
                    ],
                ],
            ],
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'index' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if ($action->id === 'index') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actions()
    {
        return [
            'auth' => [
                'class' => AuthAction::class,
                'authCollectionInstance' => $this->module->authClientCollection,
                'successCallback' => [$this, 'onAuthSuccess'],
                'cancelUrl' => Url::to(['/sbbol2/connect/auth-error']),
            ],
        ];
    }

    public function actionIndex()
    {
        $this->cleanUpSession();

        $jwsRequest = Yii::$app->request->post('request');
        Yii::info("Got Sberbank registration request: $jwsRequest");

        $token = (new Parser())->parse((string)$jwsRequest);
        $senderTerminalAddress = $token->getClaim('terminalAddress', '') ?: null;
        $returnUrl = $token->getClaim('returnUrl', '') ?: null;

        if (!$this->isValidToken($token, $senderTerminalAddress)) {
            return $this->redirectBack(true, $returnUrl, $senderTerminalAddress);
        }

        Yii::$app->session->set('terminalAddress', $senderTerminalAddress);
        Yii::$app->session->set('returnUrl', $returnUrl);

        return $this->redirect(['/sbbol2/connect/auth', 'authclient' => 'sberbank']);
    }

    public function actionAuthError()
    {
        return $this->redirectBack(true);
    }

    public function onAuthSuccess(SberbankOpenIdConnect $client)
    {
        $jobId = Yii::$app->resque->enqueue(
            ImportSberbankCustomerJob::class,
            [
                'authToken' => serialize($client->getAccessToken()),
                'terminalAddress' => Yii::$app->session->get('terminalAddress'),
                'sendCustomerSettings' => true,
            ]
        );
        $isError = empty($jobId);
        return $this->redirectBack($isError);
    }

    private function cleanUpSession()
    {
        Yii::$app->session->remove('returnUrl');
        Yii::$app->session->remove('terminalAddress');
    }

    private function redirectBack(bool $isError = false, string $returnUrl = null, string $terminalAddress = null)
    {
        if ($returnUrl === null) {
            $returnUrl = Yii::$app->session->get('returnUrl');
        }
        if ($terminalAddress === null) {
            $terminalAddress = Yii::$app->session->get('terminalAddress');
        }

        $this->cleanUpSession();

        $result = $isError ? 'error' : 'success';
        $query = parse_url($returnUrl, PHP_URL_QUERY);
        $redirectUrl = $returnUrl . ($query ? '&' : '?') . "result=$result&terminalAddress=$terminalAddress";
        return $this->redirect($redirectUrl);
    }

    private function isValidToken(Token $token, string $senderTerminalAddress): bool
    {
        $senderCertificate = Cert::findByRole($senderTerminalAddress, Cert::ROLE_SIGNER_BOT, true);
        if ($senderCertificate === null) {
            Yii::info("Cannot find certificate for terminal $senderTerminalAddress");
            return false;
        }

        $signer = new Sha256();
        try {
            $isValid = $token->verify($signer, $senderCertificate->getCertificate()->body);
        } catch (Exception $exception) {
            Yii::info("Failed to validate signature, caused by: $exception");
            return false;
        }
        if (!$isValid) {
            Yii::info("Token signature is invalid");
            return false;
        }
        if ($token->isExpired()) {
            Yii::info('Token has expired');
            return false;
        }
        return true;
    }
}
