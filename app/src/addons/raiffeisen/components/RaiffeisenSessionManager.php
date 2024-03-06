<?php

namespace addons\raiffeisen\components;

use addons\raiffeisen\models\RaiffeisenCustomer;
use addons\raiffeisen\RaiffeisenModule;
use Yii;
use yii\base\Component;

class RaiffeisenSessionManager extends Component
{
    /** @var RaiffeisenTransport */
    private $transport;

    public function init()
    {
        parent::init();
        $module = Yii::$app->getModule(RaiffeisenModule::SERVICE_ID);
        $this->transport = $module->transport;
    }

    public function findOrCreateSession($customerId)
    {
        $sessionId = $this->restoreSession($customerId);
        if (!$sessionId) {
            $sessionId = $this->login($customerId);
            $this->storeSession($customerId, $sessionId);
        }
        return $sessionId;
    }

    public function deleteSession($customerId)
    {
        Yii::$app->redis->del($this->getCacheKey($customerId));
    }

    public function storeSession($customerId, $sessionId)
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
        return "raiffeisen-session-$customerId";
    }

    private function login($customerId)
    {
        $customer = RaiffeisenCustomer::findOne($customerId);
        if ($customer === null) {
            throw new \Exception("Cannot find customer $customerId");
        }

        $loginResult = $this->transport->loginByCredentials($customer->login, $customer->password);

        if (!$loginResult->isLoggedIn()) {
            throw new \Exception("Failed to login as customer $customerId");
        }

        return $loginResult->getSessionId();
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
