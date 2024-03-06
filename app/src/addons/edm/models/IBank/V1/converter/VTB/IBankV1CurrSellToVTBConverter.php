<?php

namespace addons\edm\models\IBank\V1\converter\VTB;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use addons\edm\models\DictBank;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt;
use addons\edm\models\IBank\common\IBankDocument;
use addons\edm\models\VTBCurrSell\VTBCurrSellType;
use common\helpers\vtb\VTBHelper;
use common\models\vtbxml\documents\CurrSell;

class IBankV1CurrSellToVTBConverter extends BaseIBankV1ToVTBConverter
{
    const TYPE_MODEL_CLASS = VTBCurrSellType::class;
    const VTB_DOCUMENT_VERSION = 5;
    const EXT_MODEL_CLASS = ForeignCurrencyOperationDocumentExt::class;
    const MAPPING = [
        'DOCUMENTDATE',
        'DOCUMENTNUMBER',
        'DEALTYPE',
        'SENDEROFFICIALS',
        'PHONEOFFICIAL',
        'ACCOUNTDEBET',
        'CURRCODEDEBET',
        'AMOUNTDEBET',
        'ACCOUNTCREDIT',
        'CURRCODECREDIT',
        'AMOUNTCREDIT',
        'BALANCEACCOUNT',
        'CHARGETYPE',
        'CHARGEACCOUNT',
        'REQUESTRATETYPE',
        'REQUESTRATE',
        'SUPPLYCONDITION',
        'SUPPLYCONDITIONDATE',
        'OPERCODE',
        null,
        null,
        [
            'GROUNDRECEIPTSBLOB' => [
                'DOCTYPECODE',
                'DOCUMENTNUMBER',
                'DOCUMENTDATE',
                'DESCRIPTION'
            ]
        ],
        'PAYMENTDETAILS',
        'CODEMESSAGE',
        'MESSAGEFORBANK'
    ];

    protected function afterCreateTypeModel(BaseVTBDocumentType $typeModel, IBankDocument $ibankDocument)
    {
        parent::afterCreateTypeModel($typeModel, $ibankDocument);

        /** @var CurrSell $document */
        $document = $typeModel->document;

        $organization = VTBHelper::getOrganizationByVTBCustomerId($document->CUSTID);
        if ($organization !== null) {
            $document->CUSTOMERNAME = $organization->name;
            $document->CUSTOMERINN = $organization->inn;
            $document->CUSTOMERADDRESS = $organization->address;
            $document->CUSTOMERPLACE = $organization->city;
            $document->CUSTOMERPROPERTYTYPE = $organization->propertyTypeCode;
        }

        $debitAccount = EdmPayerAccount::findOne(['number' => $document->ACCOUNTDEBET]);
        if ($debitAccount !== null) {
            /** @var DictBank $bank */
            $bank = $debitAccount->bank;
            $document->DEBETBANKBIC = $bank->bik;
            $document->DEBETBANKNAME = $bank->name;
            $document->DEBETBANKPLACE = $bank->city;
            $document->DEBETBANKCORRACC = $bank->account;
        }

        // В случае зачисления купленной валюты на счет стороннего Банка, отличного от Банка счета дебета, реквизиты заполнять не нужно.
        // Если  <ACCOUNTCREDIT> отсутствует в настройках организации являющейся владельцем счета <ACCOUNTDEBET> или <ACCOUNTCREDIT>
        // привязан в справочнике организаций к банку отличному от банка счета <ACCOUNTDEBET>, то реквизиты банка по счету кредита не заполняем.
        $creditAccount = EdmPayerAccount::findOne(['number' => $document->ACCOUNTCREDIT]);
        if ($creditAccount !== null) {
            /** @var DictBank $bank */
            $bank = $debitAccount->bank;
            if ($bank->bik === $document->DEBETBANKBIC) {
                $document->CREDITBANKBIC = $bank->bik;
                $document->CREDITBANKNAME = $bank->name;
                $document->CREDITBANKPLACE = $bank->city;
                $document->CREDITBANKCORRACC = $bank->account;
            }
        }
    }
}
