<?php

namespace addons\SBBOL\states\in\sbbolDocument\processCertificatesSteps;

use addons\SBBOL\models\SBBOLCertificate;
use addons\SBBOL\states\BaseStep;
use addons\SBBOL\states\in\sbbolDocument\ProcessCertificatesState;
use common\models\sbbolxml\response\CertificateType;
use common\modules\certManager\components\ssl\X509FileModel;

/**
 * @property ProcessCertificatesState $state
 */
class SaveCertificatesStep extends BaseStep
{
    public function run()
    {
        $certificatesAttributes = array_reduce(
            $this->state->sbbolDocuments,
            function ($carry, CertificateType $certificate) {
                $certificateBody = base64_decode($certificate->value());

                if (!X509FileModel::isCertificate($certificateBody)) {
                    $this->log("Not a X.509 certificate, will not save it to database: $certificateBody");
                    return $carry;
                }

                $certificateX509 = X509FileModel::loadData($certificateBody);
                $carry[] = [
                    'validTo'     => $this->formatDateTime($certificateX509->getValidTo()),
                    'validFrom'   => $this->formatDateTime($certificateX509->getValidFrom()),
                    'commonName'  => @$certificateX509->getSubject()['CN'],
                    'fingerprint' => $certificateX509->getFingerprint(),
                    'serial'      => @$certificateX509->getRawData()['serialNumberHex'],
                    'status'      => SBBOLCertificate::STATUS_ACTIVE,
                    'type'        => $this->getCertificateType($certificate),
                    'body'        => $certificateBody,
                ];

                return $carry;
            },
            []
        );

        SBBOLCertificate::refreshAll($certificatesAttributes);

        return true;
    }

    private function formatDateTime(\DateTime $dateTime)
    {
        return $dateTime ? $dateTime->format('Y-m-d H:i:s') : null;
    }

    private function getCertificateType(CertificateType $certificate)
    {
        if ($certificate->getBank()) {
            return SBBOLCertificate::TYPE_BANK;
        } elseif ($certificate->getRoot()) {
            return SBBOLCertificate::TYPE_ROOT;
        }

        return null;
    }
}
