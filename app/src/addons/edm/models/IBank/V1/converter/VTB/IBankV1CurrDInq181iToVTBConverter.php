<?php

namespace addons\edm\models\IBank\V1\converter\VTB;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\ForeignCurrencyControl\ForeignCurrencyOperationInformationExt;
use addons\edm\models\IBank\common\IBankDocument;
use addons\edm\models\VTBCurrDealInquiry181i\VTBCurrDealInquiry181iType;
use common\models\vtbxml\documents\CurrDealInquiry181i;

class IBankV1CurrDInq181iToVTBConverter extends BaseIBankV1ToVTBConverter
{
    const TYPE_MODEL_CLASS = VTBCurrDealInquiry181iType::class;
    const VTB_DOCUMENT_VERSION = 7;
    const EXT_MODEL_CLASS = ForeignCurrencyOperationInformationExt::class;
    const MAPPING = [
        null,
        null,
        'DOCUMENTDATE',
        'DOCUMENTNUMBER',
        'SENDEROFFICIALS',
        'PHONEOFFICIAL',
        'ACCOUNT',
        'BANKCOUNTRYCODE',
        'FCORRECTION',
        null,
        [
            'DEALINFOBLOB' => [
                'AMOUNTPSCURRENCY',
                'CURRCODEPS',
                'DOCUMENTNUMBER',
                'OPERCODE',
                'PSNUMBER',
                'PAYMENTAMOUNT',
                'PAYMENTCURRCODE',
                'TRANSFERDATE',
                'NUM',
                'OPERDATE',
                'EXPECTDATE',
                'FOPERNUMMODE',
                'CONTRACTNUMBER',
                'CONTRACTDATE',
                'PAYMENTDIRECTION',
                'PREPAYRETURN',
                'DOCUMENTTYPEID',
                'REMARK',
            ],
        ],
        null,
        'CODEMESSAGE',
        'MESSAGEFORBANK',
    ];

    protected function afterCreateTypeModel(BaseVTBDocumentType $typeModel, IBankDocument $ibankDocument)
    {
        parent::afterCreateTypeModel($typeModel, $ibankDocument);

        /** @var CurrDealInquiry181i $document */
        $document = $typeModel->document;

        $payerAccount = EdmPayerAccount::findOne(['number' => $document->ACCOUNT]);
        if ($payerAccount !== null) {
            $document->CUSTOMERNAME = $payerAccount->getPayerName();
            $org = DictOrganization::findOne($payerAccount->organizationId);
            if ($org) {
                $document->CUSTOMERINN = $org->inn;
            }
        }

        $this->fillCustomerBankAttributes($typeModel, $ibankDocument);
    }

}
