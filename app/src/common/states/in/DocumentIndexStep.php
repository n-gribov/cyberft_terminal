<?php

namespace common\states\in;

use common\states\BaseDocumentStep;

class DocumentIndexStep extends BaseDocumentStep
{
    public $name = 'index';

    public function run()
    {
        // индексируем входящий в эластике
        // Yii::$app->elasticsearch->putDocument($this->state->document);

        return true;
    }

}
