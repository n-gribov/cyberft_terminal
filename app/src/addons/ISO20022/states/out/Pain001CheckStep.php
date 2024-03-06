<?php
namespace addons\ISO20022\states\out;

use addons\ISO20022\models\Pain001Type;
use common\states\BaseDocumentStep;

class Pain001CheckStep extends BaseDocumentStep
{
    public $name = 'check001';

    public function run()
    {
        $document = $this->state->document;
        $typeModel = $this->state->typeModel;

        // Если pain.001, то заполняем дополнительные поля extModel
        if ($typeModel->type == Pain001Type::TYPE) {
            $extModel = $document->extModel;
            $extModel->count = $typeModel->count;
            $extModel->sum = $typeModel->sum;
            $extModel->currency = $typeModel->currency;
            $extModel->originalFilename = $typeModel->originalFilename;
            $extModel->save(false);
        }

        return true;
    }
}
