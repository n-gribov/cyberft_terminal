<?php

namespace addons\edm\models\VTBDocument\presenters\config;

use common\models\vtbxml\documents\CancellationRequest;
use common\models\vtbxml\documents\ConfDocInquiry138I;
use common\models\vtbxml\documents\ConfDocInquiry138IConfDocNotPS;
use common\models\vtbxml\documents\ConfDocInquiry138IConfDocPS;
use common\models\vtbxml\documents\ContractChange;
use common\models\vtbxml\documents\ContractReg;
use common\models\vtbxml\documents\ContractRegNonresidentInfo;
use common\models\vtbxml\documents\CredReg;
use common\models\vtbxml\documents\CurrBuy;
use common\models\vtbxml\documents\CurrConversion;
use common\models\vtbxml\documents\CurrDealInquiry181i;
use common\models\vtbxml\documents\CurrDealInquiry181iDealInfo;
use common\models\vtbxml\documents\CurrSell;
use common\models\vtbxml\documents\DocInfo;
use common\models\vtbxml\documents\FreeBankDoc;
use common\models\vtbxml\documents\FreeClientDoc;
use common\models\vtbxml\documents\PayDocCur;
use common\models\vtbxml\documents\PayDocCurGroundDocument;
use common\models\vtbxml\documents\PayDocCurOperCode;
use common\models\vtbxml\documents\PayDocRu;
use common\models\vtbxml\documents\PayRollDoc;
use common\models\vtbxml\documents\StatementQuery;
use common\models\vtbxml\documents\StatementRu;
use common\models\vtbxml\documents\StatementRuDocument;
use common\models\vtbxml\documents\TransitAccPayDoc;
use common\models\vtbxml\documents\TransitAccPayDocNotice;

class BSDocumentPresenterConfigFactory
{
    public static function createForType($documentType)
    {        
        $configs = [
            PayDocCur::TYPE                   => PayDocCurPresenterConfig::class,
            PayDocCurGroundDocument::TYPE     => PayDocCurGroundDocumentPresenterConfig::class,
            PayDocCurOperCode::TYPE           => PayDocCurOperCodePresenterConfig::class,
            ContractReg::TYPE                 => ContractRegPresenterConfig::class,
            CredReg::TYPE                     => CredRegPresenterConfig::class,
            CurrConversion::TYPE              => CurrConversionPresenterConfig::class,
            CurrDealInquiry181i::TYPE         => CurrDealInquiry181iPresenterConfig::class,
            CurrDealInquiry181iDealInfo::TYPE => CurrDealInquiry181iDealInfoPresenterConfig::class,
            FreeBankDoc::TYPE                 => FreeBankDocPresenterConfig::class,
            FreeClientDoc::TYPE               => FreeClientDocPresenterConfig::class,
            StatementQuery::TYPE              => StatementQueryPresenterConfig::class,
            StatementRu::TYPE                 => StatementRuPresenterConfig::class,
            StatementRuDocument::TYPE         => StatementRuDocumentPresenterConfig::class,
            TransitAccPayDoc::TYPE            => TransitAccPayDocPresenterConfig::class,
            CurrBuy::TYPE                     => CurrBuyPresenterConfig::class,
            CurrSell::TYPE                    => CurrSellPresenterConfig::class,
            ConfDocInquiry138I::TYPE          => ConfDocInquiry138IPresenterConfig::class,
            ConfDocInquiry138IConfDocPS::TYPE => ConfDocInquiry138IConfDocPSPresenterConfig::class,
            CancellationRequest::TYPE         => CancellationRequestPresenterConfig::class,
            ContractChange::TYPE              => ContractChangePresenterConfig::class,
            ContractRegNonresidentInfo::TYPE  => ContractRegNonResidentInfoPresenterConfig::class,  
            // configless documents have common currency field config
            ConfDocInquiry138IConfDocNotPS::TYPE => CommonCurrencyFieldPresenterConfig::class,
            DocInfo::TYPE                     => CommonCurrencyFieldPresenterConfig::class,
            PayDocRu::TYPE                    => CommonCurrencyFieldPresenterConfig::class,
            PayRollDoc::TYPE                  => CommonCurrencyFieldPresenterConfig::class,
            TransitAccPayDocNotice::TYPE      => CommonCurrencyFieldPresenterConfig::class,
        ];

        $configClass = array_key_exists($documentType, $configs)
            ? $configs[$documentType]
            : BSDocumentPresenterConfig::class;

        return new $configClass();
    }

}
