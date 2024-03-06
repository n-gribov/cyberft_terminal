<?php

namespace addons\edm\models\IBank\V2\converter\VTB;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use addons\edm\models\DictCurrency;
use addons\edm\models\DictOrganization;
use addons\edm\models\DictVTBBankBranch;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationFactory;
use addons\edm\models\IBank\common\IBankDocument;
use addons\edm\models\VTBCurrConversion\VTBCurrConversionType;
use common\base\BaseType;
use common\models\vtbxml\documents\CurrConversion;

class IBankV2CurrencyConvertToVTBConverter extends BaseIBankV2ToVTBConverter
{
    const TYPE_MODEL_CLASS = VTBCurrConversionType::class;
    const VTB_DOCUMENT_VERSION = 5;
    const EXT_MODEL_CLASS = ForeignCurrencyOperationDocumentExt::class;
    const MAPPING = [
        'DATE_DOC'  => 'DOCUMENTDATE',
        'NUM_DOC'   => 'DOCUMENTNUMBER',
        'CLN_OKPO' => 'CUSTOMEROKPO',
        'CLN_EMPLOYEE_FIO' => 'SENDEROFFICIALS',
        'CLN_EMPLOYEE_PHONES' => 'PHONEOFFICIAL',
        'SALE_SUM' => 'AMOUNTDEBET',
        'SALE_CURRENCY' => 'CURRCODEDEBET',
        'SALE_ACCOUNT' => 'ACCOUNTDEBET',
        'PURCHASE_SUM' => 'AMOUNTCREDIT',
        'PURCHASE_CURRENCY' => 'CURRCODECREDIT',
        'INSIDE_PURCHASE_ACCOUNT' => 'ACCOUNTCREDIT',
        'CONVERT_RATE_KIND' => 'REQUESTRATETYPE',
        'OUTSIDE_BNK_BIC' => 'CREDITBANKBIC',
        'OUTSIDE_PURCHASE_INFO' => 'PAYMENTDETAILS',
        'EXPERIENCE_DATE' => 'VALIDITYPERIOD',
        'TRANSFER_DATE' => 'TRANSFERDOCUMENTDATE',
        'ADDED_COND' => 'SUPPLYCONDITIONDATE',
        'CONVERT_RATE' => 'REQUESTRATE',
    ];

    protected function afterCreateTypeModel(BaseVTBDocumentType $typeModel, IBankDocument $ibankDocument)
    {
        parent::afterCreateTypeModel($typeModel, $ibankDocument);

        /** @var CurrConversion $document */
        $document = $typeModel->document;

        if ($document->CURRCODEDEBET) {
            $currencyDebet = DictCurrency::findOne(['name' => $document->CURRCODEDEBET]);

            if ($currencyDebet) {
                $document->CURRCODEDEBET = $currencyDebet->code;
            }
        }

        if ($document->CURRCODECREDIT) {
            $currencyCredit = DictCurrency::findOne(['name' => $document->CURRCODECREDIT]);

            if ($currencyCredit) {
                $document->CURRCODECREDIT = $currencyCredit->code;
            }
        }

        if ($document->ACCOUNTDEBET) {
            $document->BALANCEACCOUNT = $document->ACCOUNTDEBET;

            $account = EdmPayerAccount::findOne(['number' => $document->ACCOUNTDEBET]);

            if ($account) {
                $bankBranch = DictVTBBankBranch::findOne(['bik' => $account->bankBik]);

                if ($bankBranch) {
                    $document->DEBETBANKBIC = $bankBranch->bik;
                    $document->DEBETBANKNAME = $bankBranch->name;
                    $document->BALANCEBANKBIC = $bankBranch->bik;
                }

                /** @var DictOrganization $organization */
                $organization = $account->edmDictOrganization;

                if ($organization) {
                    $document->CUSTOMERNAME = $organization->name;
                    $document->CUSTOMERINN = $organization->inn;
                    $document->CUSTOMERADDRESS = $organization->address;
                }
            }
        }

        $rateTypes = [
            'по курсу банка' => 0,
            'по курсу ЦБ' => 0,
            'по курсу биржи' => 0,
            'по указанному курсу' => 2,
            'по курсу не выше чем' => 1,
            'по курсу не ниже чем' => 1,
        ];
        if (array_keys($rateTypes, $document->REQUESTRATETYPE)) {
            $document->REQUESTRATETYPE = $rateTypes[$document->REQUESTRATETYPE];
        } else {
            $document->REQUESTRATETYPE = null;
        }
    }

    public function createExtModelAttributes(BaseType $typeModel): array
    {
        $attributes = parent::createExtModelAttributes($typeModel);
        $attributes['documentType'] = ForeignCurrencyOperationFactory::OPERATION_CONVERSION;

        return $attributes;
    }
}
