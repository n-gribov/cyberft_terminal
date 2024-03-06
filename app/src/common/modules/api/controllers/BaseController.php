<?php

namespace common\modules\api\controllers;

use common\modules\api\models\AccessToken;
use common\settings\AppSettings;
use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

abstract class BaseController extends Controller
{
    /** @var string|null */
    private $authorizedTerminalAddress;

    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            $this->authorize();
        } catch (UnauthorizedHttpException | ForbiddenHttpException $exception) {
            $this->sleep();
            throw $exception;
        } catch (\Exception $exception) {
            $this->sleep();
            Yii::info("API authorization failed, caused by $exception");
            throw new ForbiddenHttpException();
        }

        return true;
    }

    private function authorize(): void
    {
        $accessTokenHeader = Yii::$app->request->headers->get('Authorization');
        if (empty($accessTokenHeader)) {
            throw new UnauthorizedHttpException();
        }
        $accessToken = AccessToken::fromString($accessTokenHeader);

        /** @var AppSettings $settings */
        $settings = Yii::$app->settings->get('app', $accessToken->terminalAddress());
        if (!$settings->enableApi) {
            Yii::info("API is disabled, terminal: {$accessToken->terminalAddress()}");
            throw new ForbiddenHttpException();
        }

        if (empty($settings->apiSecret) || $settings->apiSecret !== $accessToken->secret()) {
            Yii::info("Invalid API access token provided, terminal: {$accessToken->terminalAddress()}");
            throw new ForbiddenHttpException();
        }

        $this->authorizedTerminalAddress = $accessToken->terminalAddress();
    }

    private function sleep(): void
    {
        sleep(rand(2, 8));
    }

    protected function getAuthorizedTerminalAddress(): ?string
    {
        return $this->authorizedTerminalAddress;
    }
}
