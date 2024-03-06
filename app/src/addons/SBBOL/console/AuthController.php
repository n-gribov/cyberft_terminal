<?php

namespace addons\SBBOL\console;

use addons\SBBOL\helpers\SecureRemotePassword;
use addons\SBBOL\models\SBBOLCustomer;
use addons\SBBOL\models\SBBOLKey;
use addons\SBBOL\models\soap\request\ChangePasswordRequest;
use addons\SBBOL\models\soap\request\PreChangePasswordRequest;
use addons\SBBOL\models\soap\request\PreLoginRequest;
use yii\helpers\VarDumper;

class AuthController extends BaseController
{
    public function actionIndex()
    {
        $this->run('/help', ['SBBOL/auth']);
    }

    public function actionChangePassword($user, $currentPassword, $newPassword)
    {
        $preLoginResponse = $this->module->transport->send(new PreLoginRequest(['userLogin' => $user]));
        list($currentPasswordSalt, $B, $sessionId, $returnCode, $serverMode) = $preLoginResponse->return;

        $secureRemotePassword = new SecureRemotePassword(
            $user,
            $currentPassword,
            $currentPasswordSalt,
            $B
        );

        $preChangePasswordResponse = $this->module->transport->send(new PreChangePasswordRequest(['sessionId' => $sessionId]));
        $newPasswordSalt = $preChangePasswordResponse->return;

        $changePasswordRequest = new ChangePasswordRequest([
            'sessionId' => $sessionId,
            'newPasswordData' => [
                $secureRemotePassword->getK(),
                $secureRemotePassword->getA(),
                $secureRemotePassword->calculateNewPasswordVerifier($newPassword, $newPasswordSalt),
            ]
        ]);

        $changePasswordResponse = $this->module->transport->send($changePasswordRequest);
        echo VarDumper::dumpAsString($changePasswordResponse) . "\n";
    }

    public function actionTestLoginByKey($customerId)
    {
        $customer = SBBOLCustomer::findOne($customerId);
        if ($customer === null) {
            echo "Customer is not found in database\n";
            return;
        }

        $key = SBBOLKey::findActiveByCustomer($customer->id);
        if ($key === null) {
            echo "Customer has no active key\n";
            return;
        }

        $loginResult = $this->module->transport->loginByKey($key);
        echo VarDumper::dumpAsString($loginResult) . "\n";

        if ($loginResult->isLoggedIn()) {
            $this->module->sessionManager->deleteSession($customer->id);
        }
    }

    public function actionFixLocality($customerId, $locality = null)
    {
        $customer = SBBOLCustomer::findOne($customerId);
        if ($customer === null) {
            echo "Customer is not found in database\n";
            return;
        }

        if (!$locality) {
            $locality = 'Москва';
        }

        echo "Do you want to set locality to $locality? (Y/N)\n";
        $s = strtoupper(trim(fgets(STDIN)));
        if ($s == 'Y') {
            echo "saving new locality for customer...\n";
            $customer->addressSettlement = $locality;
            $result = $customer->save();
            if ($result) {
                echo "OK\n";
            } else {
                die ("Could not save locality\n");
            }
        }
    }

    public function actionTestLoginByCredentials($customerId, $password = null)
    {
        $customer = SBBOLCustomer::findOne($customerId);
        if ($customer === null) {
            echo "Customer is not found in database\n";
            return;
        }

        if ($password) {
            if ($customer->password != $password) {
                echo "Do you want to use new password? (Y/N)\n";
                $s = strtoupper(trim(fgets(STDIN)));
                if ($s == 'Y') {
                    echo "saving new password for customer...\n";
                    $customer->password = $password;
                    $result = $customer->save();
                    if ($result) {
                        echo "OK\n";
                    } else {
                        die ("Could not save password\n");
                    }
                } else {
                    die("Then run this script without a password\n");
                }
            } else {
                die("You used the same password as current.\n");
            }
        }

        $loginResult = $this->module->transport->loginByCredentials($customer->login, $customer->password);
        echo VarDumper::dumpAsString($loginResult) . "\n";
        if ($loginResult->isLoggedIn()) {
            $this->module->sessionManager->deleteSession($customer->id);
        }
    }

    public function actionDeleteSessions()
    {
        $customers = SBBOLCustomer::find()->all();
        foreach ($customers as $customer) {
            $this->module->sessionManager->deleteSession($customer->id);
        }
    }
}
