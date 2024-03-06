<?php

namespace addons\edm\models\IBank\V1\converter\VTB;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use addons\edm\models\DictBank;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt;
use addons\edm\models\IBank\common\IBankDocument;
use addons\edm\models\VTBCurrBuy\VTBCurrBuyType;
use addons\swiftfin\models\SwiftFinDictBank;
use common\helpers\vtb\VTBHelper;
use common\models\vtbxml\documents\CurrBuy;

class IBankV1CurrBuyToVTBConverter extends BaseIBankV1ToVTBConverter
{
    const TYPE_MODEL_CLASS = VTBCurrBuyType::class;
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
        'NONACCEPTAGREENUMBER',
        'NONACCEPTAGREEDATE',
        'TRANSFERDOCUMENTNUMBER',
        'TRANSFERDOCUMENTDATE',
        'ACCOUNTCREDIT',
        'CURRCODECREDIT',
        'AMOUNTCREDIT',
        'CREDITBANKBIC',
        'CREDITBANKSWIFT',
        'BALANCEACCOUNT',
        'CHARGETYPE',
        'CHARGEACCOUNT',
        'REQUESTRATETYPE',
        'REQUESTRATE',
        'SUPPLYCONDITION',
        'SUPPLYCONDITIONDATE',
        'GROUNDCODE',
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
        'GROUNDDOCUMENT',
        'CODEMESSAGE',
        'MESSAGEFORBANK',
        null
    ];

    protected function afterCreateTypeModel(BaseVTBDocumentType $typeModel, IBankDocument $ibankDocument)
    {
        parent::afterCreateTypeModel($typeModel, $ibankDocument);

        /** @var CurrBuy $document */
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

        // Если CREDITBANKBIC соответствует БИК Банка организации отправителя то CREDITBANKSWIFT = VTBRRUMMXXX
        // Если CREDITBANKBIC не соответствует БИК Банка организации отправителя  то CREDITBANKSWIFT нужно мапить
        // из поля CreditBankSWIFT импортированного iBank
        if (!empty($document->CREDITBANKBIC)) {
            if ($debitAccount->bank->bik == $document->CREDITBANKBIC) {
                $document->CREDITBANKSWIFT = 'VTBRRUMMXXX';
            }
        }

        // Если <ACCOUNTCREDIT> отсутствует в настройках организации являющейся владельцем счета <ACCOUNTDEBET>
        // или <ACCOUNTCREDIT> привязан в справочнике организаций к банку отличному от банка счета <ACCOUNTDEBET>, то
        // по SWIFT-коду определить наименование и адрес банка из справочника SWIFT и передать в теге DEPOINFO
        $needSwiftRequisites = ($creditAccount === null || $document->DEBETBANKBIC !== $document->CREDITBANKBIC) && !empty($document->CREDITBANKSWIFT);
        if ($needSwiftRequisites) {
            /** @var SwiftFinDictBank $swiftBank */
            $swiftBank = SwiftFinDictBank::findByCode($document->CREDITBANKSWIFT);
            if ($swiftBank !== null) {
                $document->DEPOINFO = "{$swiftBank->name}, {$swiftBank->address}";
            }
        }
    }
}
