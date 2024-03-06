<?php

namespace common\states\in;

use common\document\Document;
use common\states\BaseDocumentStep;
use Exception;

class DocumentVerifyStep extends BaseDocumentStep
{
    const INITIAL_INCOMING_STATE = Document::STATUS_DECRYPTED;
	const PROCESSING_STATE = Document::STATUS_VERIFICATION;
	const SUCCESSFUL_PROCESSED_STATE = Document::STATUS_VERIFIED;
	const ERRONEOUS_PROCESSED_STATE = Document::STATUS_VERIFICATION_FAILED;

    public $name = 'verify';

    public function run()
    {
        $this->state->document->updateStatus(static::PROCESSING_STATE);

		try {
            $result = $this->state->cyxDoc->verify();
        } catch(Exception $ex) {
            $result = false;
            $this->log('Exception: ' . $ex->getMessage());
        }

		if (!$result) {
			// Ошибка верификации: выставляем статус, сохраняя текущее число попыток
            $this->state->document->updateStatus(static::ERRONEOUS_PROCESSED_STATE);
			$this->log('Failed verification: ' . $this->state->cyxDoc->getFirstError('signatures'));
		} else {
            $this->state->document->updateStatus(static::SUCCESSFUL_PROCESSED_STATE);
        }

        /**
         * Внимание: этот шаг всегда возвращает true,
         * cледующим шагом должен быть StatusReportStep, который вернет false или true в зависимости
         * от полученного здесь результата.
         */

        return true;
    }

}
