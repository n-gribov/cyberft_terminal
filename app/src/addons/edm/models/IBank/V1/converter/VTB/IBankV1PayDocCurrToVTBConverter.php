<?php

namespace addons\edm\models\IBank\V1\converter\VTB;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt;
use addons\edm\models\IBank\common\IBankDocument;
use addons\edm\models\VTBPayDocCur\VTBPayDocCurType;
use common\models\vtbxml\documents\PayDocCur;

class IBankV1PayDocCurrToVTBConverter extends BaseIBankV1ToVTBConverter
{
    const TYPE_MODEL_CLASS = VTBPayDocCurType::class;
    const VTB_DOCUMENT_VERSION = 8;
    const EXT_MODEL_CLASS = ForeignCurrencyOperationDocumentExt::class;
    const MAPPING = [
        'DOCUMENTDATE',
        'DOCUMENTNUMBER',
        null,
        'CURRCODE',
        null,
        'PAYERACCOUNT',
        'BENEFBANKBIC',
        'IMEDIABANKACCOUNT',
        'BENEFBANKNAME',
        'BENEFBANKADDRESS',
        'BENEFBANKPLACE',
        'BENEFBANKCOUNTRYCODE',
        'BENEFICIARACCOUNT',
        'BENEFICIAR',
        null,
        'BENEFICIARADDRESS',
        'BENEFICIARPLACE',
        'BENEFICIARCOUNTRYCODE',
        'AMOUNT',
        'PAYMENTSDETAILS',
        'PAYMENTSDETAILS',
        'ISMULTICURR',
        'CURRCODETRANSFER',
        'AMOUNTTRANSFER',
        'CONVCHARGEACCOUNT',
        null,
        'IMEDIABANKBIC',
        'IMEDIABANKNAME',
        'IMEDIABANKADDRESS',
        'IMEDIABANKPLACE',
        'IMEDIABANKCOUNTRYCODE',
        'SENDEROFFICIALS',
        'OFFICIALSPHONE',
        'OFFICIALSFAX',
        'CHARGESTYPE',
        'CHARGESCURRCODE',
        'CHARGESACCOUNT',
        null,
        ['OPERCODEBLOB' => ['OPERCODE', 'AMOUNT', 'CURRCODE']],
        ['GROUNDDOCUMENTS' => ['DOCTYPECODE', 'DOCUMENTNUMBER', 'DOCUMENTDATE', 'DESCRIPTION']],
        'ADDINFOVALCONTROL',
        'CURRDEALINQUIRYNUMBER',
        'CURRDEALINQUIRYDATE',
        'INDEXOFSERVICE',
        'CODEMESSAGE',
        'MESSAGEFORBANK',
    ];

    protected function afterCreateTypeModel(BaseVTBDocumentType $typeModel, IBankDocument $ibankDocument)
    {
        parent::afterCreateTypeModel($typeModel, $ibankDocument);

        /** @var PayDocCur $document */
        $document = $typeModel->document;

        $document->PAYERBANKNAME = 'VTB BANK (PJSC)';
        $document->PAYERBANKBIC = 'VTBRRUMMXXX';
        $document->PAYERBANKADDRESS = 'VORONTSOVSKAYA UL. 43';
        $document->PAYERBANKBICTYPE = 'SWIFT';
        $document->PAYERBANKCOUNTRYCODE = '643';
        $document->PAYERCOUNTRYCODE = '643';

        if (!$document->ISMULTICURR) {
            $document->CURRCODETRANSFER = $document->CURRCODE;
            $document->AMOUNTTRANSFER = $document->AMOUNT;
        }

        $payerAccount = EdmPayerAccount::findOne(['number' =>$document->PAYERACCOUNT]);
        if ($payerAccount !== null) {
            /** @var DictOrganization $organization */
            $organization = $payerAccount->edmDictOrganization;

            $document->PAYER = $organization->nameLatin;

            $locationParts = preg_split('/\,\s*/', $organization->locationLatin, 2);
            $settlement = count($locationParts) > 0 ? $locationParts[0] : null;
            $addressParts = array_filter([$settlement, $organization->addressLatin]);
            $document->PAYERADDRESS = implode(', ', $addressParts);
            $document->PAYERPLACE = $settlement;
        }
    }

}
