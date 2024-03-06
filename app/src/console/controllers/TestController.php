<?php
namespace console\controllers;

use addons\edm\helpers\Converter;
use addons\edm\models\Statement\StatementTypeConverter;
use addons\ISO20022\helpers\ISO20022Helper;
use addons\ISO20022\ISO20022Module;
use addons\ISO20022\models\Auth026Type;
use addons\ISO20022\models\Pain001Type;
use addons\sbbol2\apiClient\api\StatementApi;
use addons\swiftfin\models\containers\swift\SwtContainer;
use addons\swiftfin\SwiftfinModule;
use common\components\storage\StoredFile;
use common\components\xmlsec\xmlseclibs\XMLSeclibsHelper;
use common\document\Document;
use common\helpers\CryptoProHelper;
use common\helpers\FileHelper;
use common\helpers\StringHelper;
use common\helpers\Uuid;
use common\helpers\ZipHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\certManager\components\ssl\X509FileModel;
use common\modules\certManager\models\Cert;
use common\states\in\DocumentDuplicateExportStep;
use common\states\in\DocumentInState;
use DOMDocument;
use DOMXPath;
use PharData;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SimpleXMLElement;
use UnexpectedValueException;
use Yii;
use yii\base\ErrorException;
use yii\console\Controller;
use yii\helpers\Console;
use ZipArchive;

class TestController extends Controller
{
    public function actionAddParticipantCert($file)
    {
        $model = new Cert();
        $model->setScenario('create');

        if (!is_file($file)) {
            Console::error(Yii::t('app', 'Error: Certificate file "{file}" not found', ['file' => $file]));
            return Controller::EXIT_CODE_ERROR;
        }
        try {
            $model->addCertificate($file); // Add cert as common file
        } catch (ErrorException $ex) {
            Console::output(Yii::t('app', 'Participant certificate can\'t be installed ({message})', ['message' => $ex->getMessage()]));
            return Controller::EXIT_CODE_ERROR;
        }

        // Если модель успешно сохранена в БД
        if ($model->save()) {
            Console::output(Yii::t('app', 'Participant certificate successfully installed'));
        } else {
            if ($model->hasErrors()) {
                // Here dumping all error messages
                Console::error(Yii::t('app', 'Error: {message}', ['message' => $model->getErrorsSummary()]));
            } else {
                Console::error(Yii::t('app', 'Error: Unable to save cert to the db'));
            }
            return Controller::EXIT_CODE_ERROR;
        }

        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Читаем настройки установки
     * @return array
     * @deprecated
     */
    protected function getSetup()
    {
        if ($this->_setup) {
            return $this->_setup;
        }
        if (is_file(Yii::getAlias('@project') . '/setup/params.json')) {
            $this->_setup = json_decode(file_get_contents(Yii::getAlias('@project') . '/setup/params.json'), true);
        }

        return $this->_setup;
    }

    public function actionCacheWarmup()
    {
        foreach (Yii::$app->addon->getRegisteredAddons() as $addonId => $addon) {
            $warmup = $addon->cacheWarmup('elastic');

            if ($warmup) {
                echo 'Starting cache warmup for addon ' . $addonId . PHP_EOL;
                Yii::$app->elasticsearch->deleteType($addonId);

                foreach ($warmup as $model) {
                    Yii::$app->elasticsearch->putDocument($model);
                }
            }
        }
    }

    public function actionTestMassiveisopain($id, $count, $file)
    {
        $xml = simplexml_load_file($file);
        for ($x = 0; $x < $count; $x++) {
            $xml->CstmrCdtTrfInitn->GrpHdr->MsgId = uniqid('CI_document_', true);
            $fileName = '../import/ISO20022/' . $id . '/pain001_' . $x . '.xml';
            file_put_contents(
                $fileName,
                $xml->saveXML()
            );
            echo $x . ': ' . $fileName . "\n";
        }
    }

    public function actionTestMassiveisocamt053($count, $file)
    {
        $xml = simplexml_load_file($file);
        for ($x = 0; $x < $count; $x++) {
            $xml->BkToCstmrStmt->GrpHdr->MsgId = uniqid('CI_document_', true);
            $fileName = '../import/ISO20022/' . Yii::$app->exchange->defaultTerminalId . '/camt053_' . $x . '.xml';
            file_put_contents(
                $fileName,
                $xml->saveXML()
            );
            echo $x . ': ' . $fileName . "\n";
        }
    }

    public function actionTestMassiveisocamt054($count, $file)
    {
        $xml = simplexml_load_file($file);
        for ($x = 0; $x < $count; $x++) {
            $xml->BkToCstmrDbtCdtNtfctn->GrpHdr->MsgId = uniqid('CI_document_', true);
            $fileName = '../import/ISO20022/' . Yii::$app->exchange->defaultTerminalId . '/camt054_' . $x . '.xml';
            file_put_contents(
                $fileName,
                $xml->saveXML()
            );
            echo $x . ': ' . $fileName . "\n";
        }
    }

    public function actionTestMassiveSwift($count, $file)
    {
        for ($x = 0; $x < $count; $x++) {
            $fileName = '../import/swiftfin/swift' . '/swift_test_massive' . $x . '.swf';
            copy($file, $fileName);
            chmod($fileName, 0775);
            $user = 'www-data';
            chown($fileName, $user);

            echo $x . ': ' . $fileName . "\n";
        }
    }

    public function actionTestMassiveEdm($count, $file)
    {
        $xml = simplexml_load_file($file);
        for ($x = 0; $x < $count; $x++) {
            $xml->Header->DocId = uniqid('CI_document_EDM_', true);
            $fileName = '../import/edm/in' . '/edm_test_massive' . $x . '.xml';
            file_put_contents(
                $fileName,
                $xml->saveXML()
            );
            echo $x . ': ' . $fileName . "\n";
        }
    }

    public function actionTestCryptoProExpired()
    {
        // Проверка актуальности лицензии КриптоПро
        if (!CryptoProHelper::checkCPLicense()) {
            // Если есть хотя бы одна запись об истекшей лицензии
            // делаем запись в журнал событий
            echo 'Log event' . PHP_EOL;
            // Зарегистрировать событие просроченного сертификата Криптопро в модуле мониторинга
            Yii::$app->monitoring->log('user:CryptoProCertExpired', '', '');
        }
    }

    public function actionTruncateTables()
    {
        echo "********************************************************\n";
        echo "*                                                      *\n";
        echo "*    THIS COMMAND WILL TRUNCATE ALL DOCUMENT TABLES    *\n";
        echo "*                                                      *\n";
        echo "*                  ARE YOU SURE? (Y/N)                 *\n";
        echo "*                                                      *\n";
        echo "********************************************************\n";

        $line = strtoupper(trim(fgets(STDIN)));

        if ($line !== 'Y') {
            echo "Aborted.\n";

            exit(0);
        }

        $tables = [
            'message',
            'document',
            'documentExtEdm',
            'documentExtEdmForeignCurrencyOperation',
            'documentExtEdmPaymentOrder',
            'documentExtEdmPaymentRegister',
            'documentExtEdmStatement',
            'documentExtEdmStatementRequest',
            'documentExtFileAct',
            'documentExtFinZip',
            'documentExtISO20022',
            'documentExtSwiftFin',
            'edm_paymentOrder',
            'edm_paymentRegister',
            'monitor_log',
            'storage',
            'import_errors',
            'monitor_checker',
            'monitor_checker_settings',
            'participant_BICDir',
            //'contractRegistrationRequest',
            'contractRegistrationRequestNonresident',
            'contractRegistrationRequestPaymentSchedule',
            'contractRegistrationRequestTranche',
            'edm_foreignCurrencyOperationInformationExt',
            'vtb_RegisterCurPayDocCur',
            //'edmForeignCurrencyOperationInformation',
            //'edmForeignCurrencyOperationInformationItem',
            //'edmConfirmingDocumentInformation',
        ];

        $command = Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0');
        $command->execute();

        foreach ($tables as $table) {
            $command = Yii::$app->db->createCommand('truncate table ' . $table);
            echo 'truncating table ' . $table . "...\n";
            $command->execute();
        }

        $command = Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=1');
        $command->execute();
    }

    public function actionTestVerify($file)
    {
        $cyxDoc = new CyberXmlDocument();
        $cyxDoc->loadXml(file_get_contents($file));

        echo 'SENDER: ' . $cyxDoc->senderId . PHP_EOL;

        $typeModel = $cyxDoc->content->getTypeModel();

        $mySignVerifier = Yii::$app->xmlsec;

        $dom = new DOMDocument();
        $dom->loadXML((string)$typeModel);

        echo $dom->saveXML() . "\n";

        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('doc', 'http://cyberft.ru/xsd/edm.02');
        $xpath->registerNamespace('data', 'http://cyberft.ru/xsd/cftdata.02');
        $xpath->registerNamespace('cftsign', 'http://www.w3.org/2000/09/xmldsig#');
        $query = '/' . XMLSeclibsHelper::nsNode('doc', 'PaymentRegister')
            . '/' . XMLSeclibsHelper::nsNode('data', 'Signatures')
            . '/' . XMLSeclibsHelper::nsNode('cftsign', 'Signature');

        $signatures = $xpath->query($query, $dom);

		$myCertManager = Yii::$app->getModule('certManager');

        foreach ($signatures as $signature) {
            if (($fingerprint = $mySignVerifier->getFingerprint($signature)) === false) {
                die("Unable to find fingerprint inside XMLDSIG signature\n");
            }

            echo 'fingerprint = ' . $fingerprint . "\n";

            if (($myCert = $myCertManager->getCertificateByAddress($cyxDoc->senderId, $fingerprint)) === null) {
                die("No certificate found: {$fingerprint}\n");
            }

            if (!$myCert->isActive) {
                die("Certificate expired: {$fingerprint}\n");
            }

            // Пропускаем проверку подписи, если сертификат Крипто Про
            if ($myCert->isCryptoPro()) {

                echo "it's cryptopro\n";

                continue;
            }

            if (!$mySignVerifier->verifySignature($signature, $myCert->body)) {
                // Не продолжаем проверку, если произошла ошибка верификации
                die("Verification failed\n");
            }

            echo "OK!\n";
        }
    }

    public function actionReexport($type, $beginId, $endId = null)
    {
        if (is_null($endId)) {
            $endId = $beginId;
        }

        $documents = Document::find()
            ->where(['type' => $type])
            ->andWhere(['direction' => 'IN'])
            ->andWhere(['>=', 'id', $beginId])
            ->andWhere(['<=', 'id', $endId])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        foreach ($documents as $document) {
            echo 're-exporting document ' . $document->id . "\n";

            $state = new DocumentInState();
            $state->document = $document;
            $state->cyxDoc = CyberXmlDocument::read($document->actualStoredFileId);
            $state->module = Yii::$app->registry->getTypeModule($document->type);
            $step = new DocumentDuplicateExportStep();
            $step->state = $state;
            $step->run();
        }
    }

    public function actionTestSwift($file)
    {
        $data = str_replace("\r\n", "\n", file_get_contents($file));
        $data = str_replace("\n", "\r\n", $data);

        $swtContainer = new SwtContainer();
        $swtContainer->loadData($data);

        $mt = SwiftfinModule::getInstance()->mtDispatcher->instantiateMt(
            $swtContainer->getcontentType(),
            ['owner' => $swtContainer]
        );

        $mt->setBody($swtContainer->getContent());

        $parsed = $mt->parseForTags();

        $testChars = "ABVGDEoJZIiKLMNOPRSTUFHCcQqxYXeuaj0123456789()?+npd,/\\-.:bsvzrmf\n\r' ";
        $testCharsReplace = str_repeat('-', strlen($testChars));

        foreach ($parsed as $tag) {
            $tagName = $tag['name'];
            $tagValue = $tag['value'];
            if ($tagValue) {
                $length = strlen($tagValue);
                $result = strtr($tagValue, $testChars, $testCharsReplace);
                if ($result !== str_repeat('-', $length)) {
                    $mt->addError('body', 'Tag ' . $tagName . ' contains invalid characters: ' . str_replace('-', '', $result));
                }
            }
        }

        if ($mt->hasErrors() || !$mt->validate()) {
            echo $mt->getReadableErrors() . "\n";
        } else {
            echo "validated OK.\n";
        }
    }

    public function actionTestSwiftTypes()
    {
        $mtDispatcher = SwiftfinModule::getInstance()->mtDispatcher;
        $types = $mtDispatcher->getRegisteredTypes();
        foreach ($types as $type => $params) {
            $numType = substr($type, 2);
            echo $type . ' : ';
            //if ($type != 'MT200') {
            $mt = $mtDispatcher->instantiateMt($numType);
            echo $mt->getType();

            if ($mt->getType() == $numType) {
                echo ' OK';
            } else {
                echo ' ERROR';
            }
            //}

            echo PHP_EOL;
        }

        echo PHP_EOL;
    }

    public function actionTestSdk($privateKeyPassword)
    {
        //var_dump(hash_algos());
        $filePath = '../import/_private_key.pem';

        $content = file_get_contents($filePath);
        echo $content . "\n";

        $key = openssl_pkey_get_private($content, $privateKeyPassword);
        var_dump($key);

        while ($msg = openssl_error_string()) {
            echo $msg . "\n";
        }

        $body = 'this is for sign';
        $signature = '';

        if (!openssl_sign($body, $signature, $key)) { //, $this->openSSLAlgoMapping[$this->digestMethod]))
            throw new UnexpectedValueException('Unable to sign document. Error: ' . openssl_error_string());
        }

        var_dump(base64_encode($signature));

        $certPath = '../import/_certificate.crt';

        $certificate = file_get_contents($certPath);

        $publicKey = openssl_pkey_get_public($certificate);

        var_dump($publicKey);

        var_dump(openssl_verify($body, $signature, $publicKey));
        while ($msg = openssl_error_string()) {
            echo $msg . "\n";
        }

    }

    public function actionTestSftp()
    {
        $resource = Yii::$app->registry->getImportResource(ISO20022Module::SERVICE_ID);

        $receivers = array_values($resource->getDirSubfolders($resource->path, false));
        foreach ($receivers as $receiverId) {

            echo "receiver = $receiverId\n";
            $files = $resource->getContents($resource->path . '/' . $receiverId);

            var_dump($files);
        }
    }


    function actionTestBigCamt($fileNameBig, $fileNameSmall, $address, $count = 1)
    {
        $data = file_get_contents($fileNameSmall);
        $msgIdPos = strpos($data, '<MsgId>');
        if ($msgIdPos === false) {
            die("MsgId tag not found\n");
        }

        $msgIdEndPos = strpos($data, '</MsgId>');
        if ($msgIdEndPos === false) {
            die("MsgId end tag not found\n");
        }

        $begin = substr($data, 0, $msgIdPos + 7);
        $end = substr($data, $msgIdEndPos);
        $baseFileName = basename($fileNameSmall);
        for ($i = 0; $i < $count; $i++) {
            file_put_contents(
                Yii::getAlias('@import') . '/ISO20022/' . $address . '/' . $i . '_' . $baseFileName,
                $begin . Yii::$app->security->generateRandomString(35) . $end
            );
        }

        $data = file_get_contents($fileNameBig);
        $msgIdPos = strpos($data, '<MsgId>');
        if ($msgIdPos === false) {
            die("MsgId tag not found\n");
        }

        $msgIdEndPos = strpos($data, '</MsgId>');
        if ($msgIdEndPos === false) {
            die("MsgId end tag not found\n");
        }

        $begin = substr($data, 0, $msgIdPos + 7);
        $end = substr($data, $msgIdEndPos);
        $baseFileName = basename($fileNameBig);
        for ($i = 0; $i < 1; $i++) {
            file_put_contents(
                Yii::getAlias('@import') . '/ISO20022/' . $address . '/' . $i . '_' . $baseFileName,
                $begin . Yii::$app->security->generateRandomString(35) . $end
            );
        }
    }

    public function actionIsoZip($path)
    {
        $testfiles = $this->DirContents($path);

        //начинаем перебор всех файлов в папке
        foreach ($testfiles as $num => $name) {
            echo "--------------\n";
            echo 'Секция ' . $num . "\n";
            echo 'Файл ' . $name . "\n";

            //считаем содержимое внутри и количество файлов
            for ($i = 0; $i <= $num; $i++) {
                $file = $name;
                $dir = dirname($file);

                //открываем архив
                $zip = new ZipArchive;
                $res = $zip->open($file);

                //проверяем, архив ли это
                if ($res === True) {
                    $numfiles = $zip->numFiles;
                    echo 'Количество файлов в архиве: ' . $numfiles . "\n";

                    //записываем массив с содержанием архива
                    for ($k = 0; $k < $numfiles; $k++) {
                        $filenames[] = $zip->getNameIndex($k);
                    }

                    //отдаем ошибку, если zip не открывается
                } else {
                    echo 'Файл ' . $file . " неверного формата\n";

                    break;
                }

                //проходим по массиву в поиске xml
                foreach ($filenames as $key => $value) {
                    //Если файлов xml нет, то отдаем result null
                    $replace = preg_replace("/.*?\./", '', $value);
                    if ($numfiles < 2 && $replace !== 'xml') {
                        $result = null;
                    }
                    $arrreplace = str_split($replace, 100);
                    foreach ($arrreplace as $number => $val) {
                        $match = preg_match("/xml\z/", $val);
                        if ($match === 1) {
                            $result = $value;

                        }
                    }
                }
                /*
                 * Если совпадений нет, то просто кидаем файл в папку
                */
                if ($result == null) {
                    echo 'Архив ' . $file . " не содержит xml\n";
                    $newfile = '../import/ISO20022/' . Yii::$app->exchange->defaultTerminalId . "/" . basename($file);
                    copy($file, $newfile);
                    echo 'Файл был скопирован в  ' . $newfile . "\n--------------\n";

                    break;
                }

                /*
                * Если совпадения есть, то прогоняем полную итерацию.
                */
                $res = $zip->extractTo($dir, $result);
                if ($res === true) {
                    echo 'file ' . $result . ' was extracted to ' . $dir . "\n";
                } else {
                    echo 'failed';
                }
                $zip->close();
                $extfile = $result;
                try {
                    $this->ChangeId($dir . '/' . $extfile);
                } catch (\Exception $ex) {
                    break;
                }
                $this->WriteZip($dir . '/' . $extfile, $file);
                $newfile = '../import/ISO20022/' . Yii::$app->exchange->defaultTerminalId . '/' . basename($file);
                copy($file, $newfile);

                echo 'File was copied to ' . $newfile . "\n--------------\n";
                unlink($dir . '/' . $extfile);

                break;
            }
        }
    }

    private function DirContents($path)
    {
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        $files =[];
        foreach ($rii as $file) {
            if ($file->isDir()){
                continue;
            }
            $files[] = $file->getPathname();
        }
        return $files;
    }

    private function ChangeId($file)
    {
        $xml = simplexml_load_file($file);
        foreach ($xml->getNamespaces() as $key => $value) {

            $filetypes = '/(auth.026|auth.025|auth.024)/';

            if (preg_grep('/auth.024/', explode("\n", $value))) {
                $xml->PmtRgltryInfNtfctn->GrpHdr->MsgId = uniqid('auth024_', true);
                file_put_contents($file, $xml->saveXML());
                $case = 'auth.024';
            } else if (preg_grep('/auth.025/', explode("\n", $value))) {
                $xml->CcyCtrlSpprtgDocDlvry->GrpHdr->MsgId = uniqid('auth025_', true);
                file_put_contents($file, $xml->saveXML());
                $case = 'auth.025';
            } else if (preg_grep('/auth.026/', explode("\n", $value))) {
                $xml->CcyCtrlReqOrLttr->GrpHdr->MsgId = uniqid('auth026_', true);
                file_put_contents($file, $xml->saveXML());
                $case = 'auth.026';
            } else {
                preg_match($filetypes, $value, $matches, PREG_OFFSET_CAPTURE, 0);
                if ($matches !== null) {
                    $xml->CcyCtrlReqOrLttr->GrpHdr->MsgId = uniqid('iso_files_', true);
                    $case = 'Невозможно определить тип документа';
                    file_put_contents($file, $xml->saveXML());
                }
            }
        }
        echo 'document type ' . $case . "\n";
        echo 'uuid was changed in ' . $file . "\n";
    }

    private function WriteZip($file, $archive)
    {
        $filename = basename($file);
        $zip = new ZipArchive();
        $res = $zip->open($archive);
        if ($res === true) {
            $zip->addFile($file, $filename);
            $zip->close();
        } else {
            echo 'failed';
            die;
        }
    }

    public function actionSetSignLevel($count_manual, $cryptopro_option)
    {
        $manual_options = [ '0', '1', '2', '3', '4', '5', '6', '7'];
        $crypto_pro_options = ['Yes', 'No'];

        if (!in_array($count_manual, $manual_options)){
            echo "Error!\nValid Parameters for first argument:\n 0-7\n";
        } else if (!in_array($cryptopro_option, $crypto_pro_options)) {
            echo "Error!\nValid Parameters for second argument: \nYes (enable crypto-pro signing)\nNo (disable crypto-pro signing)\n";
            die();
        }
        else {
            if ($cryptopro_option === 'Yes') {
                Yii::$app->getModule('ISO20022')->settings->enableCryptoProSign = '1';
                // Сохранить настройки модуля в БД
                Yii::$app->getModule('ISO20022')->settings->save();
                Yii::$app->getModule('ISO20022')->settings->enableCryptoProSign;
            } else {
                Yii::$app->getModule('ISO20022')->settings->enableCryptoProSign = '0';
                // Сохранить настройки модуля в БД
                Yii::$app->getModule('ISO20022')->settings->save();
                Yii::$app->getModule('ISO20022')->settings->enableCryptoProSign;
            }

            $terminalSettings = Yii::$app->settings->get('app', Yii::$app->exchange->getDefaultTerminalId());
            $terminalSettings->usePersonalAddonsSigningSettings = false;
            $terminalSettings->useAutosigning = false;
            $terminalSettings->qtySignings = $count_manual;
            // Сохранить модель в БД
            $terminalSettings->save();
        }
        echo 'Setting ' . $count_manual . ' manual sign option and ' . $cryptopro_option . " to crypto-pro sign\n";
    }

    public function actionTestCryptoPro()
    {
        $collection = CryptoProHelper::getCertInfo();
        var_export($collection->getContainers());
    }

    public function actionTestVerify2($id, $containerPos = 1, $signaturePos = 1)
    {
        $document = Document::findOne($id);
        $cyxDoc = null;

        if ($document->isEncrypted) {
            Yii::$app->exchange->setCurrentTerminalId($document->originTerminalId);
            $data = Yii::$app->storage->decryptStoredFile($document->actualStoredFileId);

            $cyxDoc = new CyberXmlDocument();
            $cyxDoc->loadFromString($data);
        } else {
            $cyxDoc = CyberXmlDocument::read($document->actualStoredFileId);
        }

        var_export($cyxDoc->verify());
        echo "\n";
    }

    public function actionTestContent($id, $full = false)
    {
        $document = Document::findOne($id);
        echo "\n******* DOCUMENT DATA ******\n" . var_export($document->attributes, true) . "\n";
        $storedFile = Yii::$app->storage->get($document->actualStoredFileId);
        $output = $full ? $storedFile->getData() : StringHelper::packString($storedFile->getData(), 4096);
        echo "\n******* STORAGE DATA ******\n" . $output . "\n";
        $cyx = CyberXmlDocument::read($document->actualStoredFileId);
        $output = $full ? $cyx->saveXML() : StringHelper::packString($cyx->saveXML(), 4096);
        echo "*********** CYX ***********\n" . $output . "\n";
        $typeModel = $cyx->getContent()->getTypeModel();
        echo "******** MODEL DATA *******\n" . (string) $typeModel . "\n\n";
    }

    public function actionTestFile($path, $full = false)
    {
        $cyx = new CyberXmlDocument();
        $cyx->loadXml(file_get_contents($path));
        $output = $full ? $cyx->saveXML() : StringHelper::packString($cyx->saveXML(), 4096);
        echo "*********** CYX ***********\n" . $output . "\n";
        $typeModel = $cyx->getContent()->getTypeModel();
        echo "typemodel filename: " . var_export($typeModel->fileNames, true) . "\n";
        echo "******** MODEL DATA *******\n" . (string) $typeModel . "\n\n";
        //file_put_contents('zipcontent.zip', $typeModel->zipContent);
        //echo "********* Signed info **************\n";
        //var_export($typeModel->getSignedInfo());
        //echo "******** MODEL SIGNATURES *********\n";
        //var_export($typeModel->getSignaturesList());
    }

    public function actionSbbolApi()
    {
        $module = Yii::$app->getModule('sbbol2');

        $tokenProvider = $module->apiAccessTokenProvider;
        $token = $tokenProvider->getForCustomer(1);

        $api = $module->apiFactory->create(StatementApi::class);

        $accountNumber =
            '40702810854004466448'; // rub 1
            //'40702810954005888446'; // rub 2
            //'40702840054004456448'; // usd
            //'40702978654006231852'; // eur
        $date = date('Y-m-d');
                //'2001-01-01';
                //'2003-01-01';
                //'2005-01-01';

        $result = $api->getSummaryUsingGET($token, $accountNumber, $date);

        echo "*** SUMMARY ***\n" .  (string) $result . "\n";
        $page = 1;

        do {
            $result = $api->getTransactionsUsingGET($token, $accountNumber, $date, $page);
            echo "*** TRANSACTIONS:\n" . (string) $result . "\n";
            $links = $result->getLinks();
            if ($links) {
                $links = $links[0];
            } else {
                break;
            }
            if ($links) {
                $href = $links->getHref();
                $rel = $links->getRel();
                if ($href && $rel == 'next') {
                    $array = [];
                    parse_str($href, $array);
                    if (isset($array['page'])) {
                        $page = $array['page'];
                    } else {
                        break;
                    }
                } else {
                    break;
                }
            }
        } while(true);

    }

    public function actionCheckStorage($lastId = 0)
    {
        $successCnt = 0;
        $totalCnt = 0;
        while(true) {
            echo "lastId: " . $lastId . "\n";
            $documents = Document::find()->where(['>', 'id', $lastId])
                ->orderBy(['id' => SORT_ASC])
                ->limit(10000)
                ->all();

            if (!count($documents)) {
                break;
            }

            foreach($documents as $document) {
                $totalCnt++;
                $lastId = $document->id;
                $storedFileId = $document->actualStoredFileId;
                $storedFile = Yii::$app->storage->get($storedFileId);
                if (!$storedFile) {
                    echo $document->type . ' ' . $document->id . ' ' . $document->dateCreate . ' no stored file ' . $storedFileId . "\n";
                    continue;
                }
                $fs = $storedFile->fileSystem;

                if ($fs == 'local') {
                    $path = Yii::getAlias('@storage/') . $storedFile->serviceId . '/'
                            . $storedFile->resourceId . '/' . $storedFile->path;
                    if (file_exists($path)) {
                        $successCnt++;
                    } else {
                        echo $document->type . ' ' . $document->id . ' ' . $document->dateCreate
                                . ' storage ' . $storedFileId . ' ' . $path . "\n";
                    }
                } else if ($fs == 'tar') {
                    $path = Yii::getAlias('@storage/') . $storedFile->serviceId . '/'
                            . $storedFile->resourceId;
                    list($tarname, $filename) = explode('/', $storedFile->path);
                    $tarPath = $path . '/' . $tarname . '.tar';
                    if (!file_exists($tarPath)) {
                        echo $document->type . ' ' . $document->id . ' ' . $document->dateCreate
                            . ' storage ' . $storedFileId . ' ' . $tarPath . "\n";

                        continue;
                    }

                    $phar = new PharData($tarPath);
                    if ($phar->offsetExists($filename)) {
                        $successCnt++;
                    } else {
                        echo $document->type . ' ' . $document->id . ' ' . $document->dateCreate
                            . ' storage ' . $storedFileId . '/' . $tarPath . '/' . $filename . "\n";
                    }
                } else {
                    echo $document->type . ' ' . $document->id
                        . ' storage ' . $storedFileId . ' unknown filesystem ' . $fs . "\n";
                }
            }
        }

        echo 'checked ' . $totalCnt . ' documents, succesful: ' . $successCnt . "\n";
    }

    public function actionStatementIso($id)
    {
        $document = Document::findOne($id);
        $typeModel = $document->getCyberXml()->getContent()->getTypeModel();

        $statementTypeModel = StatementTypeConverter::convertFrom($typeModel);
        $this->exportStatementToISO($statementTypeModel);
        //echo (string) $typeModel;
        echo "\n";
    }

    public function actionTestUuid()
    {
        for ($i = 0; $i < 10; $i++) {
            $uuid = Uuid::generateSequentialBinary(true);
            echo $uuid . "\n";

            sleep(1);
        }
    }

    private function exportStatementToISO($statementTypeModel)
    {
        $terminalId = 'NIKORUM@XDEV';
        $useCyberXml = false;
        $statementUuid = null;
        $exportToTerminalFolder = true;

        // В случае если запрашивается выписка за текущий день необходимо мапить в документ типа camt.054
        //$today = date('d.m.Y');
        $useCamt054Format = true;//$statementTypeModel->statementPeriodStart === $today
            //&& $statementTypeModel->statementPeriodEnd === $today;

        $isoCamtXml = Converter::statementToIsoCamtXml(
            $statementTypeModel,
            '2019-10-07',
            '123456789',
            $statementUuid,
            $useCamt054Format
        );

        $fileContent = $isoCamtXml;

        $pathParts = [];
        if ($exportToTerminalFolder) {
            $pathParts[] = $terminalId;
        }
        if ($useCyberXml) {
            $subPath = $exportToTerminalFolder
                ? $terminalExportSettings->exportXmlPath
                :  Yii::$app->settings->get('app')->exportXmlPath;
            $subPath = preg_replace('/^\/+/', '', $subPath);
            if ($subPath) {
                $pathParts[] = $subPath;
            }
        } else {
            $pathParts[] = 'ISO20022';
        }

        $path = implode('/', $pathParts);
        $fileName = $useCyberXml
            ? "DOCTYPE_UUID.xml"
            : '____' . '.xml';

        $format = $useCamt054Format ? 'camt.054' : 'camt.053';
        $fileName = $format . '_' . $statementTypeModel->statementAccountNumber
            . '_' . date('ymd', strtotime($statementTypeModel->statementPeriodStart))
            . '_' . date('ymd', strtotime($statementTypeModel->statementPeriodEnd))
            . '_' . date('hmi')
            . '.xml';

        echo "Will save ISO $format statement to $path/$fileName\n";
    }

    public function actionTestStatement($filePath)
    {
        $cyx = new CyberXmlDocument();
        $cyx->loadXml(file_get_contents($filePath));

        $content = $cyx->getContent();

        echo get_class($content) . "\n";

        $typeModel = $content->getTypeModel();

        echo (string) $typeModel . "\n";
    }

    public function actionTestCert($file)
    {
        $certBody = file_get_contents($file);
        if ($certBody) {
            $x509 = X509FileModel::loadData(
                    $certBody
            );
            echo htmlentities($x509->subjectString) . "\n";
            echo $x509->fingerprint . "\n";
            echo $x509->getSerialNumber() . "\n";
        }
    }


    public function actionTestSign($path)
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->loadXML(file_get_contents($path), LIBXML_PARSEHUGE);
        $x = new DOMXPath($dom);
        $rootNamespace = $dom->lookupNamespaceUri($dom->namespaceURI);
        var_export($rootNamespace);
        $x->registerNameSpace('x', $rootNamespace);

        $digest = $x->query('//ds:Signature[@Id="id_5f117639665b3"]/ds:SignedInfo/ds:Reference/ds:DigestValue')->item(0);
        var_export($digest->nodeValue);

        $signatureNode = $x->query('//ds:Signature[@Id="id_5f117639665b3"]/ds:Object')->item(0);
        //var_export($signature->nodeValue);
        $keyInfo = new SimpleXMLElement('<KeyInfo/>');
        $keyInfo->addChild('Child1', 'AAA');
        $elementDom = $dom->importNode(dom_import_simplexml($keyInfo), true);

        echo $keyInfo->asXML();

        $signatureNode->parentNode->insertBefore($elementDom, $signatureNode);

        echo $dom->saveXML();

        //$object = $x->query('/ds:SignedInfo/ds:Object'
        die("\n");

        $model = new Pain001Type();
        $model->loadFromString(file_get_contents($path));

        CryptoProHelper::sign('ISO20022', $model);

        echo "*** model:\n" . (string) $model . "\n";

//        $template = $model->getSignatureTemplate('111', '222');
//        echo "*** template:\n" . $template . "\n";
    }

    public function actionTestInject($cyxfile, $signfile, $certfile)
    {
        $cyxDoc = new CyberXmlDocument();
        $cyxDoc->loadFromString(file_get_contents($cyxfile));
        $signature = file_get_contents($signfile);
        $certBody = file_get_contents($certfile);
        $typeModel = $cyxDoc->getContent()->getTypeModel();

        if (!$typeModel->injectSignature($signature, $certBody)) {
            die("inject failed\n");
        }

        ISO20022Helper::updateZipContent($typeModel);

        $cyxDoc->getContent()->markDirty();



        echo $cyxDoc->saveXML();

        echo "\n";

    }

    public function actionTestCryptoVerify($file)
    {
        echo "\n";
        $verify = CryptoProHelper::verify('ISO20022', $file, 'DEVTESTAXXXX', 'DEVTESTAXXXX');
        var_export($verify);
        echo "\n\n";
    }

    public function actionTestCryptoVerifyContent($id)
    {
        echo "\n";

        $document = Document::findOne($id);
        $cyxDoc = CyberXmlDocument::read($document->actualStoredFileId);
        $typeModel = $cyxDoc->getContent()->getTypeModel();
        file_put_contents('test_model', (string) $typeModel);
        $verify = CryptoProHelper::verify('ISO20022', 'test_model', 'DEVTESTAXXXX', 'DEVTESTAXXXX');
        var_export($verify);
        echo "\n\n";
    }

    public function actionTestAttach($file)
    {
         $document = $this->findModel($id);

        /** @var Auth026Type $typeModel */
        $typeModel = CyberXmlDocument::getTypeModel($document->actualStoredFileId);
        $attachedFiles = $typeModel->getAttachedFileList();

        try {
            if (!isset($attachedFiles[$pos])) {
                throw new \Exception("File offset $pos not found");
            }

            $file = $attachedFiles[$pos];

            // Если модель не использует сжатие в zip
            if ($typeModel->useZipContent) {
                $zip = ZipHelper::createArchiveFileZipFromString($typeModel->zipContent);
                $zipFiles = $zip->getFileList('cp866');

                $fileIndex = array_search($file['path'], $zipFiles);
                if ($fileIndex === false) {
                    throw new \Exception('Zip archive does not contain file ' . $file['path']);
                }

                $content = $zip->getFromIndex($fileIndex);
                $zip->purge();
            } else {
                $content = $typeModel->embeddedAttachments[$pos];
            }

            Yii::$app->response->sendContentAsFile($content, $file['name']);
        } catch (\Exception $exception) {
            echo ("Failed to send attachment, caused by: $exception");
        }
    }

    public function actionUnload($path)
    {
        if (!is_dir($path)) {
            die("Output path not found: $path\n");
        }
        $documents = Document::find()
            ->where(['direction' => Document::DIRECTION_IN])
            ->andWhere(['type' => Auth026Type::TYPE])
            ->andWhere('sender like \'EDCG%\'')
            ->all();

        foreach ($documents as $document) {
            $storedFile = StoredFile::findOne($document->actualStoredFileId);
            $chunks = explode('/', $storedFile->path);
            $filename = array_pop($chunks);
            $filedir = '';
            if (count($chunks) > 0) {
                $filedir = '/' . implode('/', $chunks);
            }
            $outpath = $path . '/' . $storedFile->serviceId . '/' . $storedFile->resourceId . $filedir;
            FileHelper::createDirectory($outpath);
            if (!is_dir($outpath)) {
                die("Cannot create output path: $outpath\n");
            }
            $data = Yii::$app->storage->get($storedFile->id);
            $date = str_replace([' ', '.', ':'], ['_', '-', ''], $document->dateCreate);
            $filename = $date . '_' . $document->uuid;
            file_put_contents($outpath . '/' . $filename, $data);
            echo "document {$document->id} written to $outpath/$filename\n";
        }
    }

    public function actionTestZip()
    {
        /** @var $zip ArchiveFileZip */
        $zip = ZipHelper::createTempArchiveFileZip();
        $zip->addFromString('test', 'ISOшка', 'cp866');
        $zip->close();
        rename($zip->getPath(), 'test.zip');
        $zip->open('test.zip');
        $fileList = $zip->getFileList();
        var_export($fileList);
        echo "\n";
        $fileList = $zip->getFileList('cp866');
        var_export($fileList);
        echo "\n";
        $zip->close();
    }

}

