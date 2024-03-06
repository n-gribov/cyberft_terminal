<?php

namespace addons\sbbol2\components;

use addons\sbbol2\authClient\SberbankOpenIdConnect;
use addons\sbbol2\models\Sbbol2CustomerAccessToken;
use addons\sbbol2\Sbbol2Module;
use Exception;
use Yii;
use yii\authclient\InvalidResponseException;
use yii\authclient\OAuthToken;
use yii\base\Component;
use yii\base\InvalidConfigException;

class ApiAccessTokenProvider extends Component
{
    public $tokenExpirationThreshold = 60;

    public function getForCustomer(int $customerId): string
    {
        $customerToken = $this->findToken($customerId);
        if ($customerToken === null) {
            throw new Exception("No active access token for customer $customerId found in database");
        }
        if ($this->shouldRefreshToken($customerToken)) {
            Yii::info('Will refresh access token');
            return $this->refreshToken($customerToken);
        }
        return $customerToken->accessToken;
    }

    public function customerHasActiveToken(int $customerId): bool
    {
        return $this->findToken($customerId) !== null;
    }

    /**
     * @param int $customerId
     * @return Sbbol2CustomerAccessToken|null
     */
    private function findToken(int $customerId)
    {
        return Sbbol2CustomerAccessToken::find()
            ->where(['customerId' => $customerId])
            ->andWhere(['isActive' => true])
            ->one();
    }

    private function shouldRefreshToken(Sbbol2CustomerAccessToken $tokenRecord): bool
    {
        if (empty($tokenRecord->accessTokenExpiryTime)) {
            return false;
        }
        $expiryTimestamp = strtotime($tokenRecord->accessTokenExpiryTime);
        return $expiryTimestamp - $this->tokenExpirationThreshold <= time();
    }

    private function refreshToken(Sbbol2CustomerAccessToken $customerToken): string
    {
        $token = new OAuthToken([
            'tokenParamKey' => 'access_token',
            'params' => ['refresh_token' => $customerToken->refreshToken],
        ]);
        $client = $this->getAuthClient();
        $client->validateJws = false;
        try {
            $newToken = $client->refreshAccessToken($token);
        } catch (InvalidResponseException $exception) {
            $response = $exception->response;
            $statusCode = $response ? $response->getStatusCode() : null;
            $content = $response ? $response->getContent() : null;
            Yii::info("Failed to refresh token, status: $statusCode, response: $content");
            if ($this->isClientAuthorizationRenewalRequired($exception)) {
                Yii::info("Will set token status to inactive, customer: {$customerToken->customerId}");
                $customerToken->isActive = false;
                // Сохранить модель в БД
                $customerToken->save();
            }
            throw $exception;
        }
        $this->updateCustomerToken($customerToken, $newToken);
        return $newToken->getToken();
    }

    private function updateCustomerToken(Sbbol2CustomerAccessToken $customerToken, OAuthToken $newToken)
    {
        $expiresIn = $newToken->getParam('expires_in');
        $expiryTime = $expiresIn
            ? date('Y-m-d H:i:s', time() + $expiresIn)
            : null;
        $customerToken->accessToken = $newToken->getToken();
        $customerToken->refreshToken = $newToken->getParam('refresh_token');
        $customerToken->accessTokenExpiryTime = $expiryTime;
        // Сохранить модель в БД
        $isSaved = $customerToken->save();
        if (!$isSaved) {
            throw new Exception(
                'Failed to save customer token to database, errors: '
                . var_export($customerToken->getErrors(), true)
            );
        }
    }

    private function isClientAuthorizationRenewalRequired(InvalidResponseException $exception): bool
    {
        $response = $exception->response;
        if (!preg_match('/^[34]\d\d$/', $response->getStatusCode())) {
            return false;
        }
        $data = $response->getData();
        $error = $data['error'] ?? null;
        return in_array($error, ['invalid_grant', 'unauthorized_client']);
    }

    private function getAuthClient(): SberbankOpenIdConnect
    {
        /** @var Sbbol2Module $module */
        $module = Yii::$app->getModule(Sbbol2Module::SERVICE_ID);
        $client = $module->authClientCollection->getClient('sberbank');
        if ($client instanceof SberbankOpenIdConnect) {
            return $client;
        }
        throw new InvalidConfigException('Client must be an instance of addons\sbbol2\authClient\SberbankOpenIdConnect');
    }
}
