<?php

namespace addons\SBBOL\states\in\sbbolDocument;

use addons\SBBOL\states\in\sbbolDocument\commonSteps\FinishProcessingStep;
use addons\SBBOL\states\in\sbbolDocument\processCertificatesSteps\InstallCertificatesIntoCryptoProStep;
use addons\SBBOL\states\in\sbbolDocument\processCertificatesSteps\SaveCertificatesStep;
use common\models\sbbolxml\response\CertificateType;

/**
 * @property CertificateType[] $sbbolDocuments
 */
class ProcessCertificatesState extends BaseSBBOLMultipleDocumentState
{
    protected $steps = [
        'saveCertificates' => SaveCertificatesStep::class,
        'installCertificatesIntoCryptoProStep' => InstallCertificatesIntoCryptoProStep::class,
        'finishProcessing' => FinishProcessingStep::class,
    ];
}
