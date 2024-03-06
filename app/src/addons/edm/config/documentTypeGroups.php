<?php

use addons\edm\models\EdmDocumentTypeGroup;

return [
    [
        'id' => EdmDocumentTypeGroup::RUBLE_PAYMENT,
        'types' => [
            \addons\edm\models\PaymentOrder\PaymentOrderType::TYPE,
            \addons\edm\models\PaymentRegister\PaymentRegisterType::TYPE,
            \addons\edm\models\VTBPayDocRu\VTBPayDocRuType::TYPE,
            \addons\edm\models\VTBRegisterRu\VTBRegisterRuType::TYPE,
            \addons\edm\models\SBBOLPayDocRu\SBBOLPayDocRuType::TYPE,
            \addons\edm\models\Sbbol2PayDocRu\Sbbol2PayDocRuType::TYPE,
            \addons\edm\models\Pain001Rub\Pain001RubType::TYPE,
        ],
        'name' => Yii::t('edm', 'Rouble payments'),
        'availablePermissions' => \common\document\DocumentPermission::all(),
    ],
    [
        'id' => EdmDocumentTypeGroup::CURRENCY_PAYMENT,
        'types' => [
            'MT103',
            \addons\edm\models\VTBPayDocCur\VTBPayDocCurType::TYPE,
            \addons\edm\models\VTBRegisterCur\VTBRegisterCurType::TYPE,
            \addons\edm\models\Pain001Fcy\Pain001FcyType::TYPE,
        ],
        'name' => Yii::t('edm', 'Currency payments'),
        'availablePermissions' => \common\document\DocumentPermission::all(),
    ],
    [
        'id' => EdmDocumentTypeGroup::CURRENCY_PURCHASE,
        'types' => [
            \addons\edm\models\Pain001Fx\Pain001FxType::TYPE,
            \addons\edm\models\VTBCurrBuy\VTBCurrBuyType::TYPE,
        ],
        'name' => Yii::t('edm', 'Currency purchase'),
        'availablePermissions' => \common\document\DocumentPermission::all(),
        'discriminators' => [
            \addons\edm\models\Pain001Fx\Pain001FxType::TYPE => [
                'attribute' => 'documentType',
                'value' => \addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationFactory::OPERATION_PURCHASE,
            ],
        ],
    ],
    [
        'id' => EdmDocumentTypeGroup::CURRENCY_SELL,
        'types' => [
            \addons\edm\models\Pain001Fx\Pain001FxType::TYPE,
            \addons\edm\models\VTBCurrSell\VTBCurrSellType::TYPE,
        ],
        'name' => Yii::t('edm', 'Currency sell'),
        'availablePermissions' => \common\document\DocumentPermission::all(),
        'discriminators' => [
            \addons\edm\models\Pain001Fx\Pain001FxType::TYPE => [
                'attribute' => 'documentType',
                'value' => \addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationFactory::OPERATION_SELL,
            ],
        ],
    ],
    [
        'id' => EdmDocumentTypeGroup::TRANSIT_ACCOUNT_PAYMENT,
        'types' => [
            'pain.001',
            \addons\edm\models\Pain001Rls\Pain001RlsType::TYPE,
            \addons\edm\models\VTBTransitAccPayDoc\VTBTransitAccPayDocType::TYPE,
        ],
        'name' => Yii::t('edm', 'Sell of foreign currency from the transit account'),
        'availablePermissions' => \common\document\DocumentPermission::all(),
        'discriminators' => [
            'pain.001' => [
                'attribute' => 'documentType',
                'value' => \addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationFactory::OPERATION_SELL_TRANSIT_ACCOUNT,
            ],
        ],
    ],
    [
        'id' => EdmDocumentTypeGroup::CURRENCY_CONVERSION,
        'types' => [
            'pain.001',
            \addons\edm\models\VTBCurrConversion\VTBCurrConversionType::TYPE,
        ],
        'name' => Yii::t('edm', 'Currency conversion'),
        'availablePermissions' => [
            \common\document\DocumentPermission::VIEW,
            \common\document\DocumentPermission::DELETE,
            \common\document\DocumentPermission::SIGN,
        ],
        'discriminators' => [
            'pain.001' => [
                'attribute' => 'documentType',
                'value' => \addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationFactory::OPERATION_CONVERSION,
            ],
        ],
    ],
    [
        'id' => EdmDocumentTypeGroup::CURRENCY_DEAL_INQUIRY,
        'types' => [
            \addons\ISO20022\models\Auth024Type::TYPE,
            \addons\edm\models\VTBCurrDealInquiry181i\VTBCurrDealInquiry181iType::TYPE,
        ],
        'name' => Yii::t('edm', 'Currency deal inquiry'),
        'availablePermissions' => \common\document\DocumentPermission::all(),
    ],
    [
        'id' => EdmDocumentTypeGroup::CONFIRMING_DOCUMENTS_INQUIRY,
        'types' => [
            \addons\ISO20022\models\Auth025Type::TYPE,
            \addons\edm\models\VTBConfDocInquiry138I\VTBConfDocInquiry138IType::TYPE,
        ],
        'name' => Yii::t('edm', 'Confirming document information'),
        'availablePermissions' => \common\document\DocumentPermission::all(),
    ],
    [
        'id' => EdmDocumentTypeGroup::LOAN_AGREEMENT_REGISTRATION_REQUEST,
        'types' => [
            \addons\ISO20022\models\Auth018Type::TYPE,
            \addons\edm\models\VTBCredReg\VTBCredRegType::TYPE,
        ],
        'name' => Yii::t('edm', 'Loan agreement registration request'),
        'availablePermissions' => \common\document\DocumentPermission::all(),
        'discriminators' => [
            \addons\ISO20022\models\Auth018Type::TYPE => [
                'attribute' => 'passportType',
                'value' => \addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestExt::PASSPORT_TYPE_LOAN,
            ],
        ],
    ],
    [
        'id' => EdmDocumentTypeGroup::CONTRACT_REGISTRATION_REQUEST,
        'types' => [
            \addons\ISO20022\models\Auth018Type::TYPE,
            \addons\edm\models\VTBContractReg\VTBContractRegType::TYPE,
        ],
        'name' => Yii::t('edm', 'Contract registration request'),
        'availablePermissions' => [
            \common\document\DocumentPermission::VIEW,
            \common\document\DocumentPermission::DELETE,
            \common\document\DocumentPermission::SIGN,
        ],
        'discriminators' => [
            \addons\ISO20022\models\Auth018Type::TYPE => [
                'attribute' => 'passportType',
                'value' => \addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestExt::PASSPORT_TYPE_TRADE,
            ],
        ],
    ],
    [
        'id' => EdmDocumentTypeGroup::CONTRACT_UNREGISTRATION_REQUEST,
        'types' => [
            \addons\edm\models\VTBContractUnReg\VTBContractUnRegType::TYPE,
        ],
        'name' => Yii::t('edm', 'Contract unregistration request'),
        'availablePermissions' => \common\document\DocumentPermission::all(),
    ],
    [
        'id' => EdmDocumentTypeGroup::CONTRACT_CHANGE_REQUEST,
        'types' => [
            \addons\edm\models\VTBContractChange\VTBContractChangeType::TYPE,
        ],
        'name' => Yii::t('edm', 'Contract change'),
        'availablePermissions' => [
            \common\document\DocumentPermission::VIEW,
            \common\document\DocumentPermission::DELETE,
            \common\document\DocumentPermission::SIGN,
        ],
    ],
    [
        'id' => EdmDocumentTypeGroup::BANK_LETTER,
        'types' => [
            \addons\edm\models\VTBFreeClientDoc\VTBFreeClientDocType::TYPE,
            \addons\edm\models\VTBFreeBankDoc\VTBFreeBankDocType::TYPE,
            \addons\ISO20022\models\Auth026Type::TYPE,
        ],
        'name' => Yii::t('edm', 'Letter to bank'),
        'availablePermissions' => \common\document\DocumentPermission::all(),
    ],
    [
        'id' => EdmDocumentTypeGroup::STATEMENT,
        'types' => [
            \addons\edm\models\Statement\StatementType::TYPE,
            \addons\edm\models\VTBStatementRu\VTBStatementRuType::TYPE,
            \addons\edm\models\SBBOLStatement\SBBOLStatementType::TYPE,
            \addons\edm\models\Sbbol2Statement\Sbbol2StatementType::TYPE,
            \addons\edm\models\RaiffeisenStatement\RaiffeisenStatementType::TYPE,
	    \addons\ISO20022\models\Camt052Type::TYPE,
	    \addons\ISO20022\models\Camt053Type::TYPE,
	    \addons\ISO20022\models\Camt054Type::TYPE,
        ],
        'name' => Yii::t('edm', 'Statement'),
        'availablePermissions' => [
            \common\document\DocumentPermission::VIEW,
            \common\document\DocumentPermission::CREATE,
        ],
    ],
    [
        'id' => EdmDocumentTypeGroup::CANCELLATION_REQUEST,
        'types' => [
            addons\edm\models\VTBCancellationRequest\VTBCancellationRequestType::TYPE,
        ],
        'name' => Yii::t('edm', 'Cancellation request'),
        'availablePermissions' => \common\document\DocumentPermission::all(),
    ],
];
