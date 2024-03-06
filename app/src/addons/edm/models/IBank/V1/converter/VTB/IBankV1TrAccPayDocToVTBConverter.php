<?php

namespace addons\edm\models\IBank\V1\converter\VTB;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationFactory;
use addons\edm\models\IBank\common\IBankDocument;
use addons\edm\models\VTBTransitAccPayDoc\VTBTransitAccPayDocType;
use common\base\BaseType;
use common\models\vtbxml\documents\TransitAccPayDoc;

class IBankV1TrAccPayDocToVTBConverter extends BaseIBankV1ToVTBConverter
{
    const TYPE_MODEL_CLASS = VTBTransitAccPayDocType::class;
    const VTB_DOCUMENT_VERSION = 8;
    const EXT_MODEL_CLASS = ForeignCurrencyOperationDocumentExt::class;
    const MAPPING = [
        'DOCUMENTDATE',
        'DOCUMENTNUMBER',
        'DEALTYPE',
        'SENDEROFFICIALS',
        'PHONEOFFICIALS',
        'CURRCODE',
        'TOTALAMOUNT',
        'ACCOUNTTRANSIT',
        'AMOUNTDEBET',
        'CURRDEALINQUIRYDATE',
        'CURRDEALINQUIRYNUMBER',
        'ADDINFO',
        'ISCREDIT',
        'AMOUNTCREDIT',
        'RECEIVERCURRACCOUNT',
        'CREDITBANKBICCURR',
        'ISSELL',
        'AMOUNTSELL',
        'RECEIVERRURACCOUNT',
        'RECEIVERRURBIC',
        'SUPPLYCONDITION',
        'SUPPLYCONDITIONDATE',
        'REQUESTRATETYPE',
        'REQUESTRATE',
        'CHARGETYPE',
        'CHARGEACCOUNT',
        [
            'NOTICEBLOB' => [
                'NOTICENUMBER',
                'NOTICEDATE',
                'NOTICEAMOUNT',
                'OPERCODE',
                'NOTICECURRCODE',
                'DESCRIPTION',
            ]
        ],
        [
            'GROUNDRECEIPTSBLOB' => [
                'DOCTYPECODE',
                'DOCUMENTNUMBER',
                'DOCUMENTDATE',
                'DESCRIPTION',
            ]
        ]
    ];

    const SUPPLY_CONDITIONS = [
        1 => 'Сроком "сегодня"',
        2 => 'Сроком "завтра"',
        3 => 'Сроком "послезавтра"',
    ];

    protected function afterCreateTypeModel(BaseVTBDocumentType $typeModel, IBankDocument $ibankDocument)
    {
        parent::afterCreateTypeModel($typeModel, $ibankDocument);

        /** @var TransitAccPayDoc $document */
        $document = $typeModel->document;
        $payerAccount = EdmPayerAccount::findOne(['number' => $document->ACCOUNTTRANSIT]);
        if ($payerAccount) {
            $document->CUSTOMERNAME = $payerAccount->getPayerName();
        }

        if (array_key_exists($document->SUPPLYCONDITION, static::SUPPLY_CONDITIONS)) {
            $document->SUPPLYCONDITION = static::SUPPLY_CONDITIONS[$document->SUPPLYCONDITION];
        } else {
            $document->SUPPLYCONDITION = null;
        }
    }

    public function createExtModelAttributes(BaseType $typeModel): array
    {
        $attributes = parent::createExtModelAttributes($typeModel);
        $attributes['documentType'] = ForeignCurrencyOperationFactory::OPERATION_SELL_TRANSIT_ACCOUNT;

        return $attributes;
    }
}
