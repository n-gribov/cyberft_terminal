<?php

namespace console\controllers\actions;

use addons\edm\models\EdmDocumentExt;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt;
use addons\edm\models\PaymentOrder\PaymentOrderDocumentExt;
use addons\edm\models\PaymentRegister\PaymentRegisterDocumentExt;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrder;
use addons\edm\models\Statement\StatementDocumentExt;
use addons\edm\models\StatementRequest\StatementRequestExt;
use addons\fileact\models\FileActDocumentExt;
use addons\finzip\models\FinZipDocumentExt;
use addons\ISO20022\models\ISO20022DocumentExt;
use addons\swiftfin\models\SwiftFinDocumentExt;
use common\document\Document;
use common\models\Terminal;
use Yii;
use yii\base\Action;

class ImportDataAction extends Action
{
    private $_filePath;
    private $_statusLine;
    private $_terminalMap;
    private $_lastIndexes;
    private $_storagePath;
    private $_jsonPath;

    public function run($filePath)
    {
        if (!is_dir($filePath)) {
            die($filePath . ": not a dir\n");
        }

        $this->_storagePath = $filePath . '/storage/';
        $this->_jsonPath = $filePath . '/json/';

        if (!is_dir($this->_storagePath)) {
            die("Storage path not found\n");
        }

        if (!is_dir($this->_jsonPath)) {
            die("JSON path not found\n");
        }

        if (!file_exists($this->_jsonPath . '/terminals.json')) {
            die("Terminals data file not found\n");
        }

        if (file_exists($filePath . '/import.log')) {
            die($filePath . "/import.log exists, you must delete new entries from database and delete import.log\n");
        }

        $this->_filePath = $filePath;

        $terminalMap = json_decode(file_get_contents($this->_jsonPath . '/terminals.json'))->terminals;
        $terminals = Terminal::find()->all();

        $targetTerminals = [];
        foreach($terminals as $terminal) {
            $targetTerminals[$terminal->terminalId] = $terminal->id;
        }

        $this->_terminalMap = [];

        foreach($terminalMap as $terminalId => $id) {
            if (isset($targetTerminals[$terminalId])) {
                $this->_terminalMap[$id] = $targetTerminals[$terminalId];
            } else {
                die('Terminal ' . $terminalId . " is not found on the target system\n");
            }
        }

        echo "Saving last indexes...\n";

        $this->_lastIndexes = [
            Document::className() => 0,
            PaymentRegisterPaymentOrder::className() => 0,
            PaymentOrderDocumentExt::className() => 0,
            PaymentRegisterDocumentExt::className() => 0,
            StatementDocumentExt::className() => 0,
            StatementRequestExt::className() => 0,
            EdmDocumentExt::className() => 0,
            ForeignCurrencyOperationDocumentExt::className() => 0,
            FileActDocumentExt::className() => 0,
            FinZipDocumentExt::className() => 0,
            ISO20022DocumentExt::className() => 0,
            SwiftFinDocumentExt::className() => 0,
        ];

        $loggedValues = 'Indexes before import:';
        foreach($this->_lastIndexes as $class => $num) {
            $this->_lastIndexes[$class] = $this->getLastIndex($class);

            $loggedValues .= "\n" . $class . ': ' . $this->_lastIndexes[$class];
        }

        echo $loggedValues . "\n";

        file_put_contents($filePath . '/import.log', $loggedValues);

        $this->importSwiftFin();
        $this->importFileAct();
        $this->importFinZip();
        $this->importISO20022();
        $this->importEdm();
        $this->importPaymentOrder();
    }

    private function getLastIndex($class)
    {
        $model = $class::find()->limit(1)->orderBy(['id' => SORT_DESC])->one();

        if ($model) {
            return $model->id;
        }

        return 0;
    }

    private function setStatusLine($str)
    {
        $this->_statusLine = $str;

        echo $this->_statusLine;
    }

    private function updateProgress($percent)
    {
        echo "\r" . $this->_statusLine . ' ' . $percent . '%' . '    ';
    }

    private function getFileList($typeGroup)
    {
        $path = $this->_jsonPath . '/' . $typeGroup . '/';

        if (!is_dir($path)) {
            return [];
        }

        $dir = opendir($path);
        $out = [];
        while ($file = readdir($dir)) {
            if ($file == '.' || $file == '..') {
                continue;
            }

            $out[] = $path . $file;
        }

        return $out;
    }

    private function getStorageId($path)
    {
        $parts = explode('/', $path);
        array_pop($parts);
        array_pop($parts);

        $serviceId = $parts[0];
        $resourceId = $parts[1];
        if (count($parts) > 2) {
            $resourceId .= '/' . $parts[2];
        }

        $resource = Yii::$app->registry->getStorageResource($serviceId, $resourceId);

        if (!$resource) {
            echo ("Could not get resource for " . $serviceId . '/' . $resourceId . ', backup path = ' . $path . "\n");

            return null;
        }

        $data = file_get_contents($this->_storagePath . $path);
        if ($data === false) {
            die("Could not read from backup: " . $path . "\n");
        }

        $storedFile = Yii::$app->storage->putData($data, $serviceId, $resourceId);

        if (!$storedFile) {
            die("Error while saving storage data for document " . $serviceId . '/' . $resourceId . "\n");
        }

        return $storedFile->id;
    }

    private function importDocuments($items, $extModelMap = null)
    {
        $currentCnt = 1;
        $cnt = count($items);

        foreach($items as $item) {
            $this->updateProgress((int) ($currentCnt * 100 / $cnt));

            $docAttributes = $item['document'];
            unset($docAttributes['id']);

            if (isset($docAttributes['terminalId'])) {
                $docAttributes['terminalId'] = $this->_terminalMap[$docAttributes['terminalId']];
            }

            if (isset($item['storage'])) {
                $docAttributes['actualStoredFileId'] = $this->getStorageId($item['storage']);
            }

            if (isset($item['encryptedStorage'])) {
                $docAttributes['encryptedStoredFileId'] = $this->getStorageId($item['encryptedStorage']);
            }

            $document = new Document($docAttributes);

            // Сохранить модель в БД
            $document->save();

            if (isset($item['extModel'])) {
                $extAttributes = $item['extModel'];
                $extAttributes['documentId'] = $document->id;
                unset($extAttributes['id']);
                if (isset($extAttributes['terminalId'])) {
                    $extAttributes['terminalId'] = $this->_terminalMap[$extAttributes['terminalId']];
                }
                $extModel = $document->extModelCreateInstance();
                if ($extModel) {
                    if (isset($item['extModelData']) && $extModelMap) {
                        $extModelData = $item['extModelData'];
                        foreach($extModelData as $attribute => $attrValue) {
                            if (isset($extModelMap[$attribute])) {
                                $targetAttribute = $extModelMap[$attribute];
                                $extAttributes[$targetAttribute] = $this->getStorageId($attrValue);
                            }
                        }
                    }

                    $extModel->setAttributes($extAttributes);
                    // Сохранить модель в БД
                    $extModel->save();
                }
            }

            $currentCnt++;
        }
    }

    private function importSwiftFin()
    {
        $fileList = $this->getFileList('swiftfin');

        foreach($fileList as $file) {

            $items = json_decode(file_get_contents($file), true)['items'];
            $cnt = count($items);
            $this->setStatusLine('Importing ' . $cnt . ' SWIFTFIN documents from ' . basename($file) . '...');

            $this->importDocuments($items);
            echo "Done.\n";
        }
    }

    private function importFileAct()
    {
        $fileList = $this->getFileList('fileact');

        foreach($fileList as $file) {

            $items = json_decode(file_get_contents($file), true)['items'];
            $cnt = count($items);
            $this->setStatusLine('Importing ' . $cnt . ' FILEACT documents from ' . basename($file) . '...');

            $this->importDocuments($items, [
                'pduPath' => 'pduStoredFileId',
                'binPath' => 'binStoredFileId',
                'zipPath' => 'zipStoredFileId'
            ]);

            echo "Done.\n";
        }
    }

    private function importFinZip()
    {
        $fileList = $this->getFileList('finzip');

        foreach($fileList as $file) {

            $items = json_decode(file_get_contents($file), true)['items'];
            $cnt = count($items);
            $this->setStatusLine('Importing ' . $cnt . ' FINZIP documents from ' . basename($file) . '...');

            $this->importDocuments($items, [
                'zipPath' => 'zipStoredFileId'
            ]);

            echo "Done.\n";
        }
    }

    private function importISO20022()
    {
        $fileList = $this->getFileList('iso20022');

        foreach($fileList as $file) {

            $items = json_decode(file_get_contents($file), true)['items'];
            $cnt = count($items);
            $this->setStatusLine('Importing ' . $cnt . ' ISO20022 documents from ' . basename($file) . '...');

            $this->importDocuments($items, [
                'path' => 'storedFileId'
            ]);

            echo "Done.\n";
        }
    }

    private function importEdm()
    {
        $fileList = $this->getFileList('edm');

        foreach($fileList as $file) {

            $items = json_decode(file_get_contents($file), true)['items'];
            $cnt = count($items);
            $this->setStatusLine('Importing ' . $cnt . ' EDM documents from ' . basename($file) . '...');

            $this->importDocuments($items);

            echo "Done.\n";
        }
    }

    private function importPaymentOrder()
    {
        $fileList = $this->getFileList('paymentOrder');

        foreach($fileList as $file) {

            $items = json_decode(file_get_contents($file), true)['items'];
            $cnt = count($items);
            $this->setStatusLine('Importing ' . $cnt . ' PaymentOrder models from ' . basename($file) . '...');

            $currentCnt = 1;

            foreach($items as $item) {
                $this->updateProgress((int) ($currentCnt * 100 / $cnt));

                $docAttributes = $item['document'];
                unset($docAttributes['id']);

                if (isset($docAttributes['terminalId'])) {
                    $docAttributes['terminalId'] = $this->_terminalMap[$docAttributes['terminalId']];
                }

                $document = new PaymentRegisterPaymentOrder($docAttributes);
                // Сохранить модель в БД
                $document->save();

                $currentCnt++;
            }

            echo "Done.\n";
        }
    }
}

