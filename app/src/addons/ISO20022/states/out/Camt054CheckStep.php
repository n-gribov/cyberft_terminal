<?php
namespace addons\ISO20022\states\out;

use addons\ISO20022\models\Camt054Type;
use common\states\BaseDocumentStep;

class Camt054CheckStep extends BaseDocumentStep
{
    public $name = 'check054';

    public function run()
    {
        $document = $this->state->document;
        $typeModel = $this->state->typeModel;

        // Если camt.054, то заполняем дополнительные поля extModel
        if ($typeModel->type == Camt054Type::TYPE) {
            $extModel = $document->extModel;
            $extModel->accountNumber = $typeModel->account;
	    $extModel->companyName = $typeModel->companyName;
            $extModel->currency = $typeModel->currency;
            $extModel->periodStart = $typeModel->periodBegin;
            $extModel->periodEnd = $typeModel->periodEnd;
            //$extModel->originalFilename = $typeModel->originalFilename;
            $extModel->save(false);
        }

        return true;
    }
}