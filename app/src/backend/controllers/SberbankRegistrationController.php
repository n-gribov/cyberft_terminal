<?php

namespace backend\controllers;

use common\base\Controller;
use common\models\Terminal;
use common\models\User;
use common\models\UserTerminal;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Response;

class SberbankRegistrationController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['commonSettings'],
                    ],
                ],
            ],
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'create-request' => ['post'],
                ],
            ],
        ];
    }

    public function actionCreateRequest()
    {
        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        $defaultErrorMessage = Yii::t('app/autobot', 'Failed to create terminal registration request');

        $terminalId = Yii::$app->request->post('terminalId');
        $terminal = $this->findTerminal($terminalId);
        if ($terminal === null) {
            return [
                'success' => false,
                'errorMessage' => $defaultErrorMessage,
            ];
        }

        list($privateKey, $password) = $this->getAutobotPrivateKeyAndPassword($terminal->terminalId);
        if (empty($privateKey)) {
            $errorMessage = Yii::t(
                'app/autobot',
                'Not found the used for signing key of the controller to the terminal {terminalId}',
                ['terminalId' => $terminal->terminalId]
            );
            return [
                'success' => false,
                'errorMessage' => $errorMessage,
            ];
        }

        $key = new Key($privateKey, $password);
        $signer = new Sha256();
        $returnUrl = Url::to(['/sberbank-registration/process-result'], true);

        try {
            $token = (new Builder())
                ->withClaim('terminalAddress', $terminal->terminalId)
                ->withClaim('returnUrl', $returnUrl)
                ->expiresAt(time() + 120)
                ->getToken($signer, $key);
            return [
                'success' => true,
                'request' => (string)$token,
            ];
        } catch (\Exception $exception) {
            Yii::error("Failed to create JWS token, caused by: $exception");
            return [
                'success' => false,
                'errorMessage' => $defaultErrorMessage,
            ];
        }
    }

    public function actionProcessResult(string $terminalAddress, string $result)
    {
        $terminal = Terminal::findOne(['terminalId' => $terminalAddress]);
        $flashMessage = $result === 'success'
            ? Yii::t('app/autobot', 'Terminal has been successfully registered in Sberbank')
            : Yii::t('app/autobot', 'Failed to register terminal in Sberbank');
        $flashKey = $result === 'success' ? 'success' : 'error';
        // Поместить в сессию флаг сообщения о результате регистрации
        Yii::$app->session->setFlash($flashKey, $flashMessage);
        // Перенаправить на страницу индекса
        return $this->redirect([
            '/autobot/terminals/index',
            'id' => $terminal->id,
            'tabMode' => 'tabSbbol2Integration',
        ]);
    }

    private function userHasTerminalAccess($terminalId)
    {
        // Получить модель пользователя из активной сессии
        $adminIdentity = Yii::$app->user->identity;
        if ($adminIdentity->role == User::ROLE_ADDITIONAL_ADMIN) {
            $availableTerminals = UserTerminal::getUserTerminalIds($adminIdentity->id);
            return isset($availableTerminals[$terminalId]);
        }
        return true;
    }

    private function findTerminal($terminalId)
    {
        if (!$this->userHasTerminalAccess($terminalId)) {
            Yii::info("User has no access to terminal $terminalId");
            return null;
        }

        $terminal = Terminal::findOne($terminalId);
        if ($terminal === null) {
            Yii::info("Terminal $terminalId is not found");
            return null;
        }
        return $terminal;
    }

    private function getAutobotPrivateKeyAndPassword(string $terminalAddress): array
    {
        $primaryAutobot = Yii::$app->exchange->findAutobotUsedForSigning($terminalAddress);
        if ($primaryAutobot === null) {
            Yii::info("Cannot find primary autobot for terminal $terminalAddress");
            return [null, null];
        }
        if (empty($primaryAutobot->privateKey)) {
            Yii::info("Cannot find autobot private key for terminal $terminalAddress");
            return [null, null];
        }

        $terminalData = Yii::$app->exchange->findTerminalData($terminalAddress);
        $password = @$terminalData['passwords'][$primaryAutobot->id];

        return [$primaryAutobot->privateKey, $password];
    }
}
