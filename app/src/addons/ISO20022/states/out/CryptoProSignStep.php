<?php

namespace addons\ISO20022\states\out;

use addons\ISO20022\helpers\ISO20022Helper;
use addons\ISO20022\helpers\RosbankHelper;
use addons\ISO20022\models\ISO20022DocumentExt;
use common\document\Document;
use common\helpers\CryptoProHelper;
use common\states\BaseDocumentStep;
use Exception;
use Yii;

class CryptoProSignStep extends BaseDocumentStep
{
    public $name = 'cryptoproSign';

    private $_useZipContent = false;

    public function run()
    {
        $this->_useZipContent = $this->state->typeModel->useZipContent;

        if (!$this->state->module->settings['enableCryptoProSign']) {
            return true;
        }

        $document = $this->state->document;
        $typeModel = $this->state->typeModel;

        try {
            $document->extModel->extStatus = ISO20022DocumentExt::STATUS_FOR_CRYPTOPRO_SIGNING;
            $document->extModel->save(false, ['extStatus']);
            $document->updateStatus(Document::STATUS_SERVICE_PROCESSING);

            $isSigned = RosbankHelper::isGatewayTerminal($document->receiver)
                ? RosbankHelper::signDocument($document, $typeModel)
                : CryptoProHelper::sign('ISO20022', $typeModel);

            if (!$isSigned) {
                $document->extModel->extStatus = ISO20022DocumentExt::STATUS_CRYPTOPRO_SIGNING_ERROR;
                $document->extModel->save();
                $document->updateStatus(Document::STATUS_PROCESSING_ERROR);

                Yii::$app->monitoring->log('document:CryptoProSigningError', 'document', $document->id, [
                    'terminalId' => $document->terminalId
                ]);
                $this->log('CryptoPro signing failed');

                return false;
            }

            if ($typeModel->hasProperty('useZipContent') && $typeModel->useZipContent) {
                ISO20022Helper::updateZipContent($typeModel);
            }

            $this->state->cyxDoc->setTypeModel($typeModel);

            $storedFile = Yii::$app->storage->get($this->state->document->actualStoredFileId);
            $storedFile->updateData($this->state->cyxDoc->saveXML());

            $document->extModel->extStatus = ISO20022DocumentExt::STATUS_CRYPTOPRO_SIGNING_SUCCESS;
            $document->extModel->save();
            $this->log('Signed with CryptoPro keys');

        } catch(Exception $ex) {
            $this->log($ex->getMessage() . PHP_EOL . print_r($this->state->typeModel->errors, true));

            return false;
        }

        return true;
    }

}