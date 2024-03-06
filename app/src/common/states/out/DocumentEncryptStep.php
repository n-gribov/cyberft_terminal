<?php
namespace common\states\out;

use common\document\Document;
use common\modules\transport\TransportModule;
use common\states\BaseDocumentStep;
use Yii;

class DocumentEncryptStep extends BaseDocumentStep
{
    public $name = 'encrypt';

    public function run()
    {
        $certManager = Yii::$app->getModule('certManager');
        $cert = $certManager->getSignerBotCertificate($this->state->document->receiver);

        if (!$cert) {
            // Если активный сертификат контролера не найден, отправляем без шифрования (CYB-4320)
            $this->log(' Not encrypted: no active certificates for ' . $this->state->document->receiver);
            $this->state->document->encryptedStoredFileId = $this->state->document->actualStoredFileId;

            return true;
        }

        $certs = [$cert->getCertificate()->body];
        $additionalEncryptCertId = Yii::$app->settings->get('Security')->additionalEncryptCert;
        if ($additionalEncryptCertId) {
            $additionalEncryptCert = $certManager->getCertificate($additionalEncryptCertId);
            if ($additionalEncryptCert) {
                $certs[] = $additionalEncryptCert->getCertificate()->body;
            }
        }

        if ($this->state->cyxDoc->encrypt($certs) == false) {
            $this->state->document->updateStatus(Document::STATUS_ENCRYPTING_ERROR);
            $this->log('Failed to encrypt');

            return false;
        }

		// Сохраняем результат успешного шифрования
		$transportModule = Yii::$app->getModule('transport');
		$storedFile = $transportModule->storeData(
                $this->state->cyxDoc->getDom()->saveXML(),
                TransportModule::STORAGE_RESOURCE_OUT
        );

		if (is_null($storedFile)) {
			// Ошибка сохранения файла данных: выставляем статус, сохраняя текущее число попыток
            $this->state->document->updateStatus(Document::STATUS_ENCRYPTING_ERROR, "Data file can't be saved");
			$this->log('Failed to store file');

			return false;
		}

		$this->state->document->encryptedStoredFileId = $storedFile->id;

        return true;
    }

    public function onSuccess()
    {
		// Сохраняем сведения о результирующем файле данных
		$this->state->document->save(false, ['encryptedStoredFileId']);

		// Статус: успешное завершение задания
        $this->state->document->updateStatus(Document::STATUS_ENCRYPTED);

        parent::onSuccess();
    }

}