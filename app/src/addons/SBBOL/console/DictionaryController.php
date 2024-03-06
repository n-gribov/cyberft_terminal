<?php

namespace addons\SBBOL\console;

use addons\SBBOL\models\SBBOLCustomer;
use addons\SBBOL\settings\SBBOLSettings;
use common\helpers\sbbol\SBBOLXmlSerializer;
use common\helpers\Uuid;
use common\models\sbbolxml\request\DictType;
use common\models\sbbolxml\request\Request;
use common\models\sbbolxml\response\Response;
use common\models\sbbolxml\SBBOLTransportConfig;

class DictionaryController extends BaseController
{
    /**
     * Метод выводит текст с подсказкой
     */
    public function actionIndex()
    {
        $this->run('/help', ['SBBOL/dictionary']);
    }

    public function actionGet(...$dictionaryTypes)
    {
        $customer = SBBOLCustomer::findOne(['isHoldingHead' => true]);
        if ($customer === null) {
            echo "Holding head customer is not found\n";
            return;
        }

        $requestDocument = (new Request())
            ->setRequestId((string)Uuid::generate(false))
            ->setVersion(SBBOLTransportConfig::EXCHANGE_FORMAT_VERSION)
            ->setProtocolVersion(SBBOLTransportConfig::PROTOCOL_VERSION)
            ->setOrgId($customer->id)
            ->setSender($customer->senderName);

        foreach ($dictionaryTypes as $dictionaryType) {
            $dict = (new DictType())->setDictId($dictionaryType);
            $requestDocument->addToDict($dict);
        }

        echo "Sending request...\n\n";
        $sessionId = $this->module->sessionManager->findOrCreateSession($customer->holdingHeadId);
        $responseBody = $this->sendRequestAndGetStatus($requestDocument, $sessionId);

        /** @var Response $responseDocument */
        $responseDocument = SBBOLXmlSerializer::deserialize($responseBody, Response::class);

        foreach ($responseDocument->getDict() as $dict) {
            $this->processDict($dict);
            echo "\n";
        }
    }

    private function processDict(\common\models\sbbolxml\response\DictType $dict)
    {
        echo "Processing {$dict->getDictId()} dictionary...\n";
        if ($dict->getErrorMessage()) {
            echo "Got error: {$dict->getErrorMessage()}\n";
            return;
        }
        if (empty($dict->getStep())) {
            echo "No data description found in response\n";
            return;
        }

        $this->downloadDictionary($dict);
    }

    private function downloadDictionary(\common\models\sbbolxml\response\DictType $dict)
    {
        $steps = $dict->getStep();
        if (count($steps) > 1) {
            throw new \Exception('Multiple part downloads are not supported');
        }
        $url = $this->createDownloadUrl($steps[0]->getPostFix());
        $content = file_get_contents($url);
        if ($content === false) {
            throw new \Exception("Failed to download file from $url");
        }
        $fileName = "dictionary-{$dict->getDictId()}.zip";
        file_put_contents($fileName, $content);
        echo "Dictionary is saved to $fileName\n";
    }

    private function createDownloadUrl($relativeUrl)
    {
        /** @var SBBOLSettings $settings */
        $settings = $this->module->settings;
        $gatewayUrl = $settings->gatewayUrl;
        if (empty($gatewayUrl)) {
            throw new \Exception('SBBOL gateway URL is not set');
        }
        $downloadUrlBase = preg_replace('#/upg$#', '', $gatewayUrl);

        return $downloadUrlBase . $relativeUrl;
    }
}
