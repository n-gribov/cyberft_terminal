<?php

namespace addons\sbbol2\authClient;

use Lcobucci\JWT\Parser;
use Ramsey\Uuid\Uuid;
use Yii;
use yii\authclient\OAuth2;
use yii\web\HttpException;

class SberbankOpenIdConnect extends OAuth2
{
    public $scope = 'openid';
    public $issuerUrl;
    public $validateJws = true;

    public function buildAuthUrl(array $params = [])
    {
        $defaultParams = [
            'client_id' => $this->clientId,
            'response_type' => 'code',
            'redirect_uri' => $this->getReturnUrl(),
        ];
        if (!empty($this->scope)) {
            $defaultParams['scope'] = $this->scope;
        }

        if ($this->validateAuthState) {
            $authState = $this->generateAuthState();
            $authNonce = $this->generateAuthNonce();
            $this->setState('authState', $authState);
            $this->setState('authNonce', $authNonce);
            $defaultParams['state'] = $authState;
            $defaultParams['nonce'] = $authNonce;
        }

        return $this->composeUrl($this->authUrl, array_merge($defaultParams, $params));
    }

    protected function initUserAttributes()
    {
        return $this->api('ic/sso/api/oauth/user-info', 'GET');
    }

    /**
     * @param $request Request
     * @param $accessToken OAuthToken
     */
    public function applyAccessTokenToRequest($request, $accessToken)
    {
        // OpenID Connect requires bearer token auth for the user info endpoint
        $request->getHeaders()->set('Authorization', 'Bearer ' . $accessToken->getToken());
    }

    protected function defaultReturnUrl()
    {
        $params = Yii::$app->getRequest()->getQueryParams();
        // OAuth2 specifics:
        unset($params['code']);
        unset($params['state']);
        // OpenIdConnect specifics:
        unset($params['nonce']);
        unset($params['authuser']);
        unset($params['session_state']);
        unset($params['prompt']);
        $params[0] = Yii::$app->controller->getRoute();

        return Yii::$app->getUrlManager()->createAbsoluteUrl($params);
    }

    /*
     * @param array $tokenConfig token configuration.
     * @return OAuthToken token instance.
     */
    protected function createToken(array $tokenConfig = [])
    {
        if ($this->validateJws) {
            $jwsData = $this->parseJws($tokenConfig['params']['id_token']);
            $this->validateClaims($jwsData);
            $tokenConfig['params'] = array_merge($tokenConfig['params'], $jwsData);

            $authNonce = $this->getState('authNonce');
            if (!isset($jwsData['nonce']) || empty($authNonce) || strcmp($jwsData['nonce'], $authNonce) !== 0) {
                throw new HttpException(400, 'Invalid auth nonce');
            } else {
                $this->removeState('authNonce');
            }
        }

        return parent::createToken($tokenConfig);
    }

    private function parseJws(string $jws): array
    {
        try {
            $token = (new Parser())->parse($jws);
        } catch (\Exception $exception) {
            $message = YII_DEBUG ? 'Unable to parse JWS: ' . $exception->getMessage() : 'Invalid JWS';
            throw new HttpException(400, $message, $exception->getCode(), $exception);
        }

        return array_reduce(
            array_keys($token->getClaims()),
            function ($carry, $key) use ($token) {
                $carry[$key] = $token->getClaim($key);
                return $carry;
            },
            []
        );
    }

    private function validateClaims(array $claims)
    {
        if ($this->issuerUrl) {
            if (!isset($claims['iss']) || (strcmp(rtrim($claims['iss'], '/'), rtrim($this->issuerUrl, '/')) !== 0)) {
                throw new HttpException(400, 'Invalid "iss"');
            }
        }
        if (!isset($claims['aud']) || (strcmp($claims['aud'], $this->clientId) !== 0)) {
            throw new HttpException(400, 'Invalid "aud"');
        }
    }

    protected function generateAuthNonce(): string
    {
        return(string)Uuid::uuid4();
    }

    protected function generateAuthState(): string
    {
        return(string)Uuid::uuid4();
    }
}
