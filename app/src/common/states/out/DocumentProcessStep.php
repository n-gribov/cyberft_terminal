<?php

namespace common\states\out;

use common\document\Document;
use common\states\BaseDocumentStep;
use Yii;

class DocumentProcessStep extends BaseDocumentStep
{
    public $name = 'process';

    public function run()
    {
        $document = $this->state->document;

        $this->state->module->processDocument($document);

        if ($document->direction == Document::DIRECTION_OUT && $document->signaturesRequired > 0) {
            Yii::$app->resque->enqueue('common\jobs\ExtractSignDataJob', ['id' => $document->id]);
        }

        return true;
    }

}