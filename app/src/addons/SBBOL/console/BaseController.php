<?php

namespace addons\SBBOL\console;

use addons\SBBOL\models\soap\request\GetRequestStatusSRPRequest;
use addons\SBBOL\models\soap\request\SendRequestsSRPRequest;
use addons\SBBOL\SBBOLModule;
use common\base\ConsoleController;
use common\helpers\sbbol\SBBOLXmlSerializer;
use common\models\sbbolxml\request\Request;

/**
 * @property SBBOLModule $module
 */
abstract class BaseController extends ConsoleController
{
    public function beforeAction($action)
    {
        $parentResult = parent::beforeAction($action);
        if (!$parentResult) {
            return false;
        }

        return $this->ensureOsUser('www-data');
    }

    protected function ensureOsUser($requiredUserLogin)
    {
        $userLogin = trim(shell_exec('whoami'));
        if ($userLogin !== $requiredUserLogin) {
            echo "Please, run this command as $requiredUserLogin\n";
            return false;
        }

        return true;
    }

    protected function sendRequestAndGetStatus(Request $requestDocument, $sessionId)
    {
        $requestXmlContent = SBBOLXmlSerializer::serialize($requestDocument);
        $requestMessage = new SendRequestsSRPRequest([
            'sessionId' => $sessionId,
            'requests'  => SBBOLXmlSerializer::createCdataNode('ns1', 'requests', $requestXmlContent)
        ]);

        $this->module->transport->send($requestMessage);

        $statusRequestMessage = new GetRequestStatusSRPRequest([
            'sessionId' => $sessionId,
            'orgId' => $requestDocument->getOrgId(),
            'requests' => [$requestDocument->getRequestId()]
        ]);

        $n = 1;
        while (true) {
            sleep(3);

            $statusResponseMessage = $this->module->transport->send($statusRequestMessage);
            $return = $statusResponseMessage->return;

            if (!is_scalar($return) || strpos($return, '<!--') !== 0) {
                return $return;
            }

            echo "Request status: $return\n";

            $n++;
            if ($n > 100) {
                throw new \Exception('Too much attempts');
            }
        }
    }
}
