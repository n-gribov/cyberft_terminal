<?php

namespace addons\edm\models\IBank\V1\converter\VTB;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use addons\edm\models\DictVTBBankBranch;
use addons\edm\models\IBank\common\IBankDocument;
use addons\edm\models\VTBContractChange\VTBContractChangeType;
use addons\edm\models\VTBContractRequest\VTBContractRequestExt;
use common\base\BaseType;
use common\helpers\vtb\VTBHelper;
use common\models\vtbxml\documents\ContractChange;

class IBankV1ContractChanges181iToVTBConverter extends BaseIBankV1ToVTBConverter
{
    const TYPE_MODEL_CLASS = VTBContractChangeType::class;
    const EXT_MODEL_CLASS = VTBContractRequestExt::class;
    const VTB_DOCUMENT_VERSION = 1;
    const MAPPING = [
        null,
        'CUSTID',
        'KBOPID',
        'DOCUMENTDATE',
        'DOCUMENTNUMBER',
        'SENDEROFFICIALS',
        'PHONEOFFICIALS',
        ['PSROWS' => ['ID', 'PSNUMBER', 'PSDATE']],
        ['PSDATA' => ['ID', 'PARTNUM', 'RENEWDATA']],
        ['PSDOCS' => ['ID', 'DOCTYPE', 'DOCNUM', 'DOCDATE', 'REMARK']],
    ];

    protected function afterCreateTypeModel(BaseVTBDocumentType $typeModel, IBankDocument $ibankDocument)
    {
        parent::afterCreateTypeModel($typeModel, $ibankDocument);

        /** @var ContractChange $document */
        $document = $typeModel->document;

        $organization = VTBHelper::getOrganizationByVTBCustomerId($document->CUSTID);
        if ($organization !== null) {
            $document->CUSTOMERNAME = $organization->name;
            $document->CUSTOMERINN = $organization->inn;
        }

        if (!empty($document->KBOPID)) {
            $bankBranch = DictVTBBankBranch::findOne(['branchId' => $document->KBOPID]);
            if ($bankBranch !== null) {
                $document->CUSTOMERBANKNAME = $bankBranch->name;
                $document->BANKVKFULLNAME = $bankBranch->fullName;
            }
        }
    }

    public function createExtModelAttributes(BaseType $typeModel): array
    {
        $extModel = new VTBContractRequestExt();
        $extModel->loadContentModel($typeModel);
        return array_merge(
            $extModel->dirtyAttributes,
            ['contractsAttributes' => $extModel->contractsAttributes]
        );
    }
}
