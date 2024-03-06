<?php

namespace common\states;

use common\helpers\Lock;
use common\states\BaseDocumentStep;
use Exception;

class ExtModelCreateStep extends BaseDocumentStep
{
    public $name = 'ext';

    public function run()
    {
        $document = $this->state->document;
        $extModel = $document->extModelCreateInstance(['documentId' => $document->id]);

        if (is_null($extModel)) {
            throw new Exception('Failed to get extmodel instance');
        }

        $extModel->loadContentModel($this->state->typeModel);

        if (!$extModel->save()) {
            $document->delete();

            throw new Exception('Could not save extmodel');
        }

        $this->log('Saved Extmodel for ' . $document->type . ' with id ' . $document->id);

        return true;
    }

    /**
     * @todo убрать неявную зависимость.
     * Lock ставится в импорте, при проверке уникальности документа, освобождается по логике
     * после создания экстмодели.
     *
     * Возможно, нужно сделать отдельный step для лока и анлока.
     */
    public function cleanup()
    {
        if ($this->state->lockValue) {
            Lock::release($this->state->lockName, $this->state->lockValue);
        }

        parent::cleanup();
    }

}