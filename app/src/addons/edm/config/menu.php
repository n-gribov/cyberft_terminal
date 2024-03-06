<?php
return [
    'id' => 'Edm',
    'label' => 'Banking',
    'environment' => 'DEBUG',
    'url' => ['/edm/default'],
    'iconClass' => 'ic-bank',
    'serviceID' => 'edm',
    'before' => 'FileAct',
    'items' => [
        [
            'label' => 'Documents for signing',
            'url' => ['/edm/documents/signing-index'],
            'permission' => \common\document\DocumentPermission::SIGN,
            'permissionParams' => [
                'serviceId' => \addons\edm\EdmModule::SERVICE_ID,
                'documentTypeGroup' => '*'
            ],
            'iconClass' => 'fa fa-pencil-square-o',
            'extData' => 'forSigningEdmCount'
        ],
        [
            'label'	=> 'Rouble payments',
            'url'	=> ['/edm/payment-register/index'],
            'permission'	=> \common\document\DocumentPermission::VIEW,
            'permissionParams' => [
                'serviceId' => \addons\edm\EdmModule::SERVICE_ID,
                'documentTypeGroup' => \addons\edm\models\EdmDocumentTypeGroup::RUBLE_PAYMENT,
            ],
            'iconClass'	=> 'fa fa-paste',
        ],
        [
            'label'      => 'Currency payments',
            'url'        => ['/edm/currency-payment/register-index'],
            'permission' => \common\document\DocumentPermission::VIEW,
            'permissionParams' => [
                'serviceId' => \addons\edm\EdmModule::SERVICE_ID,
                'documentTypeGroup' => [
                    \addons\edm\models\EdmDocumentTypeGroup::CURRENCY_PAYMENT,
                ],
            ],
        ],
        [
            'label'      => 'Currency operations',
            'url'        => ['/edm/documents/foreign-currency-operation-journal'],
            'permission' => \common\document\DocumentPermission::VIEW,
            'permissionParams' => [
                'serviceId' => \addons\edm\EdmModule::SERVICE_ID,
                'documentTypeGroup' => [
                    \addons\edm\models\EdmDocumentTypeGroup::CURRENCY_SELL,
                    \addons\edm\models\EdmDocumentTypeGroup::CURRENCY_PURCHASE,
                    \addons\edm\models\EdmDocumentTypeGroup::CURRENCY_CONVERSION,
                    \addons\edm\models\EdmDocumentTypeGroup::TRANSIT_ACCOUNT_PAYMENT,
                ],
            ],
        ],
        [
            'label'      => 'Foreign currency control',
            'url'        => ['/edm/documents/foreign-currency-control-index'],
            'permission' => \common\document\DocumentPermission::VIEW,
            'permissionParams' => [
                'serviceId' => \addons\edm\EdmModule::SERVICE_ID,
                'documentTypeGroup' => [
                    \addons\edm\models\EdmDocumentTypeGroup::CONTRACT_REGISTRATION_REQUEST,
                    \addons\edm\models\EdmDocumentTypeGroup::LOAN_AGREEMENT_REGISTRATION_REQUEST,
                    \addons\edm\models\EdmDocumentTypeGroup::CONTRACT_CHANGE_REQUEST,
                    \addons\edm\models\EdmDocumentTypeGroup::CONTRACT_UNREGISTRATION_REQUEST,
                    \addons\edm\models\EdmDocumentTypeGroup::CURRENCY_DEAL_INQUIRY,
                    \addons\edm\models\EdmDocumentTypeGroup::CONFIRMING_DOCUMENTS_INQUIRY,
                ],
            ],
        ],
        [
            'label'	=> 'Statements',
            'url'	=> ['/edm/documents/statement'],
            'permission'	=> \common\document\DocumentPermission::VIEW,
            'permissionParams' => [
                'serviceId' => \addons\edm\EdmModule::SERVICE_ID,
                'documentTypeGroup' => \addons\edm\models\EdmDocumentTypeGroup::STATEMENT,
            ],
            'iconClass'	=> 'fa fa-paste',
            'extData' => 'newStatementCount'
        ],
        [
            'label' => 'Bank correspondence',
            'url' => ['/edm/bank-letter'],
            'permission' => \common\document\DocumentPermission::VIEW,
            'permissionParams' => [
                'serviceId' => \addons\edm\EdmModule::SERVICE_ID,
                'documentTypeGroup' => \addons\edm\models\EdmDocumentTypeGroup::BANK_LETTER,
            ],
            'extData' => 'newBankLetterCount'
        ],
        [
            'label'      => 'My templates',
            'url'        => ['/edm/payment-order-templates'],
            'permission' => \common\document\DocumentPermission::CREATE,
            'permissionParams' => [
                'serviceId' => \addons\edm\EdmModule::SERVICE_ID,
                'documentTypeGroup' => '*'
            ],
        ],
//        [
//            'label'      => 'Payers Accounts',
//            'url'        => ['/edm/edm-payer-account/index'],
//            'permission' => \common\document\DocumentPermission::VIEW,
//            'permissionParams' => [
//                'serviceId' => \addons\edm\EdmModule::SERVICE_ID,
//                'documentTypeGroup' => '*'
//            ],
//        ],
        [
            'label'      => 'Banks Directory',
            'url'        => ['/edm/dict-bank/index'],
            'permission' => \common\document\DocumentPermission::VIEW,
            'permissionParams' => [
                'serviceId' => \addons\edm\EdmModule::SERVICE_ID,
                'documentTypeGroup' => '*'
            ],
        ],
        [
            'label'      => 'Beneficiary Contractors Directory',
            'url'        => ['/edm/dict-beneficiary-contractor/index'],
            'permission' => \common\document\DocumentPermission::VIEW,
            'permissionParams' => [
                'serviceId' => \addons\edm\EdmModule::SERVICE_ID,
                'documentTypeGroup' => '*'
            ],
        ],
        [
            'label'      => 'Organizations Directory',
            'url'        => ['/edm/dict-organization/index'],
            'permission' => 'commonSettings',
        ],
    ]
];
