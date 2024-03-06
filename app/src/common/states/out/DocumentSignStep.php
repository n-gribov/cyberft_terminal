<?php
namespace common\states\out;

use common\document\Document;
use common\modules\transport\TransportModule;
use common\states\BaseDocumentStep;
use \Exception;
use Yii;
use yii\base\InvalidValueException;

class DocumentSignStep extends BaseDocumentStep
{
    public $name = 'sign';

    public function run()
    {
        $document = $this->state->document;

        $sender = $this->state->cyxDoc->senderId;

        $primaryAutobot = Yii::$app->terminals->findUsedForSigningAutobot($sender);

        if (empty($primaryAutobot)) {
            $this->log('failed to find used for signing autobot for ' . $sender);
            $document->updateStatus(Document::STATUS_SIGNING_ERROR,
                Yii::t('app/autobot', 'Signing is failed! There is no used for signing Controller key for this participant!'));
            return false;
        }

        $terminalData = Yii::$app->terminals->findTerminalData($sender);

        if (empty($terminalData['isRunning'])|| empty($terminalData['passwords'])) {
            $this->log('failed to find terminal data for ' . $sender);

            return false;
        }

        try {
            if (!$this->state->cyxDoc->sign(
                $sender,
                $primaryAutobot->privateKey,
                $terminalData['passwords'][$primaryAutobot->id],
                $primaryAutobot->fingerprint,
                $primaryAutobot->certificate)
            ) {
                throw new Exception('failed to sign');
            }

            if (!$this->saveCyberXmlDocument()) {
                throw new Exception('failed to save cyberXML');
            }

            $document->updateStatus(Document::STATUS_SIGNED);

            return true;

        } catch (InvalidValueException $ex) {

            // Обработка ошибки отсутствия сертификата контролера
            Yii::error($ex->getMessage(), 'system');
            // Смена статуса на ошибку подписания
            $document->updateStatus(Document::STATUS_SIGNING_ERROR, $ex->getMessage());

            // Создание события для журнала событий
            Yii::$app->monitoring->log(
                'document:documentContSignError',
                'document',
                $document->id,
                [
                    'documentTypeGroup' => $document->typeGroup,
                    'terminalId' => $document->terminalId
                ]
            );
        } catch (Exception $ex) {
            $document->updateStatus(Document::STATUS_SIGNING_ERROR, $ex->getMessage());

            $this->log($ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }

        return false;
    }

    private function saveCyberXmlDocument()
    {
        $transportModule  = Yii::$app->getModule('transport');

        if ($this->state->document->isEncrypted) {
            $storedFile = $transportModule->storeData(
                $this->state->cyxDoc->getDom()->saveXML(),
                TransportModule::STORAGE_RESOURCE_OUT_ENC
            );
        } else {
            $storedFile = $transportModule->storeData(
                $this->state->cyxDoc->getDom()->saveXML(),
                TransportModule::STORAGE_RESOURCE_OUT
            );
        }

        if (empty($storedFile )) {
            $this->log('Failed to create stored file');

            return false;
        }

        $this->state->document->actualStoredFileId = $storedFile->id;

        if (!$this->state->document->save(false, ['actualStoredFileId'])) {
            $this->log('Failed to update stored file id ' . $storedFile->id . ' in document');

            return false;
        }

        return true;
    }

}