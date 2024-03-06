<?php

namespace common\states\in;

use common\document\Document;
use common\states\BaseDocumentStep;
use Exception;
use Yii;

class DocumentProcessStep extends BaseDocumentStep
{
    const INITIAL_INCOMING_STATE = Document::STATUS_VERIFIED;
	const PROCESSING_STATE = Document::STATUS_SERVICE_PROCESSING;
	const SUCCESSFUL_PROCESSED_STATE = Document::STATUS_PROCESSED;
	const ERRONEOUS_PROCESSED_STATE = Document::STATUS_PROCESSING_ERROR;

    public $name = 'process';
    
    public function run()
    {
        $document = $this->state->document;
        $cyxDoc = $this->state->cyxDoc;

        $module = $this->state->module;
        if (empty($module)) {
            $module = Yii::$app->getModule('transport');
        }

        // Статус: передано в модуль
        $document->updateStatus(Document::STATUS_FORPROCESSING);

        $result = false;

        try {
            if ($module->registerMessage($cyxDoc, $document->id)) {
                $document->updateStatus(static::SUCCESSFUL_PROCESSED_STATE);
                $this->log('Processed in module ' . $module->getServiceId());
                $result = true;
            } else {
                $document->updateStatus(static::ERRONEOUS_PROCESSED_STATE);
                $this->log('Failed to process in module ' . $module->getServiceId());
            }
        } catch (Exception $ex) {
            $document->updateStatus(static::ERRONEOUS_PROCESSED_STATE);
            $this->log('Exception while in-module registration: ' . $ex);
        }

        return $result;
    }

}
