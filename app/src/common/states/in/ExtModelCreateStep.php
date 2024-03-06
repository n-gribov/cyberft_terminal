<?php

namespace common\states\in;

use common\states\BaseDocumentStep;


class ExtModelCreateStep extends BaseDocumentStep
{
    public $name = 'ext';

    public function run()
    {

        $document = $this->state->document;
        $extModel = $document->extModelCreateInstance(['documentId' => $document->id]);

        if ($extModel) {
            $extModel->loadContentModel($this->state->cyxDoc->getContent()->getTypeModel());

            if (!$extModel->save(false)) {
                $this->log('Failed to create ExtModel');
                $document->updateStatus(\common\document\Document::STATUS_CREATING_ERROR);

                return false;
            }
        }

        return true;
    }

}
