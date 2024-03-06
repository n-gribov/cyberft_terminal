<?php

namespace addons\edm\models\IBank\V2\converter\VTB;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use addons\edm\models\DictVTBBankBranch;
use addons\edm\models\IBank\common\IBankDocument;
use addons\edm\models\VTBContractRequest\VTBContractRequestExt;
use addons\edm\models\VTBContractUnReg\VTBContractUnRegType;
use common\base\BaseType;
use common\models\vtbxml\documents\ContractUnReg;

class IBankV2BargainPassportClosingToVTBConverter extends BaseIBankV2ToVTBConverter
{
    const TYPE_MODEL_CLASS = VTBContractUnRegType::class;
    const EXT_MODEL_CLASS = VTBContractRequestExt::class;
    const VTB_DOCUMENT_VERSION = 3;
    const MAPPING = [
        'DATE_DOC' => 'DOCUMENTDATE',
        'NUM_DOC' => 'DOCUMENTNUMBER',
        'CLIENT_NAME' => 'CUSTOMERNAME',
        'PASSPORT_BANK_NAME' => 'CUSTOMERBANKNAME',
        'PINFO' => [
            'CONTRACT_NUM' => ['PSROWS' => 'PSNUMBER'],
            'CONTRACT_DATE' => ['PSROWS' => 'PSDATE'],
            'CLOSING_REASON' => ['PSROWS' => 'CODE'],
            'CLOSING_REASON_DETAILS' => ['PSROWS' => 'GROUND'],
        ]
    ];

    protected function afterCreateTypeModel(BaseVTBDocumentType $typeModel, IBankDocument $ibankDocument)
    {
        parent::afterCreateTypeModel($typeModel, $ibankDocument);

        /** @var ContractUnReg $document */
        $document = $typeModel->document;

        $bik = $ibankDocument->getSenderBik();
        $inn = $ibankDocument->getSenderInn();

        if ($bik) {
            $bankBranch = DictVTBBankBranch::findOne(['bik' => $bik]);
            $document->BANKVKFULLNAME = $bankBranch->fullName;
        }

        if ($inn) {
            $document->CUSTOMERINN = $inn;
        }

        $document->SENDEROFFICIALS = 'Иванов Иван';
        $document->PHONEOFFICIALS = '89263335566';
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
