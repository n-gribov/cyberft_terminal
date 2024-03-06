<?php

use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationType;
use common\document\Document;
use common\helpers\Uuid;
use common\models\cyberxml\CyberXmlDocument;
use yii\db\Migration;

class m170309_140802_currency_op_refactor extends Migration
{
    public function up()
    {
        $module = Yii::$app->getModule('edm');

        if (!$module) {
            throw new \Exception("EDM module not found");
        }

        $extModels = ForeignCurrencyOperationDocumentExt::find()->all();

        foreach($extModels as $model) {
            $typeModel = new ForeignCurrencyOperationType();
            $document = Document::findOne($model->documentId);

            if (!$document) {
                echo "Document not found: " . $model->documentId . "\n";
                continue;
            }

            if (in_array($document->type, [ForeignCurrencyOperationType::OPERATION_SELL, ForeignCurrencyOperationType::OPERATION_PURCHASE])) {
                continue;
            }

            if ($document->actualStoredFileId) {

                $storedFile = Yii::$app->storage->get($document->actualStoredFileId);

                if ($storedFile) {
                    $path = $storedFile->getRealPath();
                    if (file_exists($path)) {
                        continue;
                    }
                } else {
                    echo "Stored file with id "
                        . $document->actualStoredFileId . " not found for document " . $document->id . "\n";
                }
            }

            if (!$document->uuid) {
                $document->uuid = Uuid::generate();
            }

            $typeModel->loadFromString($model->body);
            $cyx = CyberXmlDocument::loadTypeModel($typeModel);
            $cyx->docId = $document->uuid;
            $cyx->senderId = $document->sender;
            $cyx->receiverId = $document->receiver;
            $cyx->docDate = date('c', strtotime($document->dateCreate));
            $content = $cyx->saveXML();
            if ($document->direction == Document::DIRECTION_IN) {
                $storedFile = $module->storeDataIn($content);
            } else {
                $storedFile = $module->storeDataOut($content);
            }

            if (!$storedFile) {
                echo "Could not store file for document " . $document->id . "\n";

                continue;
            }

            $document->actualStoredFileId = $storedFile->id;
            if ($document->status == Document::STATUS_FORSIGNING) {
                $document->setSignData('service');
            }

            $document->save(false);

            echo 'Updated document ' . $document->id . ' with stored file id ' . $storedFile->id . "\n";
        }

        $this->execute('alter table `documentExtEdmForeignCurrencyOperation` drop column `typeRequest`');
        $this->execute('alter table `documentExtEdmForeignCurrencyOperation` drop column `body`');
        $this->execute('alter table `documentExtEdmForeignCurrencyOperation` drop column `dateCreate`');
        $this->execute('alter table `documentExtEdmForeignCurrencyOperation` drop column `dateUpdate`');
        $this->execute('alter table `documentExtEdmForeignCurrencyOperation` drop column `signaturesRequired`');
        $this->execute('alter table `documentExtEdmForeignCurrencyOperation` drop column `signaturesCount`');
    }

    public function down()
    {
        $this->execute("alter table `documentExtEdmForeignCurrencyOperation` add column `typeRequest` varchar(255) not null default ''");
        $this->execute('alter table `documentExtEdmForeignCurrencyOperation` add column `body` text');
        $this->execute("alter table `documentExtEdmForeignCurrencyOperation` add column `dateCreate` timestamp default '0000-00-00 00:00:00'");
        $this->execute("alter table `documentExtEdmForeignCurrencyOperation` add column `dateUpdate` timestamp default '0000-00-00 00:00:00'");
        $this->execute('alter table `documentExtEdmForeignCurrencyOperation` add column `signaturesRequired` int unsigned default 0');
        $this->execute('alter table `documentExtEdmForeignCurrencyOperation` add column `signaturesCount` int unsigned default 0');

        $this->db->schema->refreshTableSchema(ForeignCurrencyOperationDocumentExt::tableName());

        $extModels = ForeignCurrencyOperationDocumentExt::find()->all();
        foreach ($extModels as $model) {
            $document = Document::findOne($model->documentId);

            if (!$document || !$document->actualStoredFileId) {
                continue;
            }

            if (in_array($document->type, [ForeignCurrencyOperationType::OPERATION_SELL, ForeignCurrencyOperationType::OPERATION_PURCHASE])) {
                continue;
            }

            $cyx = CyberXmlDocument::read($document->actualStoredFileId);
            $typeModel = $cyx->getContent()->getTypeModel();

            $model->body = (string) $typeModel;
            $model->signaturesRequired = $document->signaturesRequired;
            $model->signaturesCount = $document->signaturesCount;
            $model->dateCreate = $document->dateCreate;
            $model->typeRequest = $document->type == 'ForeignCurrencySellRequest' ? 'sell' : 'purchase';
            $model->save(false, ['typeRequest']);
        }

        return true;
    }

}
