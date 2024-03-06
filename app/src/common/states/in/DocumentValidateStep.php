<?php
namespace common\states\in;

use common\document\Document;
use common\states\BaseDocumentStep;
use Yii;

class DocumentValidateStep extends BaseDocumentStep
{
    public $name = 'validate';

    public function run()
    {
        $document = $this->state->document;

        if (!array_key_exists($document->type, Yii::$app->registry->getRegister())) {
            $document->updateStatus(Document::STATUS_UNSUPPORTED_TYPE);
            $this->log('Unsupported type');

            return false;
        }

        if (!$this->state->cyxDoc->schemaValidate()) {
            $document->updateStatus(Document::STATUS_SCHEMA_ERROR, 'XSD validation failed');
            $this->log('XSD validation failed');

            return false;
        }

        return true;
    }

}
