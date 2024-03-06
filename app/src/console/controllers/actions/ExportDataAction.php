<?php

namespace console\controllers\actions;

use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrder;
use addons\fileact\models\FileActDocumentExt;
use addons\finzip\models\FinZipDocumentExt;
use addons\ISO20022\models\ISO20022DocumentExt;
use common\document\Document;
use common\helpers\FileHelper;
use common\models\Terminal;
use Yii;
use yii\base\Action;

class ExportDataAction extends Action
{
    private $_filePath;
    private $_fileHandle;
    private $_count;
    private $_itemCount;
    private $_typeGroup;
    private $_statusLine;

    public function run($filePath)
    {
        if (!is_dir($filePath)) {
            die($filePath . ": not a dir\n");
        }

        $storagePath = $filePath . '/storage/';
        $jsonPath = $filePath . '/json/';

        if (!is_dir($storagePath)) {
            mkdir($storagePath);
        }

        if (!is_dir($jsonPath)) {
            mkdir($jsonPath);
        }

        $this->_filePath = $filePath;

        $terminals = Terminal::find()->all();
        $terminalData = [];
        foreach($terminals as $terminal) {
            $terminalData[$terminal->terminalId] = $terminal->id;
        }

        file_put_contents($jsonPath . '/terminals.json', json_encode([
            'terminals' => $terminalData
        ]));

        $this->exportSwiftFin();
        $this->exportFileAct();
        $this->exportFinZip();
        $this->exportISO20022();
        $this->exportEdm();
        $this->exportPaymentOrder();
    }

    private function getStorageAttributes($storageId)
    {
        $storedFile = Yii::$app->storage->get($storageId);
        if (empty($storedFile)) {
            return null;
        }
        $path = $storedFile->serviceId . '/' . $storedFile->resourceId . '/' . $storedFile->path;

        return [
            'path' => $path,
            'data' => $storedFile->getData()
        ];
    }

    private function getActualAttributes($model)
    {
        $attributes = $model->attributes;

        $usedAttributes = [];
        foreach($attributes as $key => $value) {
            if (!is_null($value)) {
                $usedAttributes[$key] = $value;
            }
        }

        return $usedAttributes;
    }

    private function saveFile($storage)
    {
        $path = $storage['path'];
        $dir = dirname($path);
        FileHelper::createDirectory($this->_filePath . '/storage/' . $dir);
        file_put_contents($this->_filePath . '/storage/' . $path, $storage['data']);
    }

    private function writeJson($typeGroup, $data)
    {
        if ($this->_typeGroup != $typeGroup) {
            if ($this->_fileHandle) {
                $this->finalizeJson();
            }

            $this->_typeGroup = $typeGroup;
            $this->_count = 0;
            FileHelper::createDirectory($this->_filePath . '/json/' . $this->_typeGroup);
        }

        if ($this->_itemCount > 999) {
            $this->finalizeJson();
            $this->_count++;
        }

        if (!$this->_fileHandle) {
            $this->_fileHandle = fopen(
                $this->_filePath . '/json/' . $this->_typeGroup . '/' . str_pad($this->_count, 6, '0', STR_PAD_LEFT) . '.json'
                , 'w'
            );

            fputs($this->_fileHandle, "{\n\"items\":[\n");
        }

        if ($this->_itemCount > 0) {
            fputs($this->_fileHandle, ",\n");
        }

        fputs($this->_fileHandle, $data);

        $this->_itemCount++;
    }

    private function finalizeJson()
    {
        if ($this->_fileHandle) {
            fputs($this->_fileHandle, "\n]\n}");
            fclose($this->_fileHandle);
        }
        $this->_fileHandle = null;
        $this->_itemCount = 0;
    }

    private function getCommonDocumentData(Document $document)
    {
        $out = [
            'document' => $this->getActualAttributes($document)
        ];

        $storage = $this->getStorageAttributes($document->actualStoredFileId);
        if ($storage) {
            $this->saveFile($storage);
            $out['storage'] = $storage['path'];
        }

        $encryptedStorage = $this->getStorageAttributes($document->encryptedStoredFileId);
        if ($encryptedStorage) {
            $this->saveFile($encryptedStorage);
            $out['encryptedStorage'] = $encryptedStorage['path'];
        }

        return $out;
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

    private function exportSwiftFin()
    {
        $documents = Document::findAll(['typeGroup' => 'swiftfin']);
        $cnt = count($documents);
        if (!$cnt) {
            return;
        }

        $this->setStatusLine('Exporting ' . $cnt . ' SWIFTFIN documents...');
        $currentCnt = 1;

        /* @var $document Document */
        foreach($documents as $document) {

            $this->updateProgress((int) ($currentCnt * 100 / $cnt));

            $out = $this->getCommonDocumentData($document);

            $extModel = $document->extModelCreateInstance();
            if ($extModel) {
                $extModel = $extModel->findOne([
                    'documentId' => $document->id
                ]);

                if ($extModel) {
                    $out['extModel'] = $this->getActualAttributes($extModel);
                }
            }

            $this->writeJson('swiftfin', json_encode($out));

            $currentCnt++;
        }

        $this->finalizeJson();

        echo "Done.\n";
    }

    private function exportFileAct()
    {
        $documents = Document::findAll(['typeGroup' => 'fileact']);

        $cnt = count($documents);
        if (!$cnt) {
            return;
        }
        $this->setStatusLine('Exporting ' . $cnt . ' FILEACT documents...');

        $currentCnt = 1;

        /* @var $document Document */
        foreach($documents as $document) {

            $this->updateProgress((int) ($currentCnt * 100 / $cnt));

            $out = $this->getCommonDocumentData($document);

            /* @var $extModel FileActDocumentExt */
            $extModel = $document->extModelCreateInstance();
            if ($extModel) {
                $extModel = $extModel->findOne([
                    'documentId' => $document->id
                ]);

                if ($extModel) {

                    $extOut = [];

                    $storage = $this->getStorageAttributes($extModel->pduStoredFileId);
                    if ($storage) {
                        $this->saveFile($storage);
                        $extOut['pduPath'] = $storage['path'];
                    }

                    $storage = $this->getStorageAttributes($extModel->binStoredFileId);
                    if ($storage) {
                        $this->saveFile($storage);
                        $extOut['binPath'] = $storage['path'];
                    }
                    
                    $storage = $this->getStorageAttributes($extModel->zipStoredFileId);
                    if ($storage) {
                        $this->saveFile($storage);
                        $extOut['zipPath'] = $storage['path'];
                    }

                    if (!empty($extOut)) {
                        $out['extModelData'] = $extOut;
                    }

                    $out['extModel'] = $this->getActualAttributes($extModel);
                }
            }

            $this->writeJson('fileact', json_encode($out));

            $currentCnt++;
        }

        $this->finalizeJson();

        echo "Done.\n";
    }

    private function exportFinZip()
    {
        $documents = Document::findAll(['typeGroup' => 'finzip']);

        $cnt = count($documents);
        if (!$cnt) {
            return;
        }

        $this->setStatusLine('Exporting ' . $cnt . ' FINZIP documents...');

        $currentCnt = 1;

        /* @var $document Document */
        foreach($documents as $document) {
            $this->updateProgress((int) ($currentCnt * 100 / $cnt));

            $out = $this->getCommonDocumentData($document);

            /* @var $extModel FinZipDocumentExt */
            $extModel = $document->extModelCreateInstance();
            if ($extModel) {
                $extModel = $extModel->findOne([
                    'documentId' => $document->id
                ]);

                if ($extModel) {
                    $extOut = [];

                    $storage = $this->getStorageAttributes($extModel->zipStoredFileId);
                    if ($storage) {
                        $this->saveFile($storage);
                        $extOut['zipPath'] = $storage['path'];
                    }

                    if (!empty($extOut)) {
                        $out['extModelData'] = $extOut;
                    }

                    $out['extModel'] = $this->getActualAttributes($extModel);
                }
            }

            $this->writeJson('finzip', json_encode($out));

            $currentCnt++;
        }

        $this->finalizeJson();

        echo "Done.\n";
    }

    private function exportISO20022()
    {
        $documents = Document::findAll(['typeGroup' => 'iso20022']);

        $cnt = count($documents);
        if (!$cnt) {
            return;
        }

        $this->setStatusLine('Exporting ' . $cnt . ' ISO20022 documents...');

        $currentCnt = 1;

        /* @var $document Document */
        foreach($documents as $document) {
            
            $this->updateProgress((int) ($currentCnt * 100 / $cnt));

            $out = $this->getCommonDocumentData($document);
            /* @var $extModel ISO20022DocumentExt */
            $extModel = $document->extModelCreateInstance();
            if ($extModel) {
                $extModel = $extModel->findOne([
                    'documentId' => $document->id
                ]);

                if ($extModel) {
                    $extOut = [];

                    $storage = $this->getStorageAttributes($extModel->storedFileId);
                    if ($storage) {
                        $this->saveFile($storage);
                        $extOut['path'] = $storage['path'];
                    }

                    if (!empty($extOut)) {
                        $out['extModelData'] = $extOut;
                    }

                    $out['extModel'] = $this->getActualAttributes($extModel);
                }
            }

            $this->writeJson('iso20022', json_encode($out));

            $currentCnt++;
        }

        $this->finalizeJson();

        echo "Done.\n";
    }

    private function exportEdm()
    {
        $documents = Document::findAll(['typeGroup' => 'edm']);

        $cnt = count($documents);
        if (!$cnt) {
            return;
        }

        $this->setStatusLine('Exporting ' . $cnt . ' EDM documents...');

        $currentCnt = 1;

        /* @var $document Document */
        foreach($documents as $document) {

            $this->updateProgress((int) ($currentCnt * 100 / $cnt));

            $out = $this->getCommonDocumentData($document);

            $extModel = $document->extModelCreateInstance();
            if ($extModel) {
                $extModel = $extModel->findOne([
                    'documentId' => $document->id
                ]);

                if ($extModel) {
                    $out['extModel'] = $this->getActualAttributes($extModel);
                }
            }

            $this->writeJson('edm', json_encode($out));

            $currentCnt++;
        }

        $this->finalizeJson();

        echo "Done.\n";
    }

    private function exportPaymentOrder()
    {
        $documents = PaymentRegisterPaymentOrder::find()->all();

        $cnt = count($documents);
        if (!$cnt) {
            return;
        }

        $this->setStatusLine('Exporting ' . $cnt . ' PaymentOrder models...');

        $currentCnt = 1;

        /* @var $document PaymentRegisterPaymentOrder */
        foreach($documents as $document) {

            $this->updateProgress((int) ($currentCnt * 100 / $cnt));

            $out = [
                'document' => $this->getActualAttributes($document)
            ];

            $this->writeJson('paymentOrder', json_encode($out));

            $currentCnt++;
        }

        $this->finalizeJson();

        echo "Done.\n";
    }

}
