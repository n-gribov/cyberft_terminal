<?php

namespace common\states\in;

use common\document\Document;
use common\modules\transport\helpers\DocumentTransportHelper;
use common\modules\transport\TransportModule;
use common\states\BaseDocumentStep;
use Exception;
use Yii;

class DocumentDecryptStep extends BaseDocumentStep
{
    public $name = 'decrypt';

    public function run()
    {
        $cyxDoc = $this->state->cyxDoc;
        $document = $this->state->document;

        $info = null;

		try {
            $result = $cyxDoc->decrypt();
		} catch (Exception $ex) {
			$result = false;
            $this->log('' . $ex->getMessage());
            $info = $ex->getMessage();
		}

		if ($cyxDoc->hasErrors()) {
            foreach ($cyxDoc->errors as $error) {
                $this->log($error);
            }
            $errors = $cyxDoc->firstErrors;
            foreach($errors as $attr => $error) {
               $info .= $attr . ': ' . $error . '; ';
            }
		}

		if (false === $result) {
			// Ошибка дешифрации: выставляем статус, сохраняя текущее число попыток
            $document->updateStatus(Document::STATUS_DECRYPTING_ERROR, $info);

            DocumentTransportHelper::statusReport($document, [
                'statusCode' => 'RJCT',
                'errorCode' => '9999',
                'errorDescription' => 'Terminal error: Unable to decrypt Document'
            ]);

			$this->log('Failed to decrypt document');

			return false;
		}

		// Сохраняем результат успешной обработки
		$transport = Yii::$app->getModule('transport');
		$storedFile = $transport->storeData($cyxDoc->getDom()->saveXML(), TransportModule::STORAGE_RESOURCE_IN);

		if (is_null($storedFile)) {
			// Ошибка сохранения файла данных: выставляем статус, сохраняя текущее число попыток
            $document->updateStatus(
                Document::STATUS_DECRYPTING_ERROR,
                'Unable to save decrypted document'
            );

            DocumentTransportHelper::statusReport($document, [
                'statusCode' => 'RJCT',
                'errorCode' => '9999',
                'errorDescription' => 'Terminal error: Unable to save decrypted document'
            ]);

			$this->log('Failed to create stored file');

			return false;
		}

        $this->state->storedFileId = $storedFile->id;
        $cyxDoc->storageId = $storedFile->id;

		// Сохраняем сведения о результирующем файле данных
		$document->actualStoredFileId = $storedFile->id;
		$document->save(false, ['actualStoredFileId']);

		// Статус: успешное завершение задания
        return $document->updateStatus(Document::STATUS_DECRYPTED);
    }

}
