<?php

namespace addons\edm\models\IBank\V1\converter\VTB;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationExt;
use addons\edm\models\IBank\common\IBankDocument;
use addons\edm\models\VTBConfDocInquiry138I\VTBConfDocInquiry138IType;
use common\base\BaseType;
use common\helpers\vtb\VTBHelper;
use common\models\vtbxml\documents\ConfDocInquiry138I;

class IBankV1ConfDInq181iToVTBConverter extends BaseIBankV1ToVTBConverter
{
    const TYPE_MODEL_CLASS = VTBConfDocInquiry138IType::class;
    const VTB_DOCUMENT_VERSION = 7;
    const EXT_MODEL_CLASS = ConfirmingDocumentInformationExt::class;
    const MAPPING = [
        null,
        'CUSTID',
        'DOCUMENTDATE',
        'DOCUMENTNUMBER',
        'SENDEROFFICIALS',
        'PHONEOFFICIALS',
        'PSNUMBER',
        [
            'CONFDOCPSBLOB' => [
                'NUM',
                'DOCUMENTNUMBER',
                'DOCDATE',
                'DOCCODE',
                'CURRCODE1',
                'AMOUNTCURRENCY1',
                'CURRCODE2',
                'AMOUNTCURRENCY2',
                'FDELIVERY',
                'EXPECTDATE',
                'COUNTRYCODE',
                'ADDINFO',
                'AMOUNTCURRENCY11',
                'AMOUNTCURRENCY21',
                'CORRECTION',
            ]
        ],
        [
            'DOCATTACHMENT' => [
                'FILENAME',
                'FILECONTENT'
            ]
        ]
    ];

    protected function afterCreateTypeModel(BaseVTBDocumentType $typeModel, IBankDocument $ibankDocument)
    {
        parent::afterCreateTypeModel($typeModel, $ibankDocument);

        /** @var ConfDocInquiry138I $document */
        $document = $typeModel->document;

        $organization = VTBHelper::getOrganizationByVTBCustomerId($document->CUSTID);
        if ($organization !== null) {
            $document->CUSTOMERNAME = $organization->name;
            $document->CUSTOMERINN = $organization->inn;
        }

        $this->fillCustomerBankAttributes($typeModel, $ibankDocument);
    }

    public function createExtModelAttributes(BaseType $typeModel): array
    {
        $extModel = new ConfirmingDocumentInformationExt();
        $extModel->loadContentModel($typeModel);
        return array_merge(
            $extModel->dirtyAttributes,
            ['documents' => $extModel->documents]
        );
    }
}
