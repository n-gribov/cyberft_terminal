<?php

namespace addons\SBBOL\components;

use addons\SBBOL\models\SBBOLCustomer;
use addons\SBBOL\models\SBBOLKey;
use addons\SBBOL\models\soap\request\GetRequestStatusSRPRequest;
use addons\SBBOL\SBBOLModule;
use common\helpers\sbbol\SBBOLXmlSerializer;
use common\helpers\Uuid;
use common\models\sbbolxml\response\Response;
use Yii;
use yii\base\Component;

class SBBOLSessionManager extends Component
{
    /** @var SBBOLTransport */
    private $transport;

    public function init()
    {
        parent::init();
        $module = Yii::$app->getModule(SBBOLModule::SERVICE_ID);
        $this->transport = $module->transport;
    }

    public function findOrCreateSession($customerId, $useLoginPassword = false)
    {
        $sessionId = $this->restoreSession($customerId);
        if (!$sessionId || !$this->isValidSessionId($customerId, $sessionId)) {
            $sessionId = $this->login($customerId, $useLoginPassword);
            $this->storeSession($customerId, $sessionId);
        }

        return $sessionId;
    }

    public function deleteSession($customerId)
    {
        Yii::$app->redis->del($this->getCacheKey($customerId));
    }

    private function storeSession($customerId, $sessionId)
    {
        Yii::$app->redis->set(
            $this->getCacheKey($customerId),
            $this->encrypt($sessionId)
        );
    }

    private function restoreSession($customerId)
    {
        $encryptedSessionId = Yii::$app->redis->get($this->getCacheKey($customerId));

        return $this->decrypt($encryptedSessionId);
    }

    private function getCacheKey($customerId)
    {
        return "sbbol-session-$customerId";
    }

    private function login($customerId)
    {
        $customer = SBBOLCustomer::findOne($customerId);
        if ($customer === null) {
            throw new \Exception("Cannot find customer $customerId");
        }

        $activeKey = SBBOLKey::findActiveByCustomer($customer->id);

        $loginResult = ($activeKey !== null)
            ? $this->transport->loginByKey($activeKey)
            : $this->transport->loginByCredentials($customer->login, $customer->password);

        if (!$loginResult->isLoggedIn()) {
            throw new \Exception("Failed to login as customer $customerId, error code: {$loginResult->getReturnCode()}");
        }

        return $loginResult->getSessionId();
    }

    private function isValidSessionId($customerId, $sessionId)
    {
        $request = new GetRequestStatusSRPRequest([
            'sessionId' => $sessionId,
            'orgId' => $customerId,
            'requests' => [Uuid::generate(false)->toString()],
        ]);
        $response = $this->transport->send($request, null, false);

        if (empty($response->return)) {
            $return = null;
        } else if (is_array($response->return)) {
            $return = $response->return[0];
        } else {
            $return = $response->return;
        }

        if (strpos($return, '<Response ') !== false) {
            /** @var Response $responseDocument */
            $responseDocument = SBBOLXmlSerializer::deserialize($return, Response::class);
            if (!empty($responseDocument->getErrors())) {
                $error = $responseDocument->getErrors()[0];
                if ($error->getCode() === '00000000-0000-0000-0000-000000000002') {
                    return false;
                }
            }
        }

        return true;
    }

    private function decrypt($encryptedValue)
    {
        if (!$encryptedValue) {
            return null;
        }
        return \Yii::$app->security->decryptByKey(
            base64_decode($encryptedValue),
            $this->getEncryptionKey()
        );
    }

    private function encrypt($value)
    {
        if (!$value) {
            return null;
        }

        return base64_encode(
            \Yii::$app->security->encryptByKey(
                $value,
                $this->getEncryptionKey()
            )
        );
    }

    private function getEncryptionKey()
    {
        return getenv('COOKIE_VALIDATION_KEY');
    }
}
