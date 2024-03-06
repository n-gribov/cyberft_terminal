<?php

namespace common\states\in;

use common\document\Document;
use common\states\BaseDocumentStep;

class DocumentServiceVerifyStep extends BaseDocumentStep
{
    public $name = 'verify';

    public function run()
    {
        // Пропускаем шаг верификации для сервисных документов
        $this->state->document->updateStatus(Document::STATUS_VERIFIED);

        return true;
    }

}
