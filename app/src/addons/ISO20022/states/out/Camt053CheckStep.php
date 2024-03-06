<?php
namespace addons\ISO20022\states\out;

use addons\ISO20022\models\Camt053Type;
use common\states\BaseDocumentStep;

class Camt053CheckStep extends BaseDocumentStep
{
    public $name = 'check053';

    public function run()
    {
        $document = $this->state->document;
        $typeModel = $this->state->typeModel;

        // Если camt.053, то заполняем дополнительные поля extModel
        if ($typeModel->type == Camt053Type::TYPE) {
            $extModel = $document->extModel;
	    $extModel->companyName = $typeModel->companyName;
            $extModel->accountNumber = $typeModel->account;
            $extModel->currency = $typeModel->currency;
            $extModel->periodStart = $typeModel->periodBegin;
            $extModel->periodEnd = $typeModel->periodEnd;
            //$extModel->originalFilename = $typeModel->originalFilename;
            $extModel->save(false);
        }

        return true;
    }
}