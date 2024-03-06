<?php
namespace addons\ISO20022\console;

use addons\ISO20022\models\ISO20022Type;
use addons\ISO20022\models\Pain001Type;
use common\base\ConsoleController;
use common\document\Document;
use common\helpers\Address;
use common\helpers\CryptoProHelper;
use common\helpers\FileHelper;
use common\models\CryptoproKey;
use common\models\cyberxml\CyberXmlDocument;
use common\models\Terminal;
use common\modules\transport\helpers\DocumentTransportHelper;
use common\modules\transport\models\CFTStatusReportType;
use Exception;
use SimpleXMLElement;
use Yii;
use yii\helpers\Console;

class DefaultController extends ConsoleController
{
    public function actionIndex()
    {
        $this->run('/help', ['ISO20022']);
    }

    /** @obsolete needs refactoring */
    public function actionTestSign($file)
    {
        echo "\n------------ testing cryptopro signing of iso20022 model ------------\n";

        $this->stdout("Check the license expiration before this test!\n", Console::FG_RED);

        $model = ISO20022Type::getModelFromString($file, true);

        $isEnabled = $this->module->settings->enableCryptoProSign;

        if ($isEnabled) {
            echo "cryptopro signing is enabled in settings\n";
        } else {
            echo "WARNING: cryptopro signing is disabled in settings\n";
        }

        echo "loaded typemodel\n";

        $driver = Yii::$app->cryptography->getDriverCryptoPro();

        echo "got driver instance\n";

        $passwordKey = getenv('COOKIE_VALIDATION_KEY');

        echo 'passwordKey   : ' . $passwordKey . "\n";

        $xml = new SimpleXMLElement((string) $model);

        $sender = null;

        foreach($xml->getDocNamespaces() as $strPrefix => $strNamespace) {
            if (empty($strPrefix)) {
                $strPrefix = "a"; // Assign an arbitrary namespace prefix.
            }
            $xml->registerXPathNamespace($strPrefix, $strNamespace);
        }

        $othr = $xml->xpath('//a:InitgPty/a:Id/a:OrgId/a:Othr');

        foreach($othr as $node) {
            if ((string) $node->SchmeNm->Prtry == 'CFTBIC') {
                $sender = (string) $node->Id;
            }
        }

        if (!$sender) {
            $node = $xml->xpath('//a:InitgPty/a:Id/a:OrgId');

            if ($model->getFullType() == 'pain.001.001.06') {
                $sender = (string) $node[0]->AnyBIC;
            } else {
                $sender = (string) $node[0]->BICOrBEI;
            }
        }

        $model->sender = Address::fixSender($sender);

        echo 'sender        : ' . $model->sender . "\n";

        $cryptoProSignKeys = CryptoproKey::findByTerminalId(Terminal::getIdByAddress($model->sender));

        if (empty($cryptoProSignKeys)) {
            echo "ERROR: cryptopro keys list is empty\n";

            return;
        }

        foreach ($cryptoProSignKeys as $cryptoProSignKey) {

            echo 'key serial    : ' . $cryptoProSignKey->serialNumber
                 . "\nkeyId         : " . $cryptoProSignKey->keyId . "\n";

            $signatureId = uniqid();

            echo 'signature id  : ' . $signatureId . "\n";

            $hash = $this->module->settings->useSerial
                    ? $cryptoProSignKey->serialNumber
                    : $cryptoProSignKey->keyId;

            echo 'hash          : ' . $hash . "\n";

            $password = Yii::$app->security->decryptByKey(base64_decode($cryptoProSignKey->password), $passwordKey);

            $xml = $model->getSignatureTemplate($signatureId, $hash);

            $pos = strpos($xml, '<ds:Signature');
            if ($pos === false) {
                echo "Error: no signature tag found in xml template:\n";
                echo $xml . "\n";

                return;
            }

            $pos2 = strpos($xml, '</ds:Signature>');

            echo "------------------- template -------------------\n";

            echo substr($xml, $pos, $pos2 + 15 - $pos) . "\n";

            echo "----------------- end template -----------------\n";

            $templatePath = $this->module->storeDataTemp($xml);

            echo "template path : " . $templatePath . "\n";

            if ($templatePath === false) {
                throw new Exception('Error saving xml template file for CryptoPro signing');
            }

            $tmpPath = Yii::getAlias('@temp/') . FileHelper::uniqueName();

            echo 'tmp path      : ' . $tmpPath . "\n";

            if (file_put_contents($tmpPath, '1') === false) {
                throw new Exception('Could not prepare output file for CryptoPro signing');
            }

            $documentIn = $templatePath;
            $documentOut = $tmpPath;
            $certificateHash = $cryptoProSignKey->keyId;
            $containerPin = $password;
            $signPath = "//*[@Id='{$model->getPrefixSignature()}{$signatureId}']";

            $command = 'LD_LIBRARY_PATH="/usr/local/openssl-1.1.1/lib/:${LD_LIBRARY_PATH}" cyberft-crypt sign --cert="' . $certificateHash . '" --provider=cryptopro --cpagent=builtin --sigpath="' . $signPath . '"';
            echo "--------------------- command ------------------\n";
            echo $command . "\n";

            $pipes = NULL;

            $body = file_get_contents($documentIn);

            $descriptorspec = [
                0 => ['pipe', 'r'],
                1 => ['file', $documentOut, 'w'],
                2 => ['pipe', 'w'],
            ];

            echo "------------ begin cryptopro output ------------\n";

            $process = proc_open($command, $descriptorspec, $pipes);
            if (!is_resource($process)) {
                throw new Exception("Run cyberft-crypt error!");
            }

            fwrite($pipes[0], base64_encode($containerPin).PHP_EOL.$body);
            fclose($pipes[0]);

            $error = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            if (!empty($error)) {
                throw new Exception($error);
            }

            proc_close($process);

            echo "------------- end cryptopro output -------------\n";

            echo "Signed.\n";

            unlink($templatePath);

            //echo "output       : " . file_get_contents($tmpPath) . "\n";

            $xml = simplexml_load_file($tmpPath, 'SimpleXMLElement', LIBXML_PARSEHUGE);

            unlink($tmpPath);

            $node = $xml->xpath("//*[@Id='{$model->getPrefixSignature()}{$signatureId}']")[0];

            if ($this->module->settings->useSerial) {
                $cert = openssl_x509_parse($cryptoProSignKey->certData);
                $node->KeyInfo->X509Data->X509SubjectName = $cert['name'];
            }

            $node->KeyInfo->KeyName = $hash;

            $xml = $xml->asXML();

            $pos = strpos($xml, '<ds:Signature');
            if ($pos === false) {
                echo "ERROR: no signature tag found in output xml:\n";
                echo $xml . "\n";

                return;
            }

            $pos2 = strpos($xml, '</ds:Signature>');

            echo "----------------- signed part ------------------\n";
            echo substr($xml, $pos, $pos2 + 15 - $pos) . "\n";

        }
    }

    public function actionCheckVerify($moduleName, $id)
    {
        $document = Document::findOne($id);

        $cyx = CyberXmlDocument::read($document->actualStoredFileId);

        $typeModel = $cyx->content->typeModel;

        $filePath = Yii::getAlias('@temp/') . FileHelper::uniqueName();
        $content = (string) $typeModel;

        echo $content . "\n";
        file_put_contents($filePath, $content);
        $verify = CryptoProHelper::verify($moduleName, $filePath, $cyx->senderId, $cyx->receiverId);
        unlink($filePath);

        var_dump($verify);

    }

    public function actionReexport($id1, $id2)
    {
        $documents = Document::find()
				->where(['>=', 'id', $id1])
				->andWhere(['<=', 'id', $id2])
				->andWhere(['status' => Document::STATUS_PROCESSED])
				->orderBy(['id' => SORT_ASC])
				->all();
        foreach ($documents as $document) {
            Yii::$app->resque->enqueue('\addons\ISO20022\jobs\ExportJob', ['documentId' => $document->id]);
			echo ("document {$document->id} sent to export job\n");
        }
    }

	public function actionStatusReport($id1, $id2, $override = null)
	{
		$documents = Document::find()
		->where(['>=', 'id', $id1])
		->andWhere(['<=', 'id', $id2])
		->andWhere(['direction' => Document::DIRECTION_IN])
		->andWhere(['type' => Pain001Type::TYPE])
		->orderBy(['id' => SORT_ASC])
		->all();

		echo count($documents) . "\n";
		//die();
		foreach ($documents as $document) {
			echo ("document {$document->id} type {$document->type}\n");

			$doc = Document::findOne([
				'type' => CFTStatusReportType::TYPE,
				'uuidReference' => $document->uuidRemote
			]);

			if ($doc) {
				echo "CFTStatusReport already exists, uuid: {$doc->uuid} status: {$doc->status}\n";
				if (!$override) {
					continue;
				}
			}

			echo "resending CFTStatusReport with refDocId {$document->uuid}\n";

            DocumentTransportHelper::statusReport(
            $document,
                [
                    'statusCode' => 'ACDC',
                    'errorCode' => '0',
                    'errorDescription' => ''
                ]
            );
		}
	}
}
