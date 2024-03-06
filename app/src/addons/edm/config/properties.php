<?php

return [
    'class' => 'addons\edm\EdmModule',
    'serviceName' => 'edm',
    'fileMask' => '*.xml',
    'fileExt' => '.xml',
    'resources' => [
        'storage' => [
            'path' => '@storage/edm',
            'dirs' => [
                'in',
                'out',
                'export',
            ],
        ],
        'temp' => [
            'path' => '@storage/edm/temp',
        ],
        'export' => [
            'path' => '@export/edm',
            'dirs' => [
                'statementRequest' => [
                    'directory' => 'containers', 'usePartition' => false],
                'statement' => ['directory' => 'containers', 'usePartition' => false],
                'provcsv' => ['directory' => 'provcsv', 'usePartition' => false],
                '1c' => ['directory' => '1c', 'usePartition' => false],
                'xml' => ['directory' => 'xml', 'usePartition' => false],
            ]
        ],
        'import' => [
            'path' => '@import/edm',
            'dirs' => [
                'in' => ['directory' => 'in', 'usePartition' => false],
                'error' => ['directory' => 'error', 'usePartition' => false],
                'job' => ['directory' => 'job', 'usePartition' => false, 'useUniqueName' => false]
            ]
        ]
    ],
    'docTypes' => [
        'StatementRequest' => [
            'contentClass' => '\addons\edm\models\StatementRequest\StatementRequestCyberXmlContent',
            'extModelClass' => '\addons\edm\models\StatementRequest\StatementRequestExt',
            'typeModelClass' => '\addons\edm\models\StatementRequest\StatementRequestType',
            'resources' => [
                'export' => 'statementRequest',
                'import' => 'in'
            ],
            'jobs' => [
                'export' => '\addons\edm\jobs\ExportJob',
            ]
        ],
        'PaymentStatusReport' => [
            'contentClass' => '\addons\edm\models\PaymentStatusReport\PaymentStatusReportCyberXmlContent',
            //'extModelClass' => '\addons\edm\models\PaymentStatusReport\PaymentStatusReportExt',
            'typeModelClass' => '\addons\edm\models\PaymentStatusReport\PaymentStatusReportType',
            'resources' => [
                'export' => 'statementRequest',
                'import' => 'in'
            ],
            'jobs' => [
                'export' => '\addons\edm\jobs\ExportJob',
            ]
        ],
        'Statement' => [
            'contentClass' => '\addons\edm\models\Statement\StatementCyberXmlContent',
            'extModelClass' => '\addons\edm\models\Statement\StatementDocumentExt',
            'typeModelClass' => '\addons\edm\models\Statement\StatementType',
            'resources' => [
                'export' => '1c',
                'import' => 'in'
            ],
            'jobs' => [
                'export' => '\addons\edm\jobs\ExportJob',
            ],
            'dataView' => '@addons/edm/views/documents/readable/statement',
            'actionView' => 'viewStatement',
        ],
        'camt.052' => [
            'contentClass' => '\addons\ISO20022\models\Camt052CyberXmlContent',
            'extModelClass' => '\addons\edm\models\Statement\StatementDocumentExt',
            'typeModelClass' => '\addons\ISO20022\models\Camt052Type',
            'resources' => [
                'export' => '1c',
            ],
            'jobs' => [
                'export' => '\addons\edm\jobs\ExportJob',
            ],
            'dataView' => '@addons/edm/views/documents/readable/statement',
        ],
        'camt.053' => [
            'contentClass' => '\addons\ISO20022\models\Camt053CyberXmlContent',
            'extModelClass' => '\addons\edm\models\Statement\StatementDocumentExt',
            'typeModelClass' => '\addons\ISO20022\models\Camt053Type',
            'resources' => [
                'export' => '1c',
            ],
            'jobs' => [
                'export' => '\addons\edm\jobs\ExportJob',
            ],
            'dataView' => '@addons/edm/views/documents/readable/statement',
        ],
        'camt.054' => [
            'contentClass' => '\addons\ISO20022\models\Camt054CyberXmlContent',
            'extModelClass' => '\addons\edm\models\Statement\StatementDocumentExt',
            'typeModelClass' => '\addons\ISO20022\models\Camt054Type',
            'resources' => [
                'export' => '1c',
            ],
            'jobs' => [
                'export' => '\addons\edm\jobs\ExportJob',
            ],
            'dataView' => '@addons/edm/views/documents/readable/statement',
        ],
        'PROVCSV' => [
            'contentClass' => '\addons\edm\models\PROVCSV\ProvcsvCyberXmlContent',
            'extModelClass' => '\addons\edm\models\EdmDocumentExt',
            'typeModelClass' => '\addons\edm\models\PROVCSV\ProvcsvType',
            'resources' => [
                'export' => 'provcsv',
                'import' => 'in',
            ],
            'jobs' => [
                'export' => '\addons\edm\jobs\ExportJob',
                'incoming' => '\addons\edm\jobs\EdmInProvCSVJob'
            ],
            'dataView' => '@addons/edm/views/documents/_viewProvCSV',
            'views' => [
                'readable' => [
                    'label' => 'Readable ProvCSV',
                    'view' => '@addons/edm/views/documents/readable/provCSV',
                ],
            ],
        ],
        'PaymentOrder' => [
            'contentClass' => '\addons\edm\models\PaymentOrder\PaymentOrderCyberXmlContent',
            'extModelClass' => '\addons\edm\models\PaymentOrder\PaymentOrderDocumentExt',
            'typeModelClass' => '\addons\edm\models\PaymentOrder\PaymentOrderType',
            'resources' => [
                'export' => '1c',
                'import' => 'in',
            ],
            'jobs' => [
                'export' => '\addons\edm\jobs\ExportJob',
            ],
            'dataView' => '@addons/edm/views/documents/_viewPaymentOrder',
            'views' => [
                'readable' => [
                    'label' => 'Readable Payment Order',
                    'view' => '@addons/edm/views/documents/readable/paymentOrder',
                ],
                'printable' => [
                    'label' => 'Printable Payment Order',
                    'view' => '@addons/edm/views/documents/printable/paymentOrder',
                    'layout' => '/print',
                    'linkOptions' => ['target' => '_blank'],
                ],
                'exportXls' => [
                    'label' => Yii::t('app', 'Export as {format}', ['format' => 'Excel']),
                ],
                'export1C' => [
                    'label' => Yii::t('app', 'Export as {format}', ['format' => '1C']),
                ]
            ],
        ],
        'PaymentRegister' => [
            'contentClass' => '\addons\edm\models\PaymentRegister\PaymentRegisterCyberXmlContent',
            'extModelClass' => '\addons\edm\models\PaymentRegister\PaymentRegisterDocumentExt',
            'typeModelClass' => '\addons\edm\models\PaymentRegister\PaymentRegisterType',
            'exportType' => '1c',
            'resources' => [
                'export' => 'xml',
            ],
            'jobs' => [
                'export' => '\addons\edm\jobs\ExportJob',
            ],
            'dataView' => '@addons/edm/views/documents/_viewPaymentRegister',
            'views' => [
                'readable' => [
                    'label' => 'Readable Payment Order',
                    'view' => '@addons/edm/views/documents/readable/paymentRegister',
                ],
                'printable' => [
                    'label' => 'Printable Payment Order',
                    'view' => '@addons/edm/views/documents/printable/paymentRegister',
                    'layout' => '/print',
                    'linkOptions' => ['target' => '_blank'],
                ],
                'exportXls' => [
                    'label' => Yii::t('app', 'Export as {format}', ['format' => 'Excel']),
                ],
                'export1C' => [
                    'label' => Yii::t('app', 'Export as {format}', ['format' => '1C']),
                ]
            ],
        ],
        'pain.001.RUB' => [
            'contentClass' => '\addons\edm\models\Pain001Rub\Pain001RubCyberXmlContent',
            'extModelClass' => '\addons\edm\models\PaymentRegister\PaymentRegisterDocumentExt',
            'typeModelClass' => '\addons\edm\models\Pain001Rub\Pain001RubType',
            'exportType' => '1c',
            'resources' => [
                'export' => 'xml',
            ],
            'jobs' => [
                'export' => '\addons\edm\jobs\ExportJob',
            ],
            'dataView' => '@addons/edm/views/documents/_viewPaymentRegister',
            'views' => [
                'readable' => [
                    'label' => 'Readable Payment Order',
                    'view' => '@addons/edm/views/documents/readable/paymentRegister',
                ],
                'printable' => [
                    'label' => 'Printable Payment Order',
                    'view' => '@addons/edm/views/documents/printable/paymentRegister',
                    'layout' => '/print',
                    'linkOptions' => ['target' => '_blank'],
                ],
                'exportXls' => [
                    'label' => Yii::t('app', 'Export as {format}', ['format' => 'Excel']),
                ],
                'export1C' => [
                    'label' => Yii::t('app', 'Export as {format}', ['format' => '1C']),
                ]
            ],
        ],
        'pain.001.RLS' => [
            'contentClass' => '\addons\edm\models\Pain001Rls\Pain001RlsCyberXmlContent',
            'extModelClass' => '\addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt',
            'typeModelClass' => '\addons\edm\models\Pain001Rls\Pain001RlsType',
            'dataView' => '@addons/ISO20022/views/documents/_view',
        ],
        'pain.001.FCY' => [
            'contentClass' => '\addons\edm\models\Pain001Fcy\Pain001FcyCyberXmlContent',
            'extModelClass' => '\addons\edm\models\CurrencyPaymentRegister\CurrencyPaymentRegisterDocumentExt',
            'typeModelClass' => '\addons\edm\models\Pain001Fcy\Pain001FcyType',
            'dataView' => '@addons/ISO20022/views/documents/_view',
        ],
        'pain.001.FX' => [
            'contentClass' => '\addons\edm\models\Pain001Fx\Pain001FxCyberXmlContent',
            'extModelClass' => '\addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt',
            'typeModelClass' => '\addons\edm\models\Pain001Fx\Pain001FxType',
            'dataView' => '@addons/ISO20022/views/documents/_view',
        ],
        'edm:MT103' => [
            'contentClass' => '\addons\swiftfin\models\SwiftFinCyberXmlContent',
            'typeModelClass' => '\addons\swiftfin\models\SwiftFinType',
            'extModelClass' => '\addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt',
        ],
        'SBBOLPayDocRu' => [
            'contentClass' => '\addons\edm\models\SBBOLPayDocRu\SBBOLPayDocRuCyberXmlContent',
            'extModelClass' => '\addons\edm\models\PaymentRegister\PaymentRegisterDocumentExt',
            'typeModelClass' => '\addons\edm\models\SBBOLPayDocRu\SBBOLPayDocRuType',
            'jobs' => [
                'export' => '\addons\edm\jobs\ExportJob',
            ],
            'resources' => [
                'export' => 'xml',
            ],
            'dataView' => '@addons/edm/views/documents/_viewPaymentRegister',
        ],
        'Sbbol2PayDocRu' => [
            'contentClass' => '\addons\edm\models\Sbbol2PayDocRu\Sbbol2PayDocRuCyberXmlContent',
            'extModelClass' => '\addons\edm\models\PaymentRegister\PaymentRegisterDocumentExt',
            'typeModelClass' => '\addons\edm\models\Sbbol2PayDocRu\Sbbol2PayDocRuType',
            'jobs' => [
                'export' => '\addons\edm\jobs\ExportJob',
            ],
            'resources' => [
                'export' => 'xml',
            ],
            'dataView' => '@addons/edm/views/documents/_viewPaymentRegister',
        ],
        'edm:pain.001' => [
            'contentClass' => '\addons\ISO20022\models\Pain001CyberXmlContent',
            'typeModelClass' => '\addons\ISO20022\models\Pain001Type',
            'extModelClass' => '\addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt',
        ],
        'edm:auth.026' => [
            'contentClass' => '\addons\ISO20022\models\Auth026CyberXmlContent',
            'extModelClass' => '\addons\edm\models\BankLetter\BankLetterDocumentExt',
            'typeModelClass' => '\addons\ISO20022\models\Auth026Type',
            'jobs' => [
                'export' => '\addons\ISO20022\jobs\ExportJob',
            ],
            'dataView' => '@addons/ISO20022/views/documents/_view',
        ],
        'VTBPayDocRu' => [
            'contentClass' => '\addons\edm\models\VTBPayDocRu\VTBPayDocRuCyberXmlContent',
            'typeModelClass' => '\addons\edm\models\VTBPayDocRu\VTBPayDocRuType',
            'extModelClass' => '\addons\edm\models\PaymentRegister\PaymentRegisterDocumentExt',
        ],
        'VTBClientTerminalSettings' => [
            'contentClass' => '\addons\edm\models\VTBClientTerminalSettings\VTBClientTerminalSettingsXmlContent',
            'typeModelClass' => '\addons\edm\models\VTBClientTerminalSettings\VTBClientTerminalSettings',
        ],
        'VTBCurrConversion' => [
            'contentClass' => '\addons\edm\models\VTBCurrConversion\VTBCurrConversionCyberXmlContent',
            'extModelClass' => '\addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt',
            'typeModelClass' => '\addons\edm\models\VTBCurrConversion\VTBCurrConversionType',
        ],
        'VTBContractChange' => [
            'contentClass' => '\addons\edm\models\VTBContractChange\VTBContractChangeCyberXmlContent',
            'extModelClass' => '\addons\edm\models\VTBContractRequest\VTBContractRequestExt',
            'typeModelClass' => '\addons\edm\models\VTBContractChange\VTBContractChangeType',
        ],
        'VTBContractUnReg' => [
            'contentClass' => '\addons\edm\models\VTBContractUnReg\VTBContractUnRegCyberXmlContent',
            'extModelClass' => '\addons\edm\models\VTBContractRequest\VTBContractRequestExt',
            'typeModelClass' => '\addons\edm\models\VTBContractUnReg\VTBContractUnRegType',
        ],
        'VTBStatementQuery' => [
            'contentClass' => '\addons\edm\models\VTBStatementQuery\VTBStatementQueryCyberXmlContent',
            'extModelClass' => '\addons\edm\models\StatementRequest\StatementRequestExt',
            'typeModelClass' => '\addons\edm\models\VTBStatementQuery\VTBStatementQueryType',
            'resources' => [
                'export' => 'statementRequest',
                'import' => 'in'
            ],
            'jobs' => [
                'export' => '\addons\edm\jobs\ExportJob',
            ]
        ],
        'VTBStatementRu' => [
            'contentClass' => '\addons\edm\models\VTBStatementRu\VTBStatementRuCyberXmlContent',
            'extModelClass' => '\addons\edm\models\Statement\StatementDocumentExt',
            'typeModelClass' => '\addons\edm\models\VTBStatementRu\VTBStatementRuType',
            'resources' => [
                'export' => '1c',
            ],
            'jobs' => [
                'export' => '\addons\edm\jobs\ExportJob',
            ],
            'dataView' => '@addons/edm/views/documents/readable/statement',
            'actionView' => 'viewStatement',
        ],
        'VTBFreeClientDoc' => [
            'contentClass' => '\addons\edm\models\VTBFreeClientDoc\VTBFreeClientDocCyberXmlContent',
            'typeModelClass' => '\addons\edm\models\VTBFreeClientDoc\VTBFreeClientDocType',
            'extModelClass' => '\addons\edm\models\BankLetter\BankLetterDocumentExt',
        ],
        'VTBFreeBankDoc' => [
            'contentClass' => '\addons\edm\models\VTBFreeBankDoc\VTBFreeBankDocCyberXmlContent',
            'typeModelClass' => '\addons\edm\models\VTBFreeBankDoc\VTBFreeBankDocType',
            'extModelClass' => '\addons\edm\models\BankLetter\BankLetterDocumentExt',
        ],
        'VTBPayDocCur' => [
            'contentClass' => '\addons\edm\models\VTBPayDocCur\VTBPayDocCurCyberXmlContent',
            'extModelClass' => '\addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt',
            'typeModelClass' => '\addons\edm\models\VTBPayDocCur\VTBPayDocCurType',
        ],
        'VTBCurrDealInquiry181i' => [
            'contentClass' => '\addons\edm\models\VTBCurrDealInquiry181i\VTBCurrDealInquiry181iCyberXmlContent',
            'extModelClass' => '\addons\edm\models\ForeignCurrencyControl\ForeignCurrencyOperationInformationExt',
            'typeModelClass' => '\addons\edm\models\VTBCurrDealInquiry181i\VTBCurrDealInquiry181iType',
        ],
        'VTBCurrBuy' => [
            'contentClass' => '\addons\edm\models\VTBCurrBuy\VTBCurrBuyCyberXmlContent',
            'extModelClass' => '\addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt',
            'typeModelClass' => '\addons\edm\models\VTBCurrBuy\VTBCurrBuyType',
        ],
        'VTBCurrSell' => [
            'contentClass' => '\addons\edm\models\VTBCurrSell\VTBCurrSellCyberXmlContent',
            'extModelClass' => '\addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt',
            'typeModelClass' => '\addons\edm\models\VTBCurrSell\VTBCurrSellType',
        ],
        'VTBTransitAccPayDoc' => [
            'contentClass' => '\addons\edm\models\VTBTransitAccPayDoc\VTBTransitAccPayDocCyberXmlContent',
            'extModelClass' => '\addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt',
            'typeModelClass' => '\addons\edm\models\VTBTransitAccPayDoc\VTBTransitAccPayDocType',
        ],
        'VTBConfDocInquiry138I' => [
            'contentClass' => '\addons\edm\models\VTBConfDocInquiry138I\VTBConfDocInquiry138ICyberXmlContent',
            'extModelClass' => '\addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationExt',
            'typeModelClass' => '\addons\edm\models\VTBConfDocInquiry138I\VTBConfDocInquiry138IType',
        ],
        'VTBContractReg' => [
            'contentClass' => '\addons\edm\models\VTBContractReg\VTBContractRegCyberXmlContent',
            'extModelClass' => '\addons\edm\models\VTBContractRequest\VTBContractRequestExt',
            'typeModelClass' => '\addons\edm\models\VTBContractReg\VTBContractRegType',
        ],
        'VTBCredReg' => [
            'contentClass' => '\addons\edm\models\VTBCredReg\VTBCredRegCyberXmlContent',
            'extModelClass' => '\addons\edm\models\VTBContractRequest\VTBContractRequestExt',
            'typeModelClass' => '\addons\edm\models\VTBCredReg\VTBCredRegType',
        ],
        'VTBPrepareCancellationRequest' => [
            'contentClass' => '\addons\edm\models\VTBPrepareCancellationRequest\VTBPrepareCancellationRequestCyberXmlContent',
            'typeModelClass' => '\addons\edm\models\VTBPrepareCancellationRequest\VTBPrepareCancellationRequestType',
            'extModelClass' => '\addons\edm\models\VTBPrepareCancellationRequest\VTBPrepareCancellationRequestExt',
        ],
        'VTBPrepareCancellationResponse' => [
            'contentClass' => '\addons\edm\models\VTBPrepareCancellationResponse\VTBPrepareCancellationResponseCyberXmlContent',
            'typeModelClass' => '\addons\edm\models\VTBPrepareCancellationResponse\VTBPrepareCancellationResponseType',
        ],
        'VTBCancellationRequest' => [
            'contentClass' => '\addons\edm\models\VTBCancellationRequest\VTBCancellationRequestCyberXmlContent',
            'extModelClass' => '\addons\edm\models\VTBCancellationRequest\VTBCancellationRequestExt',
            'typeModelClass' => '\addons\edm\models\VTBCancellationRequest\VTBCancellationRequestType',
        ],
        'VTBRegisterRu' => [
            'contentClass' => '\addons\edm\models\VTBRegisterRu\VTBRegisterRuCyberXmlContent',
            'typeModelClass' => '\addons\edm\models\VTBRegisterRu\VTBRegisterRuType',
            'extModelClass' => '\addons\edm\models\PaymentRegister\PaymentRegisterDocumentExt',
            'dataView' => '@addons/edm/views/documents/_viewPaymentRegister',
        ],
        'VTBRegisterCur' => [
            'contentClass' => '\addons\edm\models\VTBRegisterCur\VTBRegisterCurCyberXmlContent',
            'typeModelClass' => '\addons\edm\models\VTBRegisterCur\VTBRegisterCurType',
            'extModelClass' => '\addons\edm\models\CurrencyPaymentRegister\CurrencyPaymentRegisterDocumentExt',
        ],
        'SBBOLPayDocRu' => [
            'contentClass' => '\addons\edm\models\SBBOLPayDocRu\SBBOLPayDocRuCyberXmlContent',
            'extModelClass' => '\addons\edm\models\PaymentRegister\PaymentRegisterDocumentExt',
            'typeModelClass' => '\addons\edm\models\SBBOLPayDocRu\SBBOLPayDocRuType',
            'jobs' => [
                'export' => '\addons\edm\jobs\ExportJob',
            ],
            'resources' => [
                'export' => 'xml',
            ],
            'dataView' => '@addons/edm/views/documents/_viewPaymentRegister',
        ],
        'SBBOLClientTerminalSettings' => [
            'contentClass' => '\addons\edm\models\SBBOLClientTerminalSettings\SBBOLClientTerminalSettingsXmlContent',
            'typeModelClass' => '\addons\edm\models\SBBOLClientTerminalSettings\SBBOLClientTerminalSettings',
        ],
        'SBBOLStatement' => [
            'contentClass' => '\addons\edm\models\SBBOLStatement\SBBOLStatementCyberXmlContent',
            'extModelClass' => '\addons\edm\models\Statement\StatementDocumentExt',
            'typeModelClass' => '\addons\edm\models\SBBOLStatement\SBBOLStatementType',
            'resources' => [
                'export' => '1c',
            ],
            'jobs' => [
                'export' => '\addons\edm\jobs\ExportJob',
            ],
            'dataView' => '@addons/edm/views/documents/readable/statement',
            'actionView' => 'viewStatement',
        ],
        'SBBOLStmtReq' => [
            'contentClass' => '\addons\edm\models\SBBOLStmtReq\SBBOLStmtReqCyberXmlContent',
            'extModelClass' => '\addons\edm\models\StatementRequest\StatementRequestExt',
            'typeModelClass' => '\addons\edm\models\SBBOLStmtReq\SBBOLStmtReqType',
            'resources' => [
                'export' => 'statementRequest',
                'import' => 'in'
            ],
            'jobs' => [
                'export' => '\addons\edm\jobs\ExportJob',
            ]
        ],
        'Sbbol2Statement' => [
            'contentClass' => '\addons\edm\models\Statement\StatementCyberXmlContent',
            'extModelClass' => '\addons\edm\models\Statement\StatementDocumentExt',
            'typeModelClass' => '\addons\edm\models\Sbbol2Statement\Sbbol2StatementType',
            'resources' => [
                'export' => '1c',
            ],
            'jobs' => [
                'export' => '\addons\edm\jobs\ExportJob',
            ],
            'dataView' => '@addons/edm/views/documents/readable/statement',
            'actionView' => 'viewStatement',
        ],
        'Sbbol2ClientTerminalSettings' => [
            'contentClass' => '\addons\edm\models\Sbbol2ClientTerminalSettings\Sbbol2ClientTerminalSettingsXmlContent',
            'typeModelClass' => '\addons\edm\models\Sbbol2ClientTerminalSettings\Sbbol2ClientTerminalSettings',
        ],
        'RaiffeisenStatement' => [
            'contentClass' => '\addons\edm\models\RaiffeisenStatement\RaiffeisenStatementCyberXmlContent',
            'extModelClass' => '\addons\edm\models\Statement\StatementDocumentExt',
            'typeModelClass' => '\addons\edm\models\RaiffeisenStatement\RaiffeisenStatementType',
            'resources' => [
                'export' => '1c',
            ],
            'jobs' => [
                'export' => '\addons\edm\jobs\ExportJob',
            ],
            'dataView' => '@addons/edm/views/documents/readable/statement',
            'actionView' => 'viewStatement',
        ],
        'RaiffeisenClientTerminalSettings' => [
            'contentClass' => '\addons\edm\models\RaiffeisenClientTerminalSettings\RaiffeisenClientTerminalSettingsXmlContent',
            'typeModelClass' => '\addons\edm\models\RaiffeisenClientTerminalSettings\RaiffeisenClientTerminalSettings',
        ],
    ],
    'documentTypeGroups' => include __DIR__ . '/documentTypeGroups.php',
    'menu' => include __DIR__ . '/menu.php',
    'regularJobs' => [
        [
            'descriptor' => 'edmImport',
            'class' => '\addons\edm\jobs\ImportJob',
            'interval' => 30,
        ],
        [
            'descriptor' => 'edmTemp',
            'class' => '\addons\edm\jobs\TempJob',
            'interval' => '07:00 weekday *',
        ],
        [
            'descriptor' => 'requestYesterdaysVTBStatements',
            'class' => '\addons\edm\jobs\VTBStatementRequestJob',
            'interval' => 60,
            'params' => [
                'date' => 'yesterday',
            ]
        ],
        [
            'descriptor' => 'requestTodaysVTBStatements',
            'class' => '\addons\edm\jobs\VTBStatementRequestJob',
            'interval' => 60,
            'params' => [
                'date' => 'today',
            ]
        ],
    ],
];
